<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Term extends Model
{
   //
   use SoftDeletes;
   protected $guarded = [];
   protected $hidden = [
       'created_at',
       'deleted_at',
       'updated_at',
       'created_by',
       'updated_by',
       'deleted_by'
   ];

   public function schoolYear(){
    return $this->belongsTo('App\SchoolYear');
   }

   public function schoolCategory(){
    return $this->belongsTo('App\SchoolCategory');
   }

   public function semester(){
    return $this->belongsTo('App\Semester');
   }


}
