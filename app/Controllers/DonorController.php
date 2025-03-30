<?php

namespace App\Controllers;

use App\Models\Donor;
use App\Services\Request;

class DonorController
{
    public function index()
    {
        $donors = Donor::all();
        // dd($donors); // Dump the data to check if it's fetching correctly
        return view('admin.donor.list', ['donors' => $donors]);
    }
    // create
    public function create(){
        return view('admin.donor.create');
    }
}
