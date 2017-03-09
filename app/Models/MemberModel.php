<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;


class MemberModel extends Model
{
    use LogsActivity;
    //
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'member';
     protected static $logAttributes = ['firstname', 'lastname','cardNo','contact'];
    protected $primaryKey="id";
    protected $guarded = ['id'];
    public $timestamps = false;
    public function libraries(){
        return $this->hasMany('App\Models\LibraryModel', "library","id");
    }
    public function librarys(){
        return $this->belongsTo('App\Models\LibraryModel', "library","id");
    }
     
}
