<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class DepartmentModel extends Model
{
    //
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'department';
    
    protected $primaryKey="id";
    protected $guarded = ['id'];
    public $timestamps = false;
    
     
}
