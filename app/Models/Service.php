<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $table = "services";
    protected $primaryKey = "services_id";

    protected $fillable = [
        'name',
        'fees',
        'doctor_id',
        'duration',
        'status',
    ];
}
