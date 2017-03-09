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

class MemberController extends Controller {

    /**
     * Create a new controller instance.
     *

     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
        //  $this->log_query();
    }

    public function log_query() {
        \DB::listen(function ($sql, $binding, $timing) {
            \Log::info('showing query', array('sql' => $sql, 'bindings' => $binding));
        }
        );
    }

    public function show_query() {

        \DB::listen(function ($sql, $binding, $timing) {
            print_r("<pre>");
            var_dump($sql);
            var_dump($binding);
        }
        );
    }

    

    public function index(Request $request, SystemController $sys) {
        $member = Models\MemberModel::query();
       
 



        if ($request->has('search') && trim($request->input('search')) != "") {
            // dd($request);
            $member->where($request->input('by'), "LIKE", "%" . $request->input("search", "") . "%");
        }
        if ($request->has('library') && trim($request->input('library')) != "") {
            $member->where("library", $request->input("library", ""));
        }
        if ($request->has('region') && trim($request->input('region')) != "") {
            $member->where("region", $request->input("region", ""));
        }
        if ($request->has('status') && trim($request->input('status')) != "") {
            $member->where("status", $request->input("status", ""));
        }
         
        if ($request->has('category') && trim($request->input('category')) != "") {
            $member->where("category", $request->input("category", ""));
        }
         
        if ($request->has('gender') && trim($request->input('gender')) != "") {
            $member->where("gender", $request->input("gender", ""));
        }


        if ($request->has('religion') && trim($request->input('religion')) != "") {
            $member->where("religion", $request->input("religion", ""));
        }
         
        $data = $member->orderBy('firstname')->orderBy('category')->orderBy('cardNo')->paginate(500);

        $request->flashExcept("_token");
 $region = $sys->getRegions();

        $religion = $sys->getReligion();
        \Session::put('members', $data);
        return view('members.index')->with("data", $data)
                        ->with('library', $sys->getLibraries())->with('region', $region)
                        ->with('religion', $religion);
    }

    public function sms(Request $request, SystemController $sys) {
        ini_set('max_execution_time', 3000); //300 seconds = 5 minutes
        $message = $request->input("message", "");
        $query = \Session::get('students');



        foreach ($query as $rtmt => $member) {
            $NAME = $member->NAME;
            $FIRSTNAME = $member->FIRSTNAME;
            $SURNAME = $member->SURNAME;
            $PROGRAMME = $sys->getProgram($member->PROGRAMME);
            $INDEXNO = $member->INDEXNO;
            $CGPA = $member->CGPA;
            $BILLS = $member->BILLS;
            $BILL_OWING = $member->BILL_OWING;
            $PASSWORD = $sys->getStudentPassword($INDEXNO);
            $newstring = str_replace("]", "", "$message");
            $finalstring = str_replace("[", "$", "$newstring");
            eval("\$finalstring =\"$finalstring\" ;");
            if ($sys->firesms($finalstring, $member->TELEPHONENO, $member->INDEXNO)) {

                StudentModel::where("INDEXNO", $INDEXNO)->update(array("SMS_SENT", "1"));
            } else {
                // return redirect('/students')->withErrors("SMS could not be sent.. please verify if you have sms data and internet access.");
            }
        }
        return redirect('/students')->with('success', 'Message sent to students successfully');

        \Session::forget('students');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(SystemController $sys) {
        $region = $sys->getRegions();

        $religion = $sys->getReligion();
        return view('members.create')
                        ->with('region', $region)
                        ->with('religion', $religion);
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
        $user = @\Auth::user()->organization;



        $gender = $request->input('gender');
        $category = $request->input('category');

        $dob = $request->input('dob');
        $parent = $request->input('pname');
        $pphone = $request->input('pphone');

        $phone = $request->input('phone');
        $region = $request->input('region');
        $religion = $request->input('religion');
        $residentAddress = $request->input('contact');

        $hometown = $request->input('hometown');

        $title = $request->input('title');
        
        $fname = $request->input('fname');

        $lname = $request->input('surname');
        $othername = $request->input('othernames');
        $houseNo = $request->input('hn');
        $status = $request->input('status');
        $class = $request->input('form');
        $school = $request->input('school');
        $joined = $request->input('joined');
        $name = $lname . ' ' . $othername . ' ' . $fname;

        $sql = \DB::table('cardno')->get();
        $new_cardNo = $sql[0]->no;
        $cardNo = date("Y") . $new_cardNo;

        $memberCode = $cardNo;
        $query = new Models\MemberModel();
        $query->name = $name;
        $query->title = $title;
        $query->lastname = $lname;
        $query->firstname = $fname;
        $query->othernames = $othername;
        $query->gender = $gender;
        $query->dob = $dob;
        $query->address = $residentAddress;
        $query->region = $region;
        $query->religion = $religion;
        $query->hometown = $hometown;
        $query->cardNo = $memberCode;
        $query->category = $category;
        $query->status = $status;
        $query->dateJoined = $joined;
        $query->houseno = $houseNo;
        $query->parent = $parent;
        $query->parent_phone = $pphone;
        $query->school = $school;
        $query->contact = $phone;
        $query->class = $class;
        $query->library = $user;


        if ($query->save()) {
            \DB::table('cardno')->increment('no');
            \DB::commit();

            return response()->json(['status' => 'success', 'message' => $name . ' added successfully ']);
        } else {

            return response()->json(['status' => 'error', 'message' => 'Error adding member ']);
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

        $query = Models\MemberModel::where('member_id', $id)->firstorFail();



        $region = $sys->getRegions();



        $religion = $sys->getReligion();
        return view('members.edit')->with('data', $query)
                     
                        ->with('region', $region)
                        ->with('religion', $religion);
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
        $user = @\Auth::user()->organization;



        $gender = $request->input('gender');
        $category = $request->input('category');

        $dob = $request->input('dob');
        $parent = $request->input('pname');
        $pphone = $request->input('pphone');

        $phone = $request->input('phone');
        $region = $request->input('region');
        $religion = $request->input('religion');
        $residentAddress = $request->input('contact');

        $hometown = $request->input('hometown');

        $title = $request->input('title');
        
        $fname = $request->input('fname');

        $lname = $request->input('surname');
        $othername = $request->input('othernames');
        $houseNo = $request->input('hn');
        $status = $request->input('status');
        $class = $request->input('form');
        $school = $request->input('school');
        $joined = $request->input('joined');
        $name = $lname . ' ' . $othername . ' ' . $fname;
         $id= $request->input('id');
        $query=  Models\MemberModel::where("member_id",$id)->update(array(
            
            "name"=>$name,
            "firstname"=>$fname,
            "lastname"=>$lname,
            "gender"=>$gender,
            "title"=>$title,
            "contact"=>$phone,
            "houseno"=>$houseNo,
            "othernames"=>$othername,
            "address"=>$residentAddress,
            "hometown"=>$hometown,
            "status"=>$status,
            "class"=>$class,
            "school"=>$school,
            "dateJoined"=>$joined,
            "dob"=>$dob,
            "category"=>$category,
            "religion"=>$religion,
            "region"=>$region,
            "parent"=>$parent,
            "parent_phone"=>$pphone
            
        ));

        if ($query) {
             
            \DB::commit();

            return response()->json(['status' => 'success', 'message' => $name . ' updated successfully ']);
        } else {

            return response()->json(['status' => 'error', 'message' => 'Error updating member ']);
        }
         } catch (\Exception $e) {
            \DB::rollback();
        }
    }

     
    public function memberUploadForm() {
        return view("members.upload");
    }

    public function uploadMembers(Request $request, SystemController $sys) {

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
       

        $gender = $value->gender;
        $category =$value->category ;

        $dob = $value->dob ;
        $parent = $value->parent ;
        $pphone = $value->parent_phone ;

        $phone = $value->phone ;
        $region = $value->region ;
        $religion = $value->religion;
        $residentAddress = $value->address ;

        $hometown = $value->hometown;

        $title = $value->title ;
        
        $fname =$value->firstname;

        $lname = $value->lastname ;
        $othername = $value->othernames ;
        $houseNo = $value->houseno;
        $status = $value->status;
        $class = $value->class;
        $school = $value->school;
        $joined = $value->dateJoined;
        $name = $lname . ' ' . $othername . ' ' . $fname;

        $sql = \DB::table('cardno')->get();
        $new_cardNo = $sql[0]->no;
        $cardNo = date("Y") . $new_cardNo;

        $memberCode = $cardNo;
        $query = new Models\MemberModel();
        $query->name = $name;
        $query->title = $title;
        $query->lastname = $lname;
        $query->firstname = $fname;
        $query->othernames = $othername;
        $query->gender = $gender;
        $query->dob = $dob;
        $query->address = $residentAddress;
        $query->region = $region;
        $query->religion = $religion;
        $query->hometown = $hometown;
        $query->cardNo = $memberCode;
        $query->category = $category;
        $query->status = $status;
        $query->dateJoined = $joined;
        $query->houseno = $houseNo;
        $query->parent = $parent;
        $query->parent_phone = $pphone;
        $query->school = $school;
        $query->contact = $phone;
        $query->class = $class;
        $query->library = $user;


         $query->save();
            \DB::table('cardno')->increment('no');
            \DB::commit();

          
         
                            
                        
                        
                        
                        
                    }
                    
                 }

  // return response()->json(['status' => 'success', 'message' => $name . ' added successfully ']);
    
          return redirect("/members")->with("success","Members uploaded successfully");
                 } 
             else {
                    return response()->json(['status' => 'error', 'message' => 'Error adding member ']);
     
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
        Models\MemberModel::where("member_id",$request->input("id"))->delete();
        return redirect("/members")->with("success","Member deleted successfully");
    }

}
