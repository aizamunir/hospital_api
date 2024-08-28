<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
