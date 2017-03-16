<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Models\StudentModel;
use App\Models\ProgrammeModel;
use App\Models;
use App\User;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Excel;

class classMemberController extends Controller {

    /**
     * Create a new controller instance.
     *

     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
        //  $this->log_query();
    }

    
    public function discharges(Request $request, SystemController $sys) {
        $books = Models\BookModel::query()->where("status","!=","BORROWED");
       
 



        if ($request->has('search') && trim($request->input('search')) != "") {
            // dd($request);
            $books->where($request->input('by'), "LIKE", "%" . $request->input("search", "") . "%");
        }
        if ($request->has('status') && trim($request->input('status')) != "") {
            $books->where("status", $request->input("status", ""));
        }
         
        $data = $books->orderBy('book_title')->orderBy('status')->paginate(500);

        $request->flashExcept("_token");
 
        return view('books.discharge')->with("data", $data)->with('library', $sys->getLibraries());
                        
    }
    
     public function charges(Request $request, SystemController $sys) {
        $books = Models\BookModel::query()->where("status" ,"BORROWED");
       
 



        if ($request->has('search') && trim($request->input('search')) != "") {
            // dd($request);
            $books->where($request->input('by'), "LIKE", "%" . $request->input("search", "") . "%");
        }
        if ($request->has('status') && trim($request->input('status')) != "") {
            $books->where("status", $request->input("status", ""));
        }
         
        $data = $books->orderBy('book_title')->orderBy('status')->paginate(500);

        $request->flashExcept("_token");
 
        return view('books.charge')->with("data", $data)->with('library', $sys->getLibraries());
                        
    }
    

    public function classTeacherRemarks(Request $request, SystemController $sys) {
        
        $array = $sys->getSemYear();
                $term = $array[0]->term;
                $year = $array[0]->year;
                $class=$sys->getTeacherClass(@\Auth::user()->fund);
                $data= \DB::table('classmembers')
                ->join('classes', 'classmembers.class', '=', 'classes.name')
                 ->join('student', 'student.indexNo', '=', 'classmembers.student')
                ->where('student.currentClass', $class)
                ->where('classmembers.term', $term)
                 ->where('classmembers.year', $year)
                ->where('student.status', "In school")
                  ->select("student.name","student.indexno","");
                  


       if ($request->has('classs') && trim($request->input('classs')) != "") {
            $data->where("classmembers.class", $request->input("classs", ""));
        }
        
        if ($request->has('term') && trim($request->input('term')) != "") {
            $data->where("classmembers.term", $request->input("term", ""));
        }
        if ($request->has('year') && trim($request->input('year')) != "") {
            $data->where("classmembers.year", $request->input("year", ""));
        }
        
          
        $request->flashExcept("_token");
 
        
         
        $query= $data->orderBy('classmembers.total','Desc')->paginate(500);
        return view('classes.classTeacherRemarks')->with("data", $query)->with("class",$sys->getClassList())
                        ->with('year', $sys->years())->with("sem", $term);
                        
    }

     
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(SystemController $sys) {
        
        return view('books.create')
                         ;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, SystemController $sys) {

        set_time_limit(36000);
        /* transaction is used here so that any errror rolls
         *  back the whole process and prevents any inserts or updates
         */

