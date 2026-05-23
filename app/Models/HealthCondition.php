<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HealthCondition extends Model
{
    protected $table = 'health_condition';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $guarded = [];

    public function Store($condition)
    {
        return $this->create($condition);
    }
}
