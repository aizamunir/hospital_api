<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Patient;

class Doctor extends Model
{
    use HasFactory;

    protected $table = "doctors";
    protected $primaryKey = "doctor_id";

    protected $fillable = [
        'name',
        'age',
        'phn_num',
        'speciality',
        'gender',
        'salary'
    ];

    public function patients() {
        return $this->hasMany(Patient::class, 'doctor_id', 'doctor_id');
    }
}
