<?php

namespace App\Controllers\Api;

use App\Controllers\ApiController;
use App\Models\Donor;
use App\Services\Request;
use App\Core\Support\Log;

class DonorApiController extends ApiController
{
    public function index()
    {
        $donors = Donor::all();
        Log::info('Retrieved donors list', ['count' => count($donors)]);
        return response()->json([
            'success' => true,
            'message' => 'Donors retrieved successfully',
            'data' => $donors
        ], 200);
    }
}
