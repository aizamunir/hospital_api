<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Doctor;

class Patient extends Model
{
    use HasFactory;

    protected $table = "patients";
    protected $primaryKey = "patient_id";

    protected $fillable = [
        'name',
        'age',
        'phn_num',
        'disease',
        'gender',
        'weight',
        'height',
        'attendee',
        'doctor_id',
        'status'
    ];

    public function doctor() {
        return $this->belongsTo(Doctor::class, 'doctor_id', 'doctor_id');
    }
}
