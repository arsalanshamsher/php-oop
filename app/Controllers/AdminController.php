<?php

namespace App\Controllers;

use App\Services\Request; // Ens
use App\Models\Donor;

class AdminController
{

    // index 
    public function index()
    {
        $donors = Donor::all();
        // dd($donors); // Dump the data to check if it's fetching correctly
        return view('admin.index', ['donors' => $donors]);
    }
    // create
    public function create()
    {

        return view('admin.create');
    }
    // store function
    public function store(Request $request)
    {

        // 1️⃣ Validate Data
        $validatedData = $request->validate([
            'donor_name'   => 'required|string|max:255',
            'email'        => 'required|email',
            'phone_number' => 'required|string|max:15',
            'address'      => 'required|string',
            'donor_profile' => 'nullable|image|mimes:image/jpeg,image/png,image/jpg,image/gif|max:2048',
            'status'       => 'required|in:0,1'
        ]);

        // 2️⃣ Profile Image Upload
        if ($request->hasFile('donor_profile')) {
            $file = $request->file('donor_profile');
            $filename  = $file->getClientOriginalName();
            // Move file to public/uploads directory
            $file->move(public_path('uploads/donor_profiles'), $filename);
            // Save path to database
            $validatedData['donor_profile'] = 'uploads/donor_profiles/' . $filename;
        }

        // 3️⃣ Donor Object Create Karo
        $donor = new Donor();
        $donor->donor_name = $validatedData['donor_name'];
        $donor->email = $validatedData['email'];
        $donor->phone_number = $validatedData['phone_number'];
        $donor->address = $validatedData['address'];
        $donor->status = $validatedData['status'];

        // 4️⃣ Profile Image Agar Hai to Set Karo
        if (isset($validatedData['donor_profile'])) {
            $donor->donor_profile = $validatedData['donor_profile'];
        }

        // 5️⃣ Data Database Me Save Karo
        $donor->save();

        // 6️⃣ Redirect with Success Message
        return redirect()->route('admin-dashboard')->with('success', 'Donor Added Successfully!');
    }



    // show
    public function show(Request $request,$id)
    {
        
        $donor = Donor::where('donor_id', $id)->first();
        dd($donor);
       
        return view('admin.show', ['donor' => $donor]);
    }
}
