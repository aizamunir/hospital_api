<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $table = "schedules";
    protected $primaryKey = "schedule_id";

    protected $fillable = [
        'doctor_id',
        'patient_id',
        'description',
        'date',
        'start_time',
        'end_time'
    ];

    public function doctor() {
        return $this->belongsTo(Doctor::class, 'doctor_id', 'doctor_id');
    }

    public function patient() {
        return $this->belongsTo(Patient::class, 'patient_id', 'patient_id');
    }
}
