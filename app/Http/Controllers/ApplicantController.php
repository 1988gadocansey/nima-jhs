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
class ApplicantController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
//        //$this->generateAccounts();
//        $query=  \App\Models\StudentModel::where("LEVEL","!=","")->get();
//        foreach($query as $index=>$row){
//        $que=Models\PortalPasswordModel::where("username",$row->STNO)->first();  
//                  if(empty($que)){
//                   
//                   $real=strtoupper(str_random(9));
//                   
//                    Models\PortalPasswordModel::create([
//                    'username' => $row->STNO,
//                     'real_password' =>$real,
//                          'level' =>$row->LEVEL,
//                         'programme' =>$row->PROGRAMMECODE,
//                    'password' => bcrypt($real),
//                  ]);}
//        }
    }
    public function admitMessage(Request $request, SystemController $sys) {
         ini_set('max_execution_time', 3000); //300 seconds = 5 minutes
         $array = $sys->getSemYear();
         
        $fiscalYear = $array[0]->YEAR;
        
$sql=  Models\ApplicantModel::where("ADMITTED","1")->where("STATUS","ADMITTED")->where("ADMISSION_FEES",">","0")->where("SMS_SENT","0")->get();
        


        foreach($sql as $rtmt=> $member) {
            $NAME = $member->NAME;
            $FIRSTNAME = $member->FIRSTNAME;
            $SURNAME = $member->SURNAME;
            $PROGRAMME = $sys->getProgram($member->PROGRAMME_ADMITTED);
            $APPLICATION_NUMBER= $member->APPLICATION_NUMBER;
            
            
                         if (strpos($member->PROGRAMME_ADMITTED, "H") === 0)
                        {
                        $level = "100";
                        $year='1';
                        }
                        else{
                        $level = "400/1";
                        $year="400/1";
                        }
                         // move all the applicants to the student table
       $name = $NAME;
        $query = new StudentModel();
        $query->YEAR = $year;
        $query->LEVEL = $level;
        $query->FIRSTNAME = $FIRSTNAME;
        $query->SURNAME = $SURNAME;
        $query->OTHERNAMES = $member->OTHERNAME;
        $query->TITLE = $member->TITLE;
        $query->SEX = $member->GENDER;
        $query->DATEOFBIRTH = $member->DOB;
        $query->NAME = $name;
        $query->AGE =$sys->age($member->DOB, 'eu');
        //$query->GRADUATING_GROUP = $group;
        $query->MARITAL_STATUS = $member->MARITAL_STATUS;
        $query->HALL =$member->HALL_ADMITTED;
        $query->ADDRESS =$member->ADDRESS;
        $query->RESIDENTIAL_ADDRESS = $member->RESIDENTIAL_ADDRESS;
        $query->EMAIL =$member->EMAIL;
        $query->PROGRAMMECODE = $member->PROGRAMME_ADMITTED;
        $query->TELEPHONENO =$member->PHONE;
        $query->COUNTRY =$member->NATIONALITY;
        $query->REGION = $member->REGION;
        $query->RELIGION =$member->RELIGION;
        $query->HOMETOWN =$member->HOMETOWN;
        $query->GUARDIAN_NAME = $member->GURDIAN_NAME;
        $query->GUARDIAN_ADDRESS = $member->GURDIAN_ADDRESS;
        $query->GUARDIAN_PHONE = $member->GURDIAN_PHONE;
        $query->GUARDIAN_OCCUPATION = $member->GURDIAN_OCCUPATION;
        $query->DISABILITY = $member->PHYSICALLY_DISABLED;
        $query->STATUS = "In School";
        $query->SYSUPDATE = "1";
        $query->DATE_ADMITTED = "AUG".date("Y");
        //$query->GRADUATING_GROUP =$sys->graduatingGroup($indexno);
        $query->TYPE= $member->SESSION_PREFERENCE;
        

        $query->HOSTEL ="";
         $query->BILLS=$member->ADMISSION_FEES;
         $query->BILL_OWING=$member->ADMISSION_FEES;
        $query->STNO =$APPLICATION_NUMBER;
        $query->INDEXNO =$APPLICATION_NUMBER;

        if($query->save()){
           
               $que=Models\PortalPasswordModel::where("username",$APPLICATION_NUMBER)->first();  
                  if(empty($que)){
                    
                    $str = 'abcdefhkmnprtuvwxyz234678';
                    $shuffled = str_shuffle($str);
                    $vcode = substr($shuffled,0,9);
                   $real=strtoupper($vcode);
                   $level= $level;
                    Models\PortalPasswordModel::create([
                    'username' => $APPLICATION_NUMBER,
                     'real_password' =>$real,
                          'level' =>$level,
                         'programme' =>$member->PROGRAMME_ADMITTED,
                        'biodata_update' =>'1',
                    'password' => bcrypt($real),
                ]);
                  
                        \DB::commit();
                         $message ="Congratulations,$FIRSTNAME you have been admitted to Takoradi Technical University to persue $PROGRAMME. Goto admissions.tpolyonline.com to print your admission letter using your serial and pin code. Thanks ";
                if ($sys->firesms($message,$member->PHONE,$member->APPLICATION_NUMBER)) {
                   Models\ApplicantModel::where("APPLICATION_NUMBER",$APPLICATION_NUMBER)->update(array("SMS_SENT"=>"1"));
                   \DB::commit();
                
                }
                        $message2 = "Hi $FIRSTNAME, Please visit Accounts Office for Fee Payment Verification and proceed to portal.tpolyonline.com to do your course registration. use $APPLICATION_NUMBER as your username  and $real as password.Thanks ";
                   
                     
                    if ($sys->firesms($message2, $member->PHONE, $APPLICATION_NUMBER)) {
                        
                    }
                     
                  }
                 
                
            } else {
               // return redirect('/students')->withErrors("SMS could not be sent.. please verify if you have sms data and internet access.");
            }
        }
       
          return redirect('/applicants/view')->with('success','Message sent to Applicants successfully');
         
         
        
    }
    public function admit(Request $request, SystemController $sys) {
         
        $applicant=$request->input("applicant");
        $program=$request->input("program");
        $hall=$request->input("hall");
        $admit=$request->input("admit");
        $unadmit=$request->input("unadmit");
        $array=$sys->getSemYear();
       if($admit!=""){
        $fiscalYear=$array[0]->YEAR;
        if (strpos($program, "H") === 0)
        {
            $level="100";
        }
        else{
            $level="400/1";
        }
         
       $fee=$sys->getYearBill( $fiscalYear, $level, $program);
        $user=@\Auth::user()->id;
        Models\ApplicantModel::where("APPLICATION_NUMBER",$applicant)
            ->update(
                    array(
                        "PROGRAMME_ADMITTED"=>$program,
                        "ADMISSION_FEES"=>$fee,
                        "HALL_ADMITTED"=>strtoupper($hall),
                        "STATUS"=>"ADMITTED",
                        "ADMITTED"=>"1",
                        "DATE_ADMITTED"=>date("D M d, Y"),

                        "ADMITTED_BY_OFFICER"=>$user
                    )
                    )
            ;
         $fiscalYear = $array[0]->YEAR;
        
$sql=  Models\ApplicantModel::where("ADMITTED","1")->where("STATUS","ADMITTED")->where("APPLICATION_NUMBER",$applicant)->get();
        


        foreach($sql as $rtmt=> $member) {
            $NAME = $member->NAME;
            $FIRSTNAME = $member->FIRSTNAME;
            $SURNAME = $member->SURNAME;
            $PROGRAMME = $sys->getProgram($member->PROGRAMME_ADMITTED);
            $APPLICATION_NUMBER= $member->APPLICATION_NUMBER;
            
            
                         if (strpos($member->PROGRAMME_ADMITTED, "H") === 0)
                        {
                        $level = "100";
                        $year='1';
                        }
                        else{
                        $level = "400/1";
                        $year="400/1";
                        }
                         // move all the applicants to the student table
       $name = $NAME;
        $query = new StudentModel();
        $query->YEAR = $year;
        $query->LEVEL = $level;
        $query->FIRSTNAME = $FIRSTNAME;
        $query->SURNAME = $SURNAME;
        $query->OTHERNAMES = $member->OTHERNAME;
        $query->TITLE = $member->TITLE;
        $query->SEX = $member->GENDER;
        $query->DATEOFBIRTH = $member->DOB;
        $query->NAME = $name;
        $query->AGE =$sys->age($member->DOB, 'eu');
        //$query->GRADUATING_GROUP = $group;
        $query->MARITAL_STATUS = $member->MARITAL_STATUS;
        $query->HALL =$member->HALL_ADMITTED;
        $query->ADDRESS =$member->ADDRESS;
        $query->RESIDENTIAL_ADDRESS = $member->RESIDENTIAL_ADDRESS;
        $query->EMAIL =$member->EMAIL;
        $query->PROGRAMMECODE = $member->PROGRAMME_ADMITTED;
        $query->TELEPHONENO =$member->PHONE;
        $query->COUNTRY =$member->NATIONALITY;
        $query->REGION = $member->REGION;
        $query->RELIGION =$member->RELIGION;
        $query->HOMETOWN =$member->HOMETOWN;
        $query->GUARDIAN_NAME = $member->GURDIAN_NAME;
        $query->GUARDIAN_ADDRESS = $member->GURDIAN_ADDRESS;
        $query->GUARDIAN_PHONE = $member->GURDIAN_PHONE;
        $query->GUARDIAN_OCCUPATION = $member->GURDIAN_OCCUPATION;
        $query->DISABILITY = $member->PHYSICALLY_DISABLED;
        $query->STATUS = "In School";
        $query->SYSUPDATE = "1";
        $query->DATE_ADMITTED = "AUG".date("Y");
        //$query->GRADUATING_GROUP =$sys->graduatingGroup($indexno);
        $query->TYPE= $member->SESSION_PREFERENCE;
        

        $query->HOSTEL ="";
         $query->BILLS=$member->ADMISSION_FEES;
         $query->BILL_OWING=$member->ADMISSION_FEES;
        $query->STNO =$APPLICATION_NUMBER;
        $query->INDEXNO =$APPLICATION_NUMBER;

        if($query->save()){
           
               $que=Models\PortalPasswordModel::where("username",$APPLICATION_NUMBER)->first();  
                  if(empty($que)){
                    
                    $str = 'abcdefhkmnprtuvwxyz234678';
                    $shuffled = str_shuffle($str);
                    $vcode = substr($shuffled,0,9);
                   $real=strtoupper($vcode);
                   $level= $level;
                    Models\PortalPasswordModel::create([
                    'username' => $APPLICATION_NUMBER,
                     'real_password' =>$real,
                          'level' =>$level,
                         'programme' =>$member->PROGRAMME_ADMITTED,
                        'biodata_update' =>'1',
                    'password' => bcrypt($real),
                ]);
                  
                        \DB::commit();
                         $message ="Congratulations,$FIRSTNAME you have been admitted to Takoradi Technical University to persue $PROGRAMME. Goto admissions.tpolyonline.com to print your admission letter using your serial and pin code. Thanks ";
                if ($sys->firesms($message,$member->PHONE,$member->APPLICATION_NUMBER)) {
                   Models\ApplicantModel::where("APPLICATION_NUMBER",$APPLICATION_NUMBER)->update(array("SMS_SENT"=>"1"));
                   \DB::commit();
                
                }
                        $message2 = "Hi $FIRSTNAME, Please visit Accounts Office for Fee Payment Verification and proceed to portal.tpolyonline.com to do your course registration. use $APPLICATION_NUMBER as your username  and $real as password.Thanks ";
                   
                     
                    if ($sys->firesms($message2, $member->PHONE, $APPLICATION_NUMBER)) {
                        
                    }
                     
                  }
                 
                
            } else {
               // return redirect('/students')->withErrors("SMS could not be sent.. please verify if you have sms data and internet access.");
            }
        }
       
          return redirect('/applicants/view')->with('success','Message sent to Applicant successfully');
         
       }
       else{
           $user=@\Auth::user()->id;
        Models\ApplicantModel::where("APPLICATION_NUMBER",$applicant)
            ->update(
                    array(
                        "PROGRAMME_ADMITTED"=>"",
                        "ADMISSION_FEES"=>0,
                        "HALL_ADMITTED"=>"",
                        "STATUS"=>"APPLICANT",
                        "ADMITTED"=>"0",
                        "DATE_ADMITTED"=>"",

                        "ADMITTED_BY_OFFICER"=>$user
                    )
                    )
            ;
       }
    }
    public function generateAccounts() {
        ini_set('max_execution_time', 3000); //300 seconds = 5 minutes
         $user=  Models\PortalPasswordModel::where('year','2016/2017')->where('level','100')->get();
         foreach($user as $users=>$row){
             
             $student=$row->username;
             $password=  strtoupper(str_random(9));
             $hashedPassword = bcrypt($password);
             
             Models\PortalPasswordModel::where('username',$student)->where('level','100')->update(array("password" => $hashedPassword,'real_password'=>$password));
             
         } 
         
    }
    public function cards() {
        $query=  Models\FormModel::where("FORM_NO","0")->paginate(2500);
        return view("admissions.viewScratchCard")->with("data",$query);
    }
    public function index(Request $request, SystemController $sys) {
              $student = Models\ApplicantModel::query();
          
         
         if ($request->has('department') && trim($request->input('department')) != "") {
               $student->whereHas('programme', function($q)use ($request) {
            $q->whereHas('departments', function($q)use ($request) {
                $q->whereIn('DEPTCODE', [$request->input('department')]);
            });
        });
        }
        if ($request->has('type') && trim($request->input('type')) != "") {
               $student->whereHas('programme', function($q)use ($request) {
         
                $q->where('TYPE', [$request->input('type')]);
            
        });
        }
        
         if ($request->has('school') && trim($request->input('school')) != "") {
               $student->whereHas('programme', function($q)use ($request) {
                               $q->whereHas('departments', function($q)use ($request) {

            $q->whereHas('school', function($q)use ($request) {
                $q->whereIn('FACCODE', [$request->input('school')]);
            });
             });
        });
        }
        
         
         
        if ($request->has('search') && trim($request->input('search')) != "") {
            // dd($request);
            $student->where($request->input('by'), "LIKE", "%" . $request->input("search", "") . "%");
        }
        if ($request->has('program') && trim($request->input('program')) != "") {
            $student->where("FIRST_CHOICE", $request->input("program", ""))
                ->orWhere("SECOND_CHOICE", $request->input("program", ""))
                ->orWhere("THIRD_CHOICE", $request->input("program", ""))
                ;
        }
         
        if ($request->has('status') && trim($request->input('status')) != "") {
            $student->where("STATUS", $request->input("status", ""));
        }
        if ($request->has('group') && trim($request->input('group')) != "") {
            $student->where("YEAR_ADMISION", $request->input("group", ""));
        }
        if ($request->has('nationality') && trim($request->input('nationality')) != "") {
            $student->where("NATIONALITY", $request->input("nationality", ""));
        }
        if ($request->has('region') && trim($request->input('region')) != "") {
            $student->where("REGION", $request->input("region", ""));
        }
        if ($request->has('gender') && trim($request->input('gender')) != "") {
            $student->where("GENDER", $request->input("gender", ""));
        }
         
        if ($request->has('hall') && trim($request->input('hall')) != "") {
            $student->where("PREFERED_HALL", $request->input("hall", ""));
        }
        if ($request->has('religion') && trim($request->input('religion')) != "") {
            $student->where("RELIGION", $request->input("religion", ""));
        }
        if ($request->has('search') && trim($request->input('search')) != "" && trim($request->input('by')) != "") {
            // dd($request);
            $student->where($request->input('by'), "LIKE", "%" . $request->input("search", "") . "%")
               ->orWhere("APPLICATION_NUMBER","LIKE", "%" . $request->input("search", "") . "%");
        }
        $data = $student->orderBy('NAME')->orderBy('APPLICATION_NUMBER')->orderBy('FIRST_CHOICE')->paginate(100);

        $request->flashExcept("_token");

        \Session::put('students', $data);
        return view('admissions.applicants.index')->with("data", $data)
                        ->with('year', $sys->years())
                        ->with('nationality', $sys->getCountry())
                        ->with('halls', $sys->getHalls())
                        ->with('religion', $sys->getReligion())
                        ->with('region', $sys->getRegions())
                        ->with('department', $sys->getDepartmentList())
                        ->with('school', $sys->getSchoolList())
                        ->with('programme', $sys->getProgramList())
                        ->with('type', $sys->getProgrammeTypes());
         
         
    }
     public function sms(Request $request, SystemController $sys){
         ini_set('max_execution_time', 3000); //300 seconds = 5 minutes
         $message = $request->input("message", "");
        $query = \Session::get('students');
        


        foreach($query as $rtmt=> $member) {
            $NAME = $member->NAME;
            $FIRSTNAME = $member->FIRSTNAME;
            $SURNAME = $member->SURNAME;
            $PROGRAMME = $sys->getProgram($member->PROGRAMME_ADMITTED);
            $APPLICATION_NUMBER= $member->APPLICATION_NUMBER;
            $HALL_ADMITTED = $member->HALL_ADMITTED;
            $ADMISSION_FEES = $member->ADMISSION_FEES;
            
            $newstring = str_replace("]", "", "$message");
            $finalstring = str_replace("[", "$", "$newstring");
            eval("\$finalstring =\"$finalstring\" ;");
             if ($sys->firesms($finalstring,$member->PHONE,$member->APPLICATION_NUMBER)) {

                 Models\ApplicantModel::where("APPLICATION_NUMBER",$APPLICATION_NUMBER)->update(array("SMS_SENT","1"));
               
            } else {
               // return redirect('/students')->withErrors("SMS could not be sent.. please verify if you have sms data and internet access.");
            }
        }
          return redirect('/students')->with('success','Message sent to students successfully');
         
         \Session::forget('students');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function getIndex(Request $request)
    {
        
        return view('students.index');
    }
    public function anyData(Request $request)
    {
         
        $students = StudentModel::join('tpoly_programme', 'tpoly_students.PROGRAMMECODE', '=', 'tpoly_programme.PROGRAMMECODE')
           ->select(['tpoly_students.ID', 'tpoly_students.NAME','tpoly_students.INDEXNO', 'tpoly_programme.PROGRAMME','tpoly_students.LEVEL','tpoly_students.INDEXNO','tpoly_students.SEX','tpoly_students.AGE','tpoly_students.TELEPHONENO','tpoly_students.COUNTRY','tpoly_students.GRADUATING_GROUP','tpoly_students.STATUS']);
         


        return Datatables::of($students)
                         
            ->addColumn('action', function ($student) {
                 return "<a href=\"edit_student/$student->INDEXNO/id\" class=\"\"><i title='Click to view student details' class=\"md-icon material-icons\">&#xE88F;</i></a>";
                 // use <i class=\"md-icon material-icons\">&#xE254;</i> for showing editing icon
                //return' <td> <a href=" "><img class="" style="width:70px;height: auto" src="public/Albums/students/'.$student->INDEXNO.'.JPG" alt=" Picture of Employee Here"    /></a>df</td>';
                          
                                         
            })
               ->editColumn('id', '{!! $ID!!}')
            ->addColumn('Photo', function ($student) {
               // return '<a href="#edit-'.$student->ID.'" class="md-btn md-btn-primary md-btn-small md-btn-wave-light waves-effect waves-button waves-light">View</a>';
            
                return' <a href="show_student/'.$student->INDEXNO.'/id"><img class="md-user-image-large" style="width:60px;height: auto" src="Albums/students/'.$student->INDEXNO.'.JPG" alt=" Picture of Student Here"    /></a>';
                          
                                         
            })
              
            
            ->setRowId('id')
            ->setRowClass(function ($student) {
                return $student->ID % 2 == 0 ? 'uk-text-success' : 'uk-text-warning';
            })
            ->setRowData([
                'id' => 'test',
            ])
            ->setRowAttr([
                'color' => 'red',
            ])
                  
            ->make(true);
             
            //flash the request so it can still be available to the view or search form and the search parameters shown on the form 
      //$request->flash();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(SystemController $sys)
    {
        $region=$sys->getRegions();
             $programme=$sys->getProgramList();
        $hall=$sys->getHalls();
        $religion=$sys->getReligion();
        return view('students.create')
            ->with('programme', $programme)
            ->with('country', $sys->getCountry())
            ->with('region', $region)
            ->with('hall',$hall)
            ->with('religion',$religion);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, SystemController $sys)
    {
      
          set_time_limit(36000);
        /*transaction is used here so that any errror rolls
         *  back the whole process and prevents any inserts or updates
         */
 if($request->user()->isSupperAdmin || @\Auth::user()->role=="Dean" || @\Auth::user()->department=="top"|| @\Auth::user()->role=="HOD"){
       
  \DB::beginTransaction();

        $user = @\Auth::user()->id;
        
        $year=$request->input('year');
        if($year=='1'){
            $level='100';
        }
        elseif($year=='2'){
            $level='200';
        }
         elseif($year=='3'){
            $level='300';
        }
        elseif($year=='4'){
            $level='400';
        }
        else{
           $level= $year;
        }
         $indexno = $request->input('indexno');
         $program = $request->input('programme');
        $gender = $request->input('gender');
        $category = $request->input('category');
        $hostel = $request->input('hostel');
        $hall = $request->input('halls');
        $dob = $request->input('dob');
        $gname = $request->input('gname');
        $gphone = $request->input('gphone');
        $goccupation = $request->input('goccupation');
        $gaddress = $request->input('gaddress');
        $email = $request->input('email');
        $phone = $request->input('phone');
        $marital_status = $request->input('marital_status');
        $region = $request->input('region');
        $country = $request->input('nationality');
        $religion = $request->input('religion');
        $residentAddress = $request->input('contact');
        $address = $request->input('address');
        $hometown = $request->input('hometown');
        $nhis = $request->input('nhis');
        $type = $request->input('type');
        $disability = $request->input('disabilty');
        $title = $request->input('title');
        $age = $sys->age($dob, 'eu');
        $group = "";
        $fname = $request->input('fname');
        $bill= $request->input('bill');
        $lname = $request->input('surname');
        $othername = $request->input('othernames');
      
        $sql=  StudentModel::where("STNO",$indexno)->first();
        if(empty($sql)){
            /////////////////////////////////////////////////////
        
        $name = $lname . ' ' . $othername . ' ' . $fname;
        $query = new StudentModel();
        $query->YEAR = $year;
        $query->LEVEL = $level;
        $query->FIRSTNAME = $fname;
        $query->SURNAME = $lname;
        $query->OTHERNAMES = $othername;
        $query->TITLE = $title;
        $query->SEX = $gender;
        $query->DATEOFBIRTH = $dob;
        $query->NAME = $name;
        $query->AGE = $age;
        $query->GRADUATING_GROUP = $group;
        $query->MARITAL_STATUS = $marital_status;
        $query->HALL = $hall;
        $query->ADDRESS = $address;
        $query->RESIDENTIAL_ADDRESS = $residentAddress;
        $query->EMAIL = $email;
        $query->PROGRAMMECODE = $program;
        $query->TELEPHONENO = $phone;
        $query->COUNTRY = $country;
        $query->REGION = $region;
        $query->RELIGION = $religion;
        $query->HOMETOWN = $hometown;
        $query->GUARDIAN_NAME = $gname;
        $query->GUARDIAN_ADDRESS = $gaddress;
        $query->GUARDIAN_PHONE = $gphone;
        $query->GUARDIAN_OCCUPATION = $goccupation;
        $query->DISABILITY = $disability;
        $query->STATUS = "In School";
        $query->SYSUPDATE = "1";
        $query->NHIS = $nhis;
        $query->STUDENT_TYPE = $type;
        $query->TYPE = $category;

        $query->HOSTEL = $hostel;
         $query->BILLS=$sys->getYearBill($year, $level, $program);
         $query->BILL_OWING=$sys->getYearBill($year, $level, $program);
        $query->STNO =$indexno;
        $query->INDEXNO =$indexno;

        if($query->save()){
             \DB::commit();
               $que=Models\PortalPasswordModel::where("username",$indexno)->first();  
                  if(empty($que)){
                    $program=$program;
                    $str = 'abcdefhkmnprtuvwxyz234678';
                    $shuffled = str_shuffle($str);
                    $vcode = substr($shuffled,0,9);
                   $real=strtoupper($vcode);
                   $level= $level;
                    Models\PortalPasswordModel::create([
                    'username' => $indexno,
                     'real_password' =>$real,
                          'level' =>$level,
                         'programme' =>$program,
                        'biodata_update' =>'1',
                    'password' => bcrypt($real),
                ]);
                  
                     $message = "Hi $fname, Please visit portal.tpolyonline.com to do update your biodata with $indexno as your username  and $real as password and then follow to the Account office for fee verification ";
                   
                     \DB::commit();
                    if ($sys->firesms($message, $phone, $indexno)) {
                        
                    }
                  }
       return redirect("/students")->with("success"," <span style='font-weight:bold;font-size:13px;'> student successfully added!</span> ");
             
          }else{
           
             return redirect("/add_students")->with("error"," <span style='font-weight:bold;font-size:13px;'> student could not be added try again!</span>");
           
              
          }
        }
        else{
             return redirect("/add_students")->with("error"," <span style='font-weight:bold;font-size:13px;'>Please student exist in the system already!</span>");
           
        }
    } else{
            throw new HttpException(Response::HTTP_UNAUTHORIZED, 'This action is unauthorized.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id,  SystemController $sys,Request $request)
    {
        
         
        
        $query = Models\ApplicantModel::where('ID', $id)->first();
        $grades=@Models\ExamResultsModel::where("APPLICATION_NUMBER",$applicant)->get();
         
       
        
        return view('admissions.applicants.show')->with('student', $query)
            ->with('data', $grades);
             
            
    }
    public function uploadStaff(Request $request) {
       if($request->hasFile('file')){
            $file=$request->file('file');
            $user = \Auth::user()->id;
             
            $ext = strtolower($file->getClientOriginalExtension());
            $valid_exts = array('csv','xlx','xlsx'); // valid extensions
            
            $path = $request->file('file')->getRealPath();
         if (in_array($ext, $valid_exts)) {
            $data = Excel::load($path, function($reader) {
                        
                    })->get();

            if(!empty($data) && $data->count()){

				foreach ($data as $key => $value) {

		$insert[] = ['fullName' => $value->name, 'staffID' => $value->staff_id,'department'=>$value->department,'position'=>$value->position,'designation'=>$value->position,'phone'=>$value->phone];

				}

                                dd($insert);
				if(!empty($insert)){

					\DB::table('tpoly_workers')->insert($insert);
 
					 return redirect('/dashboard')->with("success",  " <span style='font-weight:bold;font-size:13px;'>Staff  successfully uploaded!</span> " );
                              

				}

			}

	}
        else{
              return redirect('/getStaffCSV')->with("error", " <span style='font-weight:bold;font-size:13px;'>Please upload file format must be xlx,csv,xslx!</span> ");
                             
        }
       }
		 
       }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id,  SystemController $sys,Request $request)
    {
        //
        if($request->user()->isSupperAdmin || @\Auth::user()->department=="top"|| @\Auth::user()->role=="HOD" || @\Auth::user()->role=="Support" ||   @\Auth::user()->role=="Dean"){
              
               $query = StudentModel::where('ID', $id)->where('STATUS','In school')->first();
               
         }
         else{  
        $query = StudentModel::where('ID', $id)->whereHas('programme', function($q) {
            $q->whereHas('departments', function($q) {
                $q->whereIn('DEPTCODE', array(@\Auth::user()->department));
            });
        })->first();
       
         }
         $region=$sys->getRegions();
        
        
        // make sure only students who are currently in school can update their data
         $programme=$sys->getProgramList();
        $hall=$sys->getHalls();
        $religion=$sys->getReligion();
        return view('students.edit')->with('data', $query)
            ->with('programme', $programme)
            ->with('country', $sys->getCountry())
            ->with('region', $region)
            ->with('hall',$hall)
            ->with('religion',$religion);
    }
public function gad()
    {
        //
        return view('autocomplete');
    }

    public function updateLevel()
    {
        $students=  StudentModel::query()->where('level'," ")->get();
            
         foreach ($students as $key => $row) {
              //$student= new StudentModel();
                  $indexno=$row->INDEXNO;
                 
                  $level= substr($indexno, 2,2);
                   //dd($level);
                  if($level=='15'){
                      StudentModel::where('INDEXNO','LIKE','0715%')->update(array("LEVEL"=>'100',"YEAR"=>'1'));
                  }
                  elseif($level=='14'){
                      
                      StudentModel::where('INDEXNO','LIKE','0714%')->update(array("LEVEL"=>'200',"YEAR"=>'2'));
                      
                  }
                  elseif($level=='13'){
                         
                       StudentModel::where('INDEXNO','LIKE','0713%')->update(array("LEVEL"=>'300',"YEAR"=>'3'));
                  }
                  else{
                         
                        //StudentModel::where('LEVEL','=','')->update(array("STATUS"=>'Alumni'));
                  }
               
         }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, SystemController $sys)
    {
        if($request->user()->isSupperAdmin || @\Auth::user()->role=="HOD" || @\Auth::user()->role=="Dean"||@\Auth::user()->department=="top" || @\Auth::user()->role=="Support"){
        {
       set_time_limit(36000);
        /*transaction is used here so that any errror rolls
         *  back the whole process and prevents any inserts or updates
         */

  \DB::beginTransaction();

        
        $indexno=$request->input('indexno');
     
         
        $gender=$request->input('gender');
        $category=$request->input('category');
        $hostel=$request->input('hostel');
        $hall=$request->input('halls');
        $dob=$request->input('dob');
        $gname=$request->input('gname');
        $gphone=$request->input('gphone');
        $goccupation=$request->input('goccupation');
        $gaddress=$request->input('gaddress');
        $email=$request->input('email');
        $phone=$request->input('phone');
        $marital_status=$request->input('marital_status');
        $region=$request->input('region');
        $country=$request->input('nationality');
        $religion=$request->input('religion');
        $residentAddress=$request->input('contact');
        $address=$request->input('address');
        $hometown=$request->input('hometown');
        $nhis=$request->input('nhis');
        $type=$request->input('type');
        $disability=$request->input('disabilty');
        $title=$request->input('title');
        $age=$sys->age($dob,'eu');
        $group=$sys->graduatingGroup($indexno);
        $firstname=$request->input('fname');
        $surname=$request->input('surname');
        $othername=$request->input('othernames');
        if( @\Auth::user()->role=="Support"){
             $query= StudentModel::where("ID",$id)->update(array(
                 "FIRSTNAME"=>$firstname,
                 "SURNAME"=>$surname,
                 "NAME"=>$surname." ".$othername." ".$firstname,
                "OTHERNAMES"=>$othername));
             
        }
        else{
        $query= StudentModel::where("ID",$id)->update(array(
                 "FIRSTNAME"=>$firstname,
                 "SURNAME"=>$surname,
                 "NAME"=>$surname." ".$othername." ".$firstname,
                "OTHERNAMES"=>$othername,
                "TITLE"=>$title,
                 "SEX"=>$gender,
                 "DATEOFBIRTH"=>$dob,
                 "AGE"=>$age,
                 "GRADUATING_GROUP"=>$group,
                 "MARITAL_STATUS"=>$marital_status,
                 "HALL"=>$hall,
                 "ADDRESS"=>$address,
                 "RESIDENTIAL_ADDRESS"=>$residentAddress,
                 "EMAIL"=>$email,
                 "TELEPHONENO"=>$phone,
                 "COUNTRY"=>$country,
                 "REGION"=>$region,
                 "RELIGION"=>$religion,
                 "HOMETOWN"=>$hometown,
                 "GUARDIAN_NAME"=>$gname,
                 "GUARDIAN_ADDRESS"=>$gaddress,
                 "GUARDIAN_PHONE"=>$gphone,  
                 "GUARDIAN_OCCUPATION"=>$goccupation,
                 "DISABILITY"=>$disability,
                 "STATUS"=>"In School",
                 "NHIS"=>$nhis,
                 "STUDENT_TYPE"=>$type,
                 "TYPE"=>$category,
                 "HOSTEL"=>$hostel,
                 "SYSUPDATE"=>"1"
            
                ));
        }
     \DB::commit();
         if(!$query){
      
          return redirect("/students")->withErrors("  N<u>o</u> :<span style='font-weight:bold;font-size:13px;'> data</span>could not be updated!");
          }else{
          
           return redirect("/students")->with("success"," <span style='font-weight:bold;font-size:13px;'>data successfully updated!</span> ");
              
        }}}
           else{
            throw new HttpException(Response::HTTP_UNAUTHORIZED, 'This action is unauthorized.');
        }
    }
    public function showUploadForm() {
        return view("students.upload");
    }
    public function applicantUploadForm() {
         return view("students.applicantUpload");
    }
    /*
     * upload continuing students 
     */
    public function uploadData(Request $request, SystemController $sys) {
        set_time_limit(36000);

         
            $user = \Auth::user()->id;
            $valid_exts = array('csv', 'xls', 'xlsx'); // valid extensions
            $file = $request->file('file');
            $path = $request->file('file')->getRealPath();

            $ext = strtolower($file->getClientOriginalExtension());
            
            if (in_array($ext, $valid_exts)) {

                $data = Excel::load($path, function($reader) {
                            
                        })->get();

                if (!empty($data) && $data->count()) {
                    foreach ($data as $key => $value) {

                        $num = count($data);
 
                        $indexno = "0" . $value->index_number;

                        $name = $value->name;
                        $program = $value->program;
                        $year = $value->year;

                        $group = $sys->graduatingGroup($indexno);
                        $bill = $value->bill;
                        $owing = $value->owing;

                        if ($year == 1) {
                            $level = 100;
                        } elseif ($year == 2) {
                            $level = 200;
                        } elseif ($year == 3) {
                            $level = 300;
                        } elseif ($year == 4) {
                            $level = 400;
                        } else {
                            $level = $year;
                        }
                        //  dd($year);
                        // first check if the students exist in the system if true then update else insert
                        $programme = $sys->programmeSearchByCode(); // check if the programmes in the file tally wat is in the db
                        if (array_search($program, $programme)) {

                            $testQuery = StudentModel::where('INDEXNO', $indexno)->first();
                            if (empty($testQuery)) {


                                $student = new StudentModel();
                                $student->INDEXNO = $indexno;
                                $student->LEVEL = @$level;
                                $student->YEAR = $year;
                                $student->NAME = $name;
                                $student->PROGRAMMECODE = $program;
                                $student->STATUS = "In School";
                                $student->SYSUPDATE = "1";
                                $student->BILLS = $bill;
                                $student->BILL_OWING = $owing;
                                $student->GRADUATING_GROUP = $group;
                                $student->save();
                                \DB::commit();
                            } else {

                                StudentModel::where('INDEXNO', $indexno)->update(array("LEVEL" => @$level, "YEAR" => $year, "PROGRAMMECODE" => $program, "SYSUPDATE" => "1", "STATUS" => 'In School', "NAME" => $name, "GRADUATING_GROUP" => $group, "BILLS" => $bill, "BILL_OWING" => $owing));
                                \DB::commit();
                            }
                        } else {
                            return redirect('/upload_students')->with("error", " <span style='font-weight:bold;font-size:13px;'>File contain unrecognize programme.please try again!</span> ");
                        }
                    }
                     return redirect('/students')->with("success", " <span style='font-weight:bold;font-size:13px;'>$num student(s) uploaded  successfully!</span> ");
           
                } else {
                    return redirect('/upload_students')->with("error", " <span style='font-weight:bold;font-size:13px;'>Please upload a excel file!</span> ");
                }


                } else {
                return redirect('/upload_students')->with("error", " <span style='font-weight:bold;font-size:13px;'>Only excel file is accepted!</span> ");
            }
        
    }
    public function uploadApplicants(Request $request,  SystemController $sys) {
       
      if($request->hasFile('file')){
             $file = $request->file('file');
           
            $user = \Auth::user()->id;
           $ext = strtolower($file->getClientOriginalExtension());
            $valid_exts = array('csv','xls','xlsx'); // valid extensions
             
            $path = $request->file('file')->getRealPath();
            
         if (in_array($ext, $valid_exts)) {
            $data = Excel::load($path, function($reader) {
                        
                    })->get();

            if(!empty($data) && $data->count()){
            $programme = $sys->programmeSearchByCode(); // check if the programmes in the file tally wat is in the db
              
        foreach($data as $key => $value) {

	$name=$value->surname." ".$value->othernames." ".$value->firstname;
				  
            if (in_array($value->program_code, $programme)) {
               
                  $testQuery=StudentModel::where('STNO', $value->admission_number)->first();
                          if(empty($testQuery)){
                              
                                if($value->gender=="M"){
                                    $gender="Male";
                                }
                                else{
                                    $gender="Female";
                                }
                                $year=$value->year;
                                 if ($year == 1) {
                                     $level = 100;
                                }
                                else{
                                    $level=$year;
                                }
                                $student=new StudentModel();
                               // $student->INDEXNO=$indexno;
                                $student->LEVEL=$level;
                                $student->YEAR= $year;
                                $student->NAME=$name;
                                $student->PROGRAMMECODE=$value->program_code;
                                $student->STATUS="Admitted";
                                $student->SYSUPDATE="1";
                                $student->OTHERNAMES=$value->othernames;
                                $student->SURNAME=$value->surname;
                                $student->FIRSTNAME=$value->firstname;
                                $student->ADDRESS=$value->address;
                                $student->RESIDENTIAL_ADDRESS=$value->residential;
                                $student->SEX=$gender;
                                $student->TELEPHONENO=$value->phone;
                                $student->STNO=$value->admission_number;
                                $student->BILLS=$value->bill;
                                $student->BILL_OWING=$value->owing;
                                $student->save();
                                
                                 \DB::commit();
                              }
                             else{
                                
                                         StudentModel::where('STNO', $value->admission_number)->update(array( "YEAR" =>$value->year, "PROGRAMMECODE" =>$value->program_code, "SYSUPDATE"=>"1", "STATUS" => 'Admitted',"NAME"=>$name,"BILLS"=>$value->bill,"BILL_OWING"=>$value->owing,"SEX"=>$value->gender,"FIRSTNAME"=>$value->firstname,"TELEPHONENO"=>$value->phone,"ADDRESS"=>$value->address,"SURNAME"=>$value->surname,"RESIDENTIAL_ADDRESS"=>$value->residential));
                                     \DB::commit();
                               }
                             
                            }
                             else{
                                  
                               return redirect('/upload_applicants')->with("error", " <span style='font-weight:bold;font-size:13px;'>File contains unrecognised programme codes.. please check your programme code!</span> ");
                      
                             }
                            
	    }
            
               
                                           return redirect('/students')->with("success", " <span style='font-weight:bold;font-size:13px;'>File upload successfull</span> ");
       
	}
        else{
              return redirect('/upload_applicants')->with("error", " <span style='font-weight:bold;font-size:13px;'>Please upload an excel file!</span> ");
                                      
        }
      }
     }
    
       
    }
//    public function uploadApplicants(Request $request, SystemController $sys) {
//        set_time_limit(36000);
//         \DB::beginTransaction();
//        try {
//
//            $user = \Auth::user()->id;
//            $valid_exts = array('csv'); // valid extensions
//            $file = $request->file('file');
//            $name = time() . '-' . $file->getClientOriginalName();
//            if (!empty($file)) {
//
//                $ext = strtolower($file->getClientOriginalExtension());
//                $destination = public_path() . '\uploads';
//                if (in_array($ext, $valid_exts)) {
//                    // Moves file to folder on server
//                    // $file->move($destination, $name);
//                    if (@$file->move($destination, $name)) {
//
//
//
//                        $handle = fopen($destination . "/" . $name, "r");
//                        //  print_r($handle);
//                        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
//
//                            $num = count($data);
//
//                            for ($c = 0; $c < $num; $c++) {
//                                $col[$c] = $data[$c];
//                            }
//
//                             $indexNo_query = Models\IndexNoModel::first();
//                	
//                            $indexno ="07".date("y").$indexNo_query->no;
//                            $year = $col[0];
//                            $level = $col[1];
//                            $surname = $col[2];
//                            $othername= $col[3];
//                            $name = $col[4];
//                            $gender = $col[5];
//                            $program = trim($col[7]);
//                            $phone = "0".$col[8];
//                            $contact= $col[9];
//                            $resident= $col[10];
//                            $group= $sys->graduatingGroup($indexno);
//                            $bill= $col[11];
//                            $owing = $col[12];
//                            $admissionNum=$col[13];
//                            
//                           // print_r($program)."<br/>"
//                        // first check if the students exist in the system if true then update else insert
//                     $programme = $sys->programmeSearchByCode(); // check if the programmes in the file tally wat is in the db
//                            if (array_search($program, $programme)) {
//         
//                        $testQuery=StudentModel::where('STNO', $admissionNum)->first();
//                          if(empty($testQuery)){
//                              
//                          
//                                $student=new StudentModel();
//                               // $student->INDEXNO=$indexno;
//                                $student->LEVEL=@$level;
//                                $student->YEAR=$year;
//                                $student->NAME=$name;
//                                $student->PROGRAMMECODE=$program;
//                                $student->STATUS="In School";
//                                $student->SYSUPDATE="1";
//                                 
//                                $student->GRADUATING_GROUP=$group;
//                                $student->SURNAME=$surname;
//                                $student->FIRSTNAME=$othername;
//                                $student->RESIDENTIAL_ADDRESS=$resident;
//                                $student->ADDRESS=$contact;
//                                $student->SEX=$gender;
//                                $student->TELEPHONENO=$phone;
//                                $student->STNO=$admissionNum;
//                                $student->save();
//                                 $indexNo_query->increment("no", 1);
//                                 \DB::commit();
//                              }
//                             else{
//                                
//                                         StudentModel::where('INDEXNO', $indexno)->update(array("LEVEL" =>@$level, "YEAR" => $year, "PROGRAMMECODE" => $program, "SYSUPDATE"=>"1", "STATUS" => 'In School',"NAME"=>$name,"GRADUATING_GROUP"=>$group,"BILLS"=>$bill,"BILL_OWING"=>$owing,"SEX"=>$gender,"FIRSTNAME"=>$othername,"TELEPHONENO"=>$phone,"RESIDENT_ADDRESS"=>$resident,"ADDRESS"=>$contact,"SURNAME"=>$surname));
//                                        \DB::commit();
//                               }
//                             
//                            }
//                            else{
//                               return redirect('/upload_applicants')->with("error", " <span style='font-weight:bold;font-size:13px;'>File contains unrecognised programme codes.. please check your programme code!</span> ");
//                      
//                            }
//                                               
//                        } 
//                              
//                       
//                        fclose($handle);
//                        return redirect('/students')->with("success",  " <span style='font-weight:bold;font-size:13px;'>student uploaded  successfully!</span> ");
//                             
//                    }
//                } else {
//                     return redirect('/upload_applicants')->with("error", " <span style='font-weight:bold;font-size:13px;'>Only csv (comma delimited ) file is accepted!</span> ");
//                                   
//                }
//            } else {
//                 return redirect('/upload_students')->with("error", " <span style='font-weight:bold;font-size:13px;'>Please upload a csv file!</span> ");
//                    
//        }}
//    catch (\Exception $e) {
//            \DB::rollback();
//        }
//    }
   /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
