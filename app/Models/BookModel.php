<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;


class BookModel extends Model
{
    use LogsActivity;
    //
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'books';
     protected static $logAttributes = ['book_id', 'library','librarian'];
    protected $primaryKey="id";
    protected $guarded = ['id'];
    public $timestamps = false;
    
    public function libraries(){
        return $this->hasMany('App\Models\LibraryModel', "id","library");
    }
    
    public function librarys(){
        return $this->belongsTo('App\Models\LibraryModel', "id","library");
    }
    
     
}
