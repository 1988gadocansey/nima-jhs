<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class ClassMembersModel extends Model
{
    //
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'classmembers';
    
    protected $primaryKey="id";
    protected $guarded = ['id'];
    
   public $timestamps = false;
    public function students(){
        return $this->belongsTo('App\Models\StudentModel', "student","indexNo");
    }
     public function classes(){
        return $this->belongsTo('App\Models\ClassModel', "class","name");
    }
}
