<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;


class BorrowModel extends Model
{
    use LogsActivity;
    //
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'transactions';
     protected static $logAttributes = ['book_id', 'member_id','librarian'];
    protected $primaryKey="id";
    protected $guarded = ['id'];
    
  
    public function users(){
        return $this->belongsTo('App\User', "user","fund");
    }
    
     public function book(){
        return $this->belongsTo('App\Models\BookModel', "book_id","book_id");
    }
    public function member(){
        return $this->belongsTo('App\Models\MemberModel', "member_id","cardNo");
    }
      public function books(){
        return $this->hasMany('App\Models\BookModel', "book_id","book_id");
    }
     
}
