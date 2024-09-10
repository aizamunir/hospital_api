<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Patient;
use App\Models\Doctor;

class ActivityLog extends Model
{
    use HasFactory;

    protected $table = "activity_log";
    protected $primaryKey = "activity_log_id";

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'remarks',
        'date',
        'time'
    ];

    public function doctors() {
        return $this->hasMany(Doctor::class, 'doctor_id', 'doctor_id');
    }

    public function patients() {
        return $this->hasMany(Patient::class, 'patient_id', 'patient_id');
    }
}
