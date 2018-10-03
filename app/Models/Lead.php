<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lead extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];


    public function Product(){
        return $this->belongsTo('App\Models\Product');
    }

    public function Supplier(){
        return $this->belongsTo('App\Models\Supplier');
    }

    public function AdminStatus(){
        return $this->belongsTo('App\Models\Status','status_admin','id');
    }

    public function CallerStatus(){
        return $this->belongsTo('App\Models\Status','status_caller','id');
    }
}
