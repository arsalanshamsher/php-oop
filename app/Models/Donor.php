<?php

namespace App\Models;

use App\Core\Database\Model;

class Donor extends Model
{
    public static $table = 'donors'; // Assuming the table name is 'donors'

    // Define fillable fields based on the controller usage
    protected $fillable = [
        'donor_name',
        'email',
        'phone_number',
        'address',
        'status'
    ];
}
