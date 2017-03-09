<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class GradeSystemModel extends Model
{
     use LogsActivity;
    //
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'gradingsystem';
    protected static $logAttributes = ['value', 'grade'];
    protected $primaryKey="id";
    
    public $timestamps = false;
    
     
}
