<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;


class HouseModel extends Model
{
     use LogsActivity;
    //
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'house';
    protected static $logAttributes = ['name', 'staff','year'];
    protected $primaryKey="id";
    protected $guarded = ['id'];
    public $timestamps = false;
   public function teacher(){
        return $this->belongsTo('App\Models\WorkerModel', "master","staffID");
    }
}
