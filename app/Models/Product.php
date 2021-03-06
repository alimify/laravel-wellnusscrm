<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    public $incrementing = false;

    public function Leads(){
        return $this->hasMany('App\Models\Lead');
    }
}
