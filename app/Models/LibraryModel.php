<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class LibraryModel extends Model
{
     use LogsActivity;
    //
    /**
     * The database table used by the model.
     *
     * @var string
     */
       public $timestamps = false;
    protected $table = 'libraries';
      protected static $logAttributes = ['name', 'address','phone'];
    protected $primaryKey="id";
   protected $guarded = ['id']; 
}
