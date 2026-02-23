<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PPIC extends Model
{
    use HasFactory;
    protected $table = 'log_header_machine';
    protected $primaryKey = 'machine_id';
    protected $fillable = ['total_stroke', 'qty_actual'];
}
