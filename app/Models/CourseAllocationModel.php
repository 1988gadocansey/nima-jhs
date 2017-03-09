<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;


class CourseAllocationModel extends Model
{
    use LogsActivity;
    //
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'subjectallocations';
     protected static $logAttributes = ['teacherId', 'subject','term','year'];
    protected $primaryKey="id";
    protected $guarded = ['id'];
    public $timestamps = false;
    public function course(){
        return $this->belongsTo('App\Models\CourseModel', "subject","code");
    }
    public function courses(){
        return $this->hasMany('App\Models\CourseModel', "code","subject");
    }
     public function teacher(){
        return $this->belongsTo('App\Models\WorkerModel', "teacherId","emp_number");
    }
    /**
     * Get the user that owns the course.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
     
}
