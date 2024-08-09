<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestList extends Model
{
    use HasFactory;

    protected $table = "test_list";
    protected $primaryKey = "test_id";

    protected $fillable = [
        'name',
        'test_category_id',
    ];
}