        \DB::beginTransaction();
 try {
        $library = @\Auth::user()->organization;


         
        $title = $request->input('title');
        $copyno = $request->input('copyno');

        $status = $request->input('status');
        $category = $request->input('category');
        $edition = $request->input('edition');
        
         
        $publication= $request->input('publication');
        $place = $request->input('place');
        $publisher = $request->input('publisher');

        $copies = $request->input('copies');

        $accention = $request->input('accention');
        
        $isbn = $request->input('isbn');

        $copyright = $request->input('copyright');
        $received = $request->input('received');
        $added = $request->input('added');
        $collation = $request->input('collation');
        
        $class = $request->input('class');
        $format = $request->input('format');
        $location = $request->input('location');
        $type = $request->input('type');
        $author= $request->input('author');
        $series= $request->input('series');
        
        $query = new Models\BookModel();
        $query->book_title = $title;
        $query->copyno = $copyno;
        $query->author= $author;
        $query->category = $category;
        $query->accention_no = $accention;
        $query->date_published=$publication;
        $query->place_of_publication = $place;
        $query->book_copies = $copies;
        $query->book_pub = $publisher;
        $query->isbn = $isbn;
        $query->copyright_year = $copyright;
        $query->date_receive = $received;
        $query->date_added = $added;
        $query->collation= $collation;
        $query->class_mark = $class;
        $query->format = $format;
        $query->series = $series;
        $query->type = $type;
        $query->location= $location;
        
        $query->edition = $edition;
        $query->status = $status;
          
        if ($query->save()) {
            //\DB::table('cardno')->increment('no');
            \DB::commit();

            return response()->json(['status' => 'success', 'message' => $title . ' added successfully ']);
        } else {

            return response()->json(['status' => 'error', 'message' => 'Error adding book ']);
        }
         } catch (\Exception $e) {
            \DB::rollback();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, SystemController $sys, Request $request) {

        

        // make sure only students who are currently in school can update their data
        $query = Models\MemberModel::where('member_id', $id)->first();
        $transaction = @Models\BorrowModel::where('member_id', $id)->first();
         
        return view('members.show')->with('data', $query)
            ->with("transaction",$transaction);
                         
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, SystemController $sys, Request $request) {
        //

        $query = Models\BookModel::where('book_id', $id)->firstorFail();



         
        return view('books.edit')->with('data', $query);
                     
                         
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request) {
            \DB::beginTransaction();
 try {
        
         
        $title = $request->input('title');
        $copyno = $request->input('copyno');

        $status = $request->input('status');
        $category = $request->input('category');
        $edition = $request->input('edition');
        
         
        $publication= $request->input('publication');
        $place = $request->input('place');
        $publisher = $request->input('publisher');

        $copies = $request->input('copies');

        $accention = $request->input('accention');
        
        $isbn = $request->input('isbn');

        $copyright = $request->input('copyright');
        $received = $request->input('received');
        $added = $request->input('added');
        $collation = $request->input('collation');
        
        $class = $request->input('class');
        $format = $request->input('format');
        $location = $request->input('location');
        $type = $request->input('type');
        $author= $request->input('author');
        $series= $request->input('series');
         $id= $request->input('id');
        $query= Models\BookModel::where("book_id",$id)->update(array(
            
            "book_title"=>$title,
            "copyno"=>$copyno,
            "author"=>$author,
            "edition"=>$edition,
            "category"=>$category,
            "accention_no"=>$accention,
            "date_published"=>$publication,
            "place_of_publication"=>$place,
            "book_copies"=>$copies,
            "book_pub"=>$publisher,
            "isbn"=>$isbn,
            "copyright_year"=>$copyright,
            "date_receive"=>$received,
            "date_added"=>$added,
            "collation"=>$collation,
            "class_mark"=>$class,
            "format"=>$format,
            "series"=>$series,
            "type"=>$type,
            "location"=>$location,
            "status"=>$status
             
            
        ));

        if ($query) {
             
            \DB::commit();

            return response()->json(['status' => 'success', 'message' => $title . ' updated successfully ']);
        } else {

            return response()->json(['status' => 'error', 'message' => 'Error updating book ']);
        }
         } catch (\Exception $e) {
            \DB::rollback();
        }
    }

     
    public function bookUploadForm() {
        return view("books.upload");
    }

    public function uploadBooks(Request $request, SystemController $sys) {

        if ($request->hasFile('file')) {
            $file = $request->file('file');

            $user = \Auth::user()->organization;
            $ext = strtolower($file->getClientOriginalExtension());
            $valid_exts = array('csv', 'xls', 'xlsx'); // valid extensions

            $path = $request->file('file')->getRealPath();

            if (in_array($ext, $valid_exts)) {
                $data = Excel::load($path, function($reader) {
                            
                        })->get();

                if (!empty($data) && $data->count()) {
                  
                    foreach ($data as $key => $value) {

                       set_time_limit(36000);
       

         $library = @\Auth::user()->organization;


         
        $title = $value->book_title;
        $copyno = $value->copyno ;

        $status = $value->status ;
        $category =$value->category ;
        $edition = $value->edition ;
        
         
        $publication= $value->date_published ;
        $place = $value->place_of_publication ;
        $publisher = $value->book_pub ;

        $copies = $value->book_copies;

        $accention =$value->accention_no ;
        
        $isbn = $value->isbn ;

        $copyright = $value->copyright_year ;
        $received =$value->date_receive ;
        $added =$value->date_added ;
        $collation =$value->collation ;
        
        $class =$value->class_mark ;
        $format =$value->format ;
        $location = $value->location ;
        $type =$value->type ;
        $author= $value->author;
        $series= $value->series;
        
        $query = new Models\BookModel();
        $query->book_title = $title;
        $query->copyno = $copyno;
        $query->author= $author;
        $query->category = $category;
        $query->accention_no = $accention;
        $query->date_published=$publication;
        $query->place_of_publication = $place;
        $query->book_copies = $copies;
        $query->book_pub = $publisher;
        $query->isbn = $isbn;
        $query->copyright_year = $copyright;
        $query->date_receive = $received;
        $query->date_added = $added;
        $query->collation= $collation;
        $query->class_mark = $class;
        $query->format = $format;
        $query->series = $series;
        $query->type = $type;
        $query->location= $location;
        
        $query->edition = $edition;
        $query->status = $status;
         $query->library= $library;

         $query->save();
            

              }
                    
                 }

  // return response()->json(['status' => 'success', 'message' => $name . ' added successfully ']);
    
          return redirect("/books")->with("success","Books uploaded successfully");
                 } 
             else {
                    return response()->json(['status' => 'error', 'message' => 'Error uploading books ']);
     
               }
            
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request) {
        //
        Models\LibraryModel::where("id",$request->input("id"))->delete();
        return redirect("/books")->with("success","Book deleted successfully");
    }

}
