<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestCategory extends Model
{
    use HasFactory;

    protected $table = "test_catrgory";
    protected $primaryKey = "test_category_id";

    protected $fillable = [
        'name',
    ];
}
