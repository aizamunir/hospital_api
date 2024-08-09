<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    use HasFactory;

    protected $table = "prescriptions";
    protected $primaryKey = "prescription_id";

    protected $fillable = [
        'doctor_id',
        'patient_id',
        'description',
        'medicines',
        'next_visit'
    ];
}
