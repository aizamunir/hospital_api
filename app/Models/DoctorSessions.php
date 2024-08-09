<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorSessions extends Model
{
    use HasFactory;

    protected $table = "doctor_sessions";
    protected $primaryKey = "doctor_session_id";

    protected $fillable = [
        'doctor_id',
        'time_slot',
        'days',
        'start_time',
        'end_time'
    ];
}
