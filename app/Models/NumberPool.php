<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NumberPool extends Model
{
    protected $table='sys_phonenumber_pool';
    protected $fillable=['phone_number', 'area', 'count', 'status'];
}
