<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiagnosticTest extends Model
{
    use HasFactory;

    protected $table = "diagnostic_tests";
    protected $primaryKey = "diagnostictest_id";

    protected $fillable = [
        'doctor_id',
        'patient_id',
        'description',
        'tests'
    ];
}
