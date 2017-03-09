<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class AcademicCalenderModel extends Model
{
     use LogsActivity;
    //
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'academicsettings';
    protected static $logAttributes = ['year', 'term','startDate','endDate'];
    protected $primaryKey="ID";
    protected $guarded=array ('id');
     public $timestamps = false;
}
