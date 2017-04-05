<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class DeletedGradesModel extends Model
{
     use LogsActivity;
    //
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'deletedGrades';
     protected $primaryKey="id";
    protected $guarded = ['id'];
    public $timestamps = false;
    
    
      protected $casts = [
        'lecturer' => 'int',
    ];

    /**
     * Get the user that owns the grades.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function academic(){
        return $this->belongsTo('App\Models\StudentModel', "indexNo","indexNo");
    }
      
}
