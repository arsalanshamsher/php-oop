<?php

namespace App\Controllers\Api;

use App\Controllers\ApiController;
use App\Models\Donor;

class DonorApiController extends ApiController
{
    /**
     * Get all donors
     */
    public function index()
    {
        try {
            $donors = Donor::all();
            return $this->success($donors, 'Donors retrieved successfully');
        } catch (\Exception $e) {
            return $this->error('Failed to retrieve donors: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get donor by ID
     */
    public function show($id)
    {
        try {
            $donor = Donor::where('id', $id)->first();
            
            if (!$donor) {
                return $this->notFound('Donor not found');
            }
            
            return $this->success($donor, 'Donor retrieved successfully');
        } catch (\Exception $e) {
            return $this->error('Failed to retrieve donor: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Create new donor
     */
    public function store()
    {
        try {
            $validatedData = $this->request->validateApi([
                'donor_name' => 'required|max:255',
                'email' => 'required|email',
                'phone_number' => 'required|max:15',
                'address' => 'required',
                'status' => 'required|integer'
            ]);

            $donor = new Donor();
            $donor->donor_name = $validatedData['donor_name'];
            $donor->email = $validatedData['email'];
            $donor->phone_number = $validatedData['phone_number'];
            $donor->address = $validatedData['address'];
            $donor->status = $validatedData['status'];

            $donor->save();

            return $this->success($donor, 'Donor created successfully', 201);
        } catch (\Exception $e) {
            return $this->error('Failed to create donor: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Update donor
     */
    public function update($id)
    {
        try {
            $donor = Donor::where('id', $id)->first();
            
            if (!$donor) {
                return $this->notFound('Donor not found');
            }

            $validatedData = $this->request->validateApi([
                'donor_name' => 'required|max:255',
                'email' => 'required|email',
                'phone_number' => 'required|max:15',
                'address' => 'required',
                'status' => 'required|integer'
            ]);

            $donor->donor_name = $validatedData['donor_name'];
            $donor->email = $validatedData['email'];
            $donor->phone_number = $validatedData['phone_number'];
            $donor->address = $validatedData['address'];
            $donor->status = $validatedData['status'];

            $donor->save();

            return $this->success($donor, 'Donor updated successfully');
        } catch (\Exception $e) {
            return $this->error('Failed to update donor: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Delete donor
     */
    public function destroy($id)
    {
        try {
            $donor = Donor::where('id', $id)->first();
            
            if (!$donor) {
                return $this->notFound('Donor not found');
            }

            $donor->delete();

            return $this->success(null, 'Donor deleted successfully');
        } catch (\Exception $e) {
            return $this->error('Failed to delete donor: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Search donors
     */
    public function search()
    {
        try {
            $query = $this->request->input('q', '');
            $status = $this->request->input('status', '');
            
            if (empty($query) && empty($status)) {
                return $this->error('Search query or status is required', 400);
            }

            // Simple search implementation
            $donors = Donor::all();
            $filteredDonors = [];

            foreach ($donors as $donor) {
                $match = false;
                
                if (!empty($query)) {
                    if (stripos($donor->donor_name, $query) !== false ||
                        stripos($donor->email, $query) !== false ||
                        stripos($donor->phone_number, $query) !== false ||
                        stripos($donor->address, $query) !== false) {
                        $match = true;
                    }
                }
                
                if (!empty($status) && $donor->status == $status) {
                    $match = true;
                }
                
                if ($match) {
                    $filteredDonors[] = $donor;
                }
            }

            return $this->success($filteredDonors, 'Search completed successfully');
        } catch (\Exception $e) {
            return $this->error('Search failed: ' . $e->getMessage(), 500);
        }
    }
}
