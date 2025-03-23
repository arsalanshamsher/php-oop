<?php

namespace App\Controllers;

use App\Models\Donor;

class AdminController
{

    // index 
    public function index()
    {
        $donors = Donor::all();
        // echo '<pre>';
        // print_r($donors);
        // echo '</pre>';
        // die;
        return view('admin.index', ['donors' => $donors]);
    }
    // show
    public function show($id)
    {
        $donor = Donor::where('donor_id',$id)->get();
        echo '<pre>';
        print_r($donor);
        echo '</pre>';
        die;
        return view('admin.show', ['donor' => $donor]);
    }
}
