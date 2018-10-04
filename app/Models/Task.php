<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
   public function User(){
       return $this->belongsTo('App\User');
   }



   public function Lead(){
       return $this->belongsTo('App\Models\Lead');
   }


}
