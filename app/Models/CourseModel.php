<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;


class CourseModel extends Model
{
     use LogsActivity;
    //
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'courses';
     protected static $logAttributes = ['name', 'class','code','user'];
    protected $primaryKey="id";
    protected $guarded = ['id'];
    public $timestamps = false;
    public function programme(){
        return $this->belongsTo('App\Models\ProgrammeModel', "pcode","code");
    }
   
     public function programs(){
        return $this->hasMany('App\Models\ProgrammeModel', "code","pcode");
    }
     
}
