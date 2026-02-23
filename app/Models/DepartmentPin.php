<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class DepartmentPin extends Model
{
    protected $table = 'DepartmentPin';

    protected $fillable = [
        'Department',
        'Pin',
    ];

    public $timestamps = false;
}
