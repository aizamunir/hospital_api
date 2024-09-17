<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Patient;
use App\Models\Doctor;

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

    public function doctor() {
        return $this->belongsTo(Doctor::class, 'doctor_id', 'doctor_id');
    }

    public function patient() {
        return $this->belongsTo(Patient::class, 'patient_id', 'patient_id');
    }
}
