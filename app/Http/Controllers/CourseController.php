<?php

namespace App\Http\Controllers;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models;
use App\User;
use App\Models\AcademicRecordsModel;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Excel;

class CourseController extends Controller
{
     
    /**
     * Create a new controller instance.
     *
     
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
       
        
    }
     public function log_query() {
        \DB::listen(function ($sql, $binding, $timing) {
            \Log::info('showing query', array('sql' => $sql, 'bindings' => $binding));
        }
        );
    }
     public function uploadMounted(SystemController $sys,Request $request){

         if (@\Auth::user()->role == 'HOD' || @\Auth::user()->role == 'Support' || @\Auth::user()->role == 'Registrar' || @\Auth::user()->department == 'top') {

            if ($request->isMethod("get")) {

                return view('courses.uploadMounted');
            } else {

                set_time_limit(36000);

                $valid_exts = array('csv', 'xls', 'xlsx'); // valid extensions
                $file = $request->file('file');
                $name = time() . '-' . $file->getClientOriginalName();
                if (!empty($file)) {

                    $ext = strtolower($file->getClientOriginalExtension());

                    if (in_array($ext, $valid_exts)) {
                        // Moves file to folder on server
                        // $file->move($destination, $name);

                        $path = $request->file('file')->getRealPath();
                        $data = Excel::load($path, function($reader) {
                                    
                                })->get();
                        $total = count($data);

                        if (!empty($data) && $data->count()) {

                            $user = \Auth::user()->id;
                            $courseError=array();
                            $programError=array();
                            foreach ($data as $value => $row) {
                               
                                $code = $row->code;
                                $program = $row->program;
                                $courseID=$sys->getCourseByCode2($code,$program);                   
                                $credit = $row->credit;
                                $type = $row->type;
                                $level = $row->level;
                                $name = $row->course;
                                $year = $row->year;
                                $semester = $row->semester;
                                $searchCourse = $sys->courseSearchByCode();
                                $programme = $sys->programmeSearchByCode(); // check if the programmes in the file tally wat is in the db
                                if (in_array($program, $programme)) {
                                    if (in_array($code, $searchCourse)) {
                                        $testQuery = Models\MountedCourseModel::where('COURSE', $courseID)->where("COURSE_year",$year)
                                                ->where("COURSE_term",$semester)
                                                ->first();

                                        if (empty($testQuery)) {


                                            $course = new Models\MountedCourseModel();
                                            $course->COURSE = $courseID;
                                            $course->COURSE_CODE = $code;
                                            $course->COURSE_CREDIT = $credit;
                                            $course->PROGRAMME = $program;
                                            $course->COURSE_term = $semester;
                                            $course->COURSE_LEVEL = $level;
                                            $course->COURSE_TYPE = $type;
                                             $course->COURSE_year = $year;
                                            $course->MOUNTED_BY = $user;
                                            $course->save();
                                            \DB::commit();
                                        } else {

                                            Models\MountedCourseModel::where('COURSE', $courseID)->update(array("COURSE_CODE" =>$code,"COURSE_LEVEL" =>$level, "COURSE_term" => $semester, "PROGRAMME" => $program, "COURSE_CREDIT" => $credit, "COURSE_TYPE" => $type, "COURSE_year" => $year,"MOUNTED_BY" => $user));
                                            \DB::commit();
                                        }
                                    } else {
                                        array_push($courseError, $name." ".$code);
                                      //  redirect('/upload/courses')->with("error", " <span style='font-weight:bold;font-size:13px;'>File contain unrecognize courses.please try again!</span> ");
                                  //  dd($courseError);
                                    continue;
                                    }
                                } else {
                                      array_push($programError, $sys->getProgram($program));
                                      continue;
                                      // redirect('/upload/courses')->with("error", " <span style='font-weight:bold;font-size:13px;'>File contain unrecognize programme.please try again!</span> ");
                                }
                            }
                            if(!empty($programError) || !empty($courseError)){
              return     redirect('/upload/mounted')->with("errorP",$programError)
                   ->with("errorC",$courseError);
                           
            }
                        }
                    } else {
                        return redirect('/upload/mounted')->with("error", " <span style='font-weight:bold;font-size:13px;'>Only excel file is accepted!</span> ");
                    }
                } else {
                    return redirect('/upload/mounted')->with("error", " <span style='font-weight:bold;font-size:13px;'>Please upload an excel file!</span> ");
                }
            }
            
            
            return redirect('/mounted_view')->with("success", " <span style='font-weight:bold;font-size:13px;'>$total Courses mounted successfully</span> ");
            
            
            } else {

            return redirect("/dashboard");
        }
    }
    public function updateMounted(SystemController $sys,Request $request, $id) {
         if (@\Auth::user()->role == 'HOD' || @\Auth::user()->role == 'Support'|| @\Auth::user()->role == 'Registrar' || @\Auth::user()->department == 'top') {

          if ($request->isMethod("get")) {
              $lecturers=$sys->getLectureList_All();
              $yearList=$sys->years();
              $program=$sys->getProgramList();
              $course=$sys->getCourseList();
               $user=@\Auth::user()->staffID;
                $array = $sys->getSemYear();
                $sem = $array[0]->term;
                $year = $array[0]->year;
                $query=  @Models\MountedCourseModel::query()
                       ->where("COURSE_year",$year)
                        ->where("ID",$id)
                       ->where("COURSE_term",$sem)->paginate(20);
                
                
           return view('courses.editMounted')->with("data",@$query)
                   ->with("lecturer",$lecturers)
                   ->with("program",$program)
                    ->with("course",$course)
                    ->with("ID",$id)
                   ->with("years",$yearList);
                    
                    
                            
           } 
           else{
                \DB::beginTransaction();
                try {
                    
                    $upper = $request->input('upper');
                    
                    $sem = $request->input('semester');
                    $credit = $request->input('credit');
                    $level = $request->input('level');
                    $type = $request->input('type');
                    $course = $request->input('course');
                    
                    $lecturer = $request->input('lecturer');
                   
                    $key = $request->input('key');
                    for ($i = 0; $i < $upper; $i++) {
                        $courseArr = $course[$i];
                        
                        $levelArr = $level[$i];
                        $semArr = $sem[$i];
                        $creditArr = $credit[$i];
                        $typeArr = $type[$i];
                        $keyArr = $key[$i];
                        $lecturerArr = $lecturer[$i];
                       
                         Models\MountedCourseModel::where("ID", $key)
                                 ->update(array("COURSE_CREDIT" => $creditArr,   "COURSE_term" => $semArr, "COURSE_TYPE" => $typeArr, "COURSE_LEVEL" => $levelArr, "LECTURER" => $lecturerArr));

                         \DB::commit();
                         Models\AcademicRecordsModel::where("course", $key)->update(array("credits" => $creditArr,   "sem" => $semArr, "level" => $levelArr, "lecturer" => $lecturerArr));

                    }
                     return redirect("/mounted_view")->with("success","Mounted courses updated successfully");
                }
                catch (\Exception $e) {
                    \DB::rollback();
                }
         }
         }
        else{
            return redirect("/dashboard");
        }
         
    }
    public function uploadCourse(SystemController $sys,Request $request){

       if (@\Auth::user()->role == 'HOD' || @\Auth::user()->role == 'Support'|| @\Auth::user()->role == 'Registrar' || @\Auth::user()->department == 'top') {

          if ($request->isMethod("get")) {

           return view('courses.uploadCourse');
                            
           } 
           else{

               set_time_limit(36000);
         
 
        
           
           $valid_exts = array('csv','xls','xlsx'); // valid extensions
           $file = $request->file('file');
           $name = time() . '-' . $file->getClientOriginalName();
           if (!empty($file)) {
              
               $ext = strtolower($file->getClientOriginalExtension());
               
               if (in_array($ext, $valid_exts)) {
                   // Moves file to folder on server
                   // $file->move($destination, $name);
                    
                         $path = $request->file('file')->getRealPath();
                      $data = Excel::load($path, function($reader) {

			})->get();
                        $total=count($data);
                        
                       if(!empty($data) && $data->count()){
 
                            $user = \Auth::user()->fund;
                               foreach($data as $value=>$row)
                               {
                                   $code=$row->code;
                                   $program=$row->pcode;
                                   
                                 
                                   $name=$row->name;
                                    $programme = $sys->programmeSearchByCode(); // check if the programmes in the file tally wat is in the db
                           if (in_array($program, $programme)) {
   
                       $testQuery=Models\CourseModel::where('code', $code)->first();
                      
                         if(empty($testQuery)){
                             
                         
                               $course = new Models\CourseModel();
                                           $course->code = $code;
                                           $course->name = $name;
                                          
                                           $course->pcode = $pcode;
                                           
                                           $course->createdBy = $user;
                                           $course->save();
                                           \DB::commit();
                                       }
                         else{
                               
      @Models\CourseModel::where('code', $code)->update(array("name" =>@$name, "code" => $code, "pcode" => $program ));
                                       \DB::commit();
                         }
                               }
                               else{
                                      redirect('/upload/courses')->with("error", " <span style='font-weight:bold;font-size:13px;'>File contain unrecognize programme.please try again!</span> ");
                   
                               }
                       
                               
                               
                               
                               
                      } 
                   }
               } else {
                    return redirect('/upload/courses')->with("error", " <span style='font-weight:bold;font-size:13px;'>Only excel file is accepted!</span> ");
                                  
               }
           } else {
                return redirect('/upload/courses')->with("error", " <span style='font-weight:bold;font-size:13px;'>Please upload an excel file!</span> ");
                   
           }
         }
    
       return redirect('/courses')->with("success", " <span style='font-weight:bold;font-size:13px;'>$total Courses uploaded successfully</span> ");
              

       }
     
       
     else{

           throw new HttpException(Response::HTTP_UNAUTHORIZED, 'This action is unauthorized.');
      
     }
       

    }
    public function transcript(SystemController $sys,Request $request){

        if (@\Auth::user()->role == 'HOD' || @\Auth::user()->role == 'Support' || @\Auth::user()->role == 'Registrar' || @\Auth::user()->department == 'top') {

           if ($request->isMethod("get")) {

            return view('courses.showTranscript');
                             
            } 
            else{

                $student=  explode(',',$request->input('q'));
                $student=$student[0];
                
              
                
             $sql=Models\StudentModel::where("INDEXNO",$student)->first();
                     
               
               if(count($sql)==0){
           
                return redirect("/transcript")->with("error","<span style='font-weight:bold;font-size:13px;'> $request->input('q') does not exist!</span>");
                }
              else{
                    
                  $array=$sys->getSemYear();
                  $sem=$array[0]->term;
                  $year=$array[0]->year;
                  
                 
               $data=$this->transcriptHeader($sql, $sys)  ;
              $record=$this->generateTranscript($sql->ID,$sys);
      return view("courses.transcript")->with('grade',$record)->with("student",$data);
         
                  
                   
                  
              }
            }

        }

    }
    public function transcriptHeader($student, SystemController $sys) {
        ?>
<div class="md-card">
  
        <div   class="uk-grid" data-uk-grid-margin>

            <table  border="0" cellspacing="0" align="center" style="margin-left:32px">
                        <tr></tr>
                        <tr>
                            <th height="341" valign="top" class="bod" scope="row"><table width="100%" border="0">
                            <tr>
                                <th align="center" valign="middle" scope="row"><table width="930" height="113" border="0">
                                <tr>
                                    <th align="center" valign="middle" scope="row">
                                <fieldset>
                                <table style="" width="882" height="113" border="0" >
                                    <tr>
                                        <td>
                                            <table>
                                                <tr>
                                                    <td class="heading_a uk-text-bold">TAKORADI TECHNICAL UNIVERSITY</td>


                                                </tr>
                                                <Tr>
                                                    <td class="heading_a">Directorate of Academics Affairs</td>
                                                </Tr>
                                                <tr>
                                                    <td class="heading_c">Statement of Results</td>
                                                </tr>
                                            </table>
                                        </td>
                                        <td align='right'> <img src="<?php echo url('public/assets/img/logo.png')?>" style='width: 111px;height: auto;margin-left: -71px'/></td>
                                    </tr>
                                     
                                        
                                </table>
                                </fieldset>
                                
                                </tr>


                            </table>
                            
                            <div align="center">

                                <table border='0' class=" " align="center"  width='900px'>
                                    <tr>
                                        <td width="" style="width:69%">
                                            <center>
                                                <table border='0' class="biodata" width='800px' width=""  style="margin-left:-19px" >
                                                    <tbody>
                                                         <tr>
                                                            <td class="uk-text-bold"style="padding-right: px;">INDEX NUMBER</td> <td style="padding-right: 93px;"><?php echo $student->INDEXNO;?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="uk-text-bold" style="">NAME</td> <td style="padding-right: 36px;"><?php echo strtoupper($student->TITLE .' '.  $student->NAME)?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="uk-text-bold"style="">GENDER</td> <td style="padding-right: 36px;"><?php echo strtoupper($student->SEX)?></td>
                                                        </tr>
                                                         <tr>
                                                            <td class="uk-text-bold">PROGRAMME</td> <td style="padding-right: 177px;"><?php echo strtoupper($student->program->PROGRAMME)?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="uk-text-bold" style="">DATE OF ADMISSION</td> <td style="padding-right: 36px;"><?php echo strtoupper($student->DATE_ADMITTED)?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="uk-text-bold" style="">DATE OF BIRTH</td> <td style="padding-right: 36px;"><?PHP echo  $student->DATEOFBIRTH ; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="uk-text-bold" style="">CLASS</td> <td style="padding-right: 36px;" class="uk-text-success uk-text-bold"><?PHP echo  strtoupper($student->CLASS) ; ?></td>
                                                        </tr>
                                                        


                                                    </tbody></table> </center>
                                        </td>
                                        <td width="15">&nbsp;    
                                        <img   style="width:130px;height: auto;margin-left: 14px"  <?php
                                     $pic = $student->INDEXNO;
                                      
                                     ?>   src='<?php echo url("public/albums/students/$pic.jpg")?>' alt="  Affix student picture here"    />
           
                                        
                                        </td>
                                        
                                    </tr>
                                    </tr>
                                </table> <!-- end basic infos -->

  
                                
                                
                               
                            </div>
                              
</div>
                            </tr>
                        </table></th>
                        </tr>
                        <tr></tr>
                    </table>

    <?php
        
    }
    public function generateTranscript($sql,  SystemController $sys){
         
       $records=  Models\AcademicRecordsModel::where("student",$sql)->groupBy("year")->groupBy("level")->orderBy("level")->get();
                                

	  ?>

      
        <table width='800px' style="text-align:left; ;font-size: 16px" height="90" class=""  border="0" style="margin-left:32px">
        <tr>
        
          <td  style=" " align="left"> 
            <?php  
              $gpoint=0.0;
                $totcredit=0;
                $totgpoint=0.0;
                $gcredit=0;
                $b=0.0;
                $a=0;
            foreach ($records as $row){
            for($i=1;$i<3;$i++){
             $query=  Models\AcademicRecordsModel::where("student",$sql)->where("year",$row->year)->where("sem",$i)->get()->toArray();
                
              
                if(count($query)>0){

                echo "<div class='uk-text-bold' align='left' style='margin-left:33px'>year : ".$row->year."    ";
                echo ", term : ".$i;
                 echo ", LEVEL :  " .$row->level." <hr/></div>";
                 

                  
                 
              
                
         ?>
         
            <div class="uk-overflow-container">
           <table style="margin-left:32px"  border="0" style=""width='940px'  class="uk-table uk-table-striped">
                <thead >
                    <tr class="uk-text-bold" style="background-color:#1A337E;color:white;">
                    <td  width="86">CODE</td>
                    <td  width="558">COURSE</td>
                    <td align='center' width="48">CR</td>
                    <td align='center' width="49">GD</td>
                    <td align='center'width="95" >GP</td>
                    </tr>
                </thead>
                <tbody>
                  <?php 
		  
                foreach ($query as $rs){

                 

                ?>
                  <tr>
                    <td> <?php $object=$sys->getCourseByCodeObject($rs['course']); echo @$object[0]->COURSE_CODE; ?></td>
                    <td> <?php 
			 echo @$object[0]->COURSE_NAME;?> </td>	
                    
                    <td align='center'><?php  $gcredit+=$rs['credits'];   $totcredit+=$rs['credits'];$a+=$totcredit; if($rs['credits']){ echo $rs['credits'];} else{echo "IC";};?></td>
                    <td align='center'><?php  if($rs['grade']){ echo $rs['grade'];} else{echo "IC";}?></td>
                    <td align='center'>
                      <?php   $gpoint+=$rs['gpoint']; $totgpoint+=$rs['gpoint'];$b+=$totgpoint;if($rs['gpoint']){ echo $rs['gpoint'];} else{echo "0";}  ?></td>
                            
                     
                     
                    <?php 
                     } ?>
                  </tr>
                  <tr>
                     
                      <td>&nbsp</td>
                    
                      <td class="uk-text-bold"><span>GPA</span> <?php echo  number_format(@($gpoint/$gcredit), 2, '.', ',');?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>
                       
                    <td class="uk-text-bold" align='center'><?php echo $gcredit; ?></td>
                    <td >&nbsp;</td>
                    <td class="uk-text-bold" align='center'><?php echo $gpoint; ?>&nbsp;</td>
                  </tr>
                  <tr>
                     
                      <td>&nbsp</td>
                    
                      <td class="uk-text-bold"><span>CGPA</span> <?php echo  number_format(@($totgpoint/$totcredit), 2, '.', ',');?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>
                       
                    <td class="uk-text-bold" align='center'><?php echo   $totcredit; ?></td>
                    <td >&nbsp;</td>
                    <td class="uk-text-bold" align='center'><?php echo $totgpoint;   $b="";$a=""; ?>&nbsp;</td>
                  </tr>
                        
                </tbody>
                
                 
                    </table> 
        <?php }else{
            echo "<p class='uk-text-danger'>No results to display</p>";
        ?><?php }?>
                <p>&nbsp;</p>     
             </div><?php }  }
    
    ?> 
        
        
        </tr>
  
      </table> 
         
</div></div>
        
   <?php }
    public function subjectAllocator(Request $request,SystemController $sys)
    {
       
          if($request->user()->isSupperAdmin  ||     @\Auth::user()->department=="top"){
       
          $courses= Models\CourseAllocationModel::query() ;
           
          }
          else{
            $courses= Models\CourseAllocationModel::query()->where("teacherId",@\Auth::user()->fund) ;
            
          }
          
        if ($request->has('classs') && trim($request->input('classs')) != "") {
            $courses->where("classId", $request->input("classs", ""));
        }
        if ($request->has('staff') && trim($request->input('staff')) != "") {
            $courses->where("teacherId", $request->input("staff", ""));
        }
        if ($request->has('term') && trim($request->input('term')) != "") {
            $courses->where("term", $request->input("term", ""));
        }
        if ($request->has('year') && trim($request->input('year')) != "") {
            $courses->where("year", $request->input("year", ""));
        }
        
        $data = $courses->orderBy("classId")->paginate(100);
        
        $request->flashExcept("_token");
          
         
        return view('courses.allocation')->with("data", $data)->with("class",$sys->getClassList())
                        ->with('year', $sys->years())->with('subject', $sys->getCourseList())->with('teacher', $sys->getLectureListAllocation())
         ->with('staff', $sys->getLectureList());
         
       
    }
    
    public function classList(Request $request,SystemController $sys)
    {
       
          if($request->user()->isSupperAdmin  ||     @\Auth::user()->department=="top"){
       
          $courses= Models\CourseAllocationModel::query() ;
           
          }
          else{
            $courses= Models\CourseAllocationModel::query()->where("teacherId",@\Auth::user()->fund) ;
            
          }
          
        if ($request->has('classs') && trim($request->input('classs')) != "") {
            $courses->where("classId", $request->input("classs", ""));
        }
        if ($request->has('staff') && trim($request->input('staff')) != "") {
            $courses->where("teacherId", $request->input("staff", ""));
        }
        if ($request->has('term') && trim($request->input('term')) != "") {
            $courses->where("term", $request->input("term", ""));
        }
        if ($request->has('year') && trim($request->input('year')) != "") {
            $courses->where("year", $request->input("year", ""));
        }
        
        $data = $courses->orderBy("classId")->paginate(100);
        
        $request->flashExcept("_token");
          
          
        return view('classes.classList')->with("data", $data)->with("class",$sys->getClassList())
                        ->with('year', $sys->years())->with('subject', $sys->getCourseList()) ;
         
       
    }
    
    
    
    
    public function allocationCreator(Request $request,SystemController $sys){
        if ($request->isMethod("get")) {
             return view('courses.createAllocation')->with("class",$sys->getClassList())
                        
         ->with('staff', $sys->getLectureList())->with('subject', $sys->getCourseList());
        
        }
        else{
          
        $this->validate($request, [


                    'staff' => 'required',
                    'subject' => 'required',
                    'classs' => 'required',
                    
                ]);
                  $array = $sys->getSemYear();
                $sem = $array[0]->term;
                $year = $array[0]->year;
                    $staff=$request->input("staff");
                    $class=$request->input("classs");
                    $subject=$request->input("subject");
                   // dd($program);
                      $user=@\Auth::user()->fund;
         
                \DB::beginTransaction();
                try {
                   
                        $allocator = new Models\CourseAllocationModel();
                $allocator->subject = $subject;
                $allocator->teacherId = $staff;
                $allocator->classId = $class;
                $allocator->year = $year;
                $allocator->term = $sem;
                $allocator->createdBy = $user;
                \DB::commit();
                  
                       if( $allocator->save()){
                            \DB::commit();
                        return response()->json(['status'=>'success','message'=>' Subject allocated to staff successfully ']);
      
                       }
                       else{
                           return response()->json(['status'=>'error','message'=> ' Error allocating subject. try again ']);
       
                       }
                    
                } catch (\Exception $e) {
                    \DB::rollback();
                }
        }
    }
    // updating course allocation
     public function updateAllocation(Request $request,SystemController $sys){
        // dd($request);
                    $upper = $request->input('upper');
                    
                    $teacher = $request->input('staff');
                   
                   // $term = $request->input('term');
                    $class = $request->input('class');
                    $key = $request->input('key');
                  
                    $array = $sys->getSemYear();
                $term = $array[0]->term;
                $year = $array[0]->year;
                    
                    for ($i = 0; $i < $upper; $i++) {
                        $teacherArr = $teacher[$i];
                        
                       
                        
                        $keyArr = $key[$i];
                        $classArr = $class[$i];
                       
                         Models\CourseAllocationModel::where("id", $keyArr)
                                 ->update(array("teacherId" => $teacherArr,   "classId" => $classArr, "year" => $year, "term" => $term));

                       
                        
                    }
                         return response()->json(['status'=>'success','message'=>' Subject allocated to staff successfully ']);
      
     }
    /**
     * Display a list of all of the user's task.
     *
     * @param  Request  $request
     * @return Response
     */
    public function index(Request $request,SystemController $sys)
    {
          if($request->user()->isSupperAdmin  ||     @\Auth::user()->department=="top"){
       
          $courses= Models\CourseModel::query() ;
          }
          elseif(@\Auth::user()->role=="HOD" || @\Auth::user()->role=="Lecturer" || @\Auth::user()->role=="Registrar") {
            $courses = Models\CourseModel::where('pcode', '!=', '')->whereHas('programs', function($q) {
            $q->whereHas('departments', function($q) {
                $q->whereIn('deptCode', array(@\Auth::user()->department));
            });
        }) ;
        }
        
         if ($request->has('search') && trim($request->input('search')) != "") {
            // dd($request);
            $courses->where($request->input('by'), "LIKE", "%" . $request->input("search", "") . "%");
        }
        if ($request->has('program') && trim($request->input('program')) != "") {
            $courses->where("pcode", $request->input("program", ""));
        }
        if ($request->has('level') && trim($request->input('level')) != "") {
            $courses->where("classId", $request->input("level", ""));
        }
        
        
        $data = $courses->orderBy("name")->paginate(100);
        
        $request->flashExcept("_token");
          
         
        return view('courses.index')->with("data", $data)->with("class",$sys->getClassList())
                        ->with('program', $sys->getProgramList());
                 
    }
    public function viewMounted(Request $request,SystemController $sys) {
      $hod=@\Auth::user()->staffID;
      
//      if(@\Auth::user()->department=="top"){
//           $courses= Models\MountedCourseModel::query() ;
//      }
//      elseif(@\Auth::user()->role=="Lecturer"){
//          $courses= Models\MountedCourseModel::query()->where('LECTURER',@\Auth::user()->staffID) ;
//      }
//
//      else{
//          $courses= Models\MountedCourseModel::query()->where('MOUNTED_BY',$hod) ;
//      }
      
         if($request->user()->isSupperAdmin  ||     @\Auth::user()->department=="top"){
       
           $courses= Models\MountedCourseModel::query() ;
          }
          elseif(@\Auth::user()->role=="HOD" || @\Auth::user()->role=="Support" || @\Auth::user()->role=="Registrar") {
             $courses =Models\MountedCourseModel::where('COURSE', '!=', '')->whereHas('courses', function($q) {
            $q->whereHas('programs', function($q) {
                $q->whereIn('DEPTCODE', array(@\Auth::user()->department));
            });
        }) ;
        }
      
      
      
         if ($request->has('search') && trim($request->input('search')) != "") {
            // dd($request);
            $courses->where($request->input('by'), "LIKE", "%" . $request->input("search", "") . "%");
        }
        if ($request->has('program') && trim($request->input('program')) != "") {
            $courses->where("PROGRAMME", $request->input("program", ""));
        }
        if ($request->has('level') && trim($request->input('level')) != "") {
            $courses->where("COURSE_LEVEL", $request->input("level", ""));
        }
        if ($request->has('semester') && trim($request->input('semester')) != "") {
            $courses->where("COURSE_term", "=", $request->input("semester", ""));
        }
        if ($request->has('year') && trim($request->input('year')) != "") {
            $courses->where("COURSE_year", "=", $request->input("year", ""));
        }
         
        
        $data = $courses->paginate(100);
        
        $request->flashExcept("_token");
          
         
        return view('courses.view_mounted')->with("data", $data)
                        ->with('program', $sys->getProgramList())
                        ->with('year',$sys->years());
    }
    public function viewRegistered(Request $request,SystemController $sys , User $user, Models\AcademicRecordsModel $record) {
        
        //$this->authorize('update',$record); // in Controllers
        /*if(Gate::allows('updatesss',$record)){
            abort(403,"No authorization");
        }*/
        $array = $sys->getSemYear();
        $sem = $array[0]->term;
        $year = $array[0]->year;
        $person=@\Auth::user()->staffID;
        $lecturer=@\Auth::user()->staffID;

       // dd($request->user()->isSupperAdmin);
        if(@\Auth::user()->role=='Lecturer' || @\Auth::user()->role=='HOD' ||@\Auth::user()->role=='Dean'){
            
       
        /*
         * make sure that only courses mounted for a 
         * lecturer is available to him
         */
        
          $courses= Models\AcademicRecordsModel::query()->where('lecturer', $person) ;
           
          
        }
        elseif($request->user()->isSupperAdmin){
             
             $courses= Models\AcademicRecordsModel::query() ;
     
        }
        else{
            //abort(420, "Illegal access detected");
             return response('Unauthorized.', 401);
        }
         if ($request->has('search') && trim($request->input('search')) != "") {
            // dd($request);
            $courses->where('course',   $sys->getCourseByIDCode($request->input("search", "")));
            
        }
         
        if ($request->has('level') && trim($request->input('level')) != "") {
            $courses->where("level", $request->input("level", ""));
        }
        if ($request->has('semester') && trim($request->input('semester')) != "") {
            $courses->where("sem", "=", $request->input("semester", ""));
        }
        if ($request->has('year') && trim($request->input('year')) != "") {
            $courses->where("year", "=", $request->input("year", ""));
        }
         $data = $courses->groupby('course')->paginate(100);
         
         $request->flashExcept("_token");
         
         foreach ($data as $key => $row) {
                   
                    $arr=$sys->getCourseCodeByID($row->course);
                   // dd($arr);
                     $data[$key]->CODE=$arr;

                     $total=$sys->totalRegistered($sem,$year,$row->course,$row->level, $lecturer);
                     $data[$key]->REGISTERED=$total;
                }
        
                 
          
         
        return view('courses.registered_courses')->with("data", $data)
                        ->with('program', $sys->getProgramList())
                        ->with('year',$sys->years());
                        
           
    }
     public function mountCourse(SystemController $sys) {
          if(@\Auth::user()->role=='HOD' || @\Auth::user()->role=='Support' || @\Auth::user()->role=='Registrar'){
        $programme=$sys->getProgramList();
        
        $course=$sys->getCourseList();
        //$lecturer=$sys->getLectureList();
        $allLectureres=$sys->getLectureList_All();
       // $totalLecturers = array_merge( $lecturer, $allLectureres);
         return view('courses.mount')->with('program', $programme)
                 ->with('course', $course)
                 ->with('lecturer',$allLectureres);
          }
          else{
            throw new HttpException(Response::HTTP_UNAUTHORIZED, 'This action is unauthorized.');
        }
    }
    
    public function create(SystemController $sys) {
       if(@\Auth::user()->role=='Admin' || @\Auth::user()->department=='top'){
        $programme=$sys->getProgramList();
         return view('courses.create')->with('programme', $programme);
       }
       else{
            return redirect("/dashboard");
        }
    }
    public function show(Request $request) {
        
    }
    /**
     * Create a new task.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        if(@\Auth::user()->department=='top' || @\Auth::user()->role=='Admin'){
        \DB::beginTransaction();
        try {
            $this->validate($request, [
                'name' => 'required',
                'program' => 'required',
                'code' => 'required',
                 
            ]);

            $user=@\Auth::user()->fund;

            $name = strtoupper($request->input('name'));
            $program = strtoupper($request->input('program'));
            $code = strtoupper($request->input('code'));

            $course = new Models\CourseModel();
            $course->name = $name;
            $course->pcode= $program;
            $course->code = $code;
            $course->createdBy= $user;
             

            if ($course->save()) {
                 \DB::commit();
                return redirect("/courses")->with("success", "<span style='font-weight:bold;font-size:13px;'> $name added successfully</span> ");
            } else {

                return redirect("/courses")->with("<span style='font-weight:bold;font-size:13px;'> $name could not be added </span>!");
            }
        } catch (\Exception $e) {
            \DB::rollback();
        }
        }
        else{
            throw new HttpException(Response::HTTP_UNAUTHORIZED, 'This action is unauthorized.');
        }
    }
    public function mountCourseStore(Request $request, SystemController $sys) {
      if(@\Auth::user()->role=='HOD' || @\Auth::user()->role=='Support' || @\Auth::user()->role=='Registrar'){
        \DB::beginTransaction();
        try {
            $this->validate($request, [
                'course' => 'required',
                'program' => 'required',
                'lecturer' => 'required',
                'level' => 'required',
                'credit' => 'required',
                'semester' => 'required',
                
                'year' => 'required'
            ]);


            $course = $request->input('course');
            $program = $request->input('program');
            $level = $request->input('level');
            $semester = $request->input('semester');
            $credit = $request->input('credit');
            $year = $request->input('year');
            $lecturer = $request->input('lecturer');
            $type = $request->input('type');
            if($request->input('type')==""){
               $type="Core"; 
            }
            else{
                 $type = $request->input('type');
            }
            $hod = $request->user()->id;
            $mountedCourse = new Models\MountedCourseModel();
            $mountedCourse->COURSE = $course;
            $mountedCourse->COURSE_CREDIT = $credit;
            $mountedCourse->COURSE_term = $semester;
            $mountedCourse->COURSE_LEVEL = $level;
            $mountedCourse->COURSE_TYPE = $type;
            $mountedCourse->PROGRAMME = $program;
            $mountedCourse->LECTURER = $lecturer;
            $mountedCourse->COURSE_year = $year;
            $mountedCourse->MOUNTED_BY = $hod;



            if ($mountedCourse->save()) {
                \DB::commit();
                $CourseArray=$sys->getCourseCodeByIDArray($course);
                $courseName=$CourseArray[0]->COURSE_NAME;
                $courseCode=$CourseArray[0]->COURSE_CODE;
                $staffArray=$sys->getLecturer($lecturer);
                $lecturerName=$staffArray[0]->name;
                $lecturePhone=$staffArray[0]->phone;
                $lectureStaffID=$staffArray[0]->staffID;
                $programCode=$sys->getProgram($program);
                $message="Hi, $lecturerName, you have been assigned $courseName, $courseCode, $programCode, year $level, $year, sem $semester";
                //dd($message);
               // $sys->firesms($message, $lecturePhone,$lectureStaffID );
                return redirect("/mounted_view")->with("success", "well done:<span style='font-weight:bold;font-size:13px;'> course mounted</span>successfully  ");
            } else {

                return redirect("/mounted_view")->withErrors("Whoops N<u>o</u> :<span style='font-weight:bold;font-size:13px;'> course could not be mounted </span>could not be added!");
            }
        } catch (\Exception $e) {
            \DB::rollback();
        }
      }
        else{
           throw new HttpException(Response::HTTP_UNAUTHORIZED, 'This action is unauthorized.');
        }
    }
    public function enterMark($course,$code, SystemController $sys ,Models\AcademicRecordsModel $record ){
         //$this->authorize('update',$record); // in Controllers
        if(@\Auth::user()->role=='HOD' ||@\Auth::user()->role=='Lecturer' || @\Auth::user()->department=='top'  ){
        $array=$sys->getSemYear();
        $sem=$array[0]->term;
        $year=$array[0]->year;

        $lecturer=@\Auth::user()->fund;
         
        $resultOpen=$array[0]->enterResult;
        if($resultOpen==1){
        $mark = Models\AcademicRecordsModel::where('courseCode',$code)
                
                ->where('staff',$lecturer)
                ->where('year',$year)
                ->where('term',$sem)
                
                ->paginate(500);
        $total=count($mark);
        $courseName=$sys->getCourseByID($code);
           
        return view('courses.markSheet')->with('mark', $mark)
            ->with('year', $year)
            ->with('sem', $sem)
            ->with('mycode', $code)
            ->with('course', $courseName)
            ->with('total', $total);
        }
        else{
              abort(434, "{!!<b>Entering of marks has ended contact the Dean of your School</b>!!}");
              redirect("/class/l sist");
              
        }
        }
        else{
              return redirect("/dashboard");
              
        }
    }
    public function marksDownloadExcel( $code, SystemController $sys )

	{
        $array=$sys->getSemYear();
        $sem=$array[0]->term;
        $year=$array[0]->year;

        $lecturer=@\Auth::user()->fund;

          $data=Models\AcademicRecordsModel::
                join('student', 'assesmentsheet.indexNo', '=', 'student.indexNo')
                ->where('assesmentsheet.courseCode',$code)
                ->where('assesmentsheet.staff',$lecturer)
                ->where('assesmentsheet.year',$year)
                ->where('assesmentsheet.year',$sem)
                ->select('student.indexNo','student.name','assesmentsheet.cw1','assesmentsheet.cw2','assesmentsheet.cw3','assesmentsheet.hw1','assesmentsheet.hw2','assesmentsheet.ctest1','assesmentsheet.ctest2','assesmentsheet.project1','assesmentsheet.project2','assesmentsheet.exam')->get();
          	 
		return Excel::create('continuous_assessment', function($excel) use ($data) {

			$excel->sheet('mySheet', function($sheet) use ($data)

	        {

				$sheet->fromArray($data);

	        });

		})->download('xlsx');


	}
    // public function printAttendance($course,$code, SystemController $sys){
    //    if(@\Auth::user()->role=='HOD' ||@\Auth::user()->role=='Lecturer' || @\Auth::user()->department=='top'  ){
     
    //     $array=$sys->getSemYear();
    //     $sem=$array[0]->term;
    //     $year=$array[0]->year;
         
    //     $mark = Models\AcademicRecordsModel::where("course", $course)->where('year',$year)->where('sem',$sem)->paginate(100);
    //     $total=count($mark);
    //     $courseName=$sys->getCourseByID($code);
           
    //     return view('courses.attendanceSheet')->with('mark', $mark)
    //         ->with('year', $year)
    //         ->with('sem', $sem)
    //         ->with('course', $courseName)
    //         ->with('total', $total);
    //     }
         
       
    //    else{
    //         throw new HttpException(Response::HTTP_UNAUTHORIZED, 'This action is unauthorized.');
      
    //    }
    // }
     public function storeMark($course,$code, SystemController $sys,Request $request){
         //dd($request);
        //$this->authorize('update',$record);
        /* if (Gate::denies('update', $record)) {
          abort(403);
          } */
        if (@\Auth::user()->role == 'HOD' || @\Auth::user()->role == 'Lecturer' || @\Auth::user()->department == 'top') {


            $array = $sys->getSemYear();
            $sem = $request->input('term');
            $year = $request->input('year');
            $resultOpen = $array[0]->enterResult;
            $course = $request->input('course');
                      $courseCode = $request->input('courseCode');
                    $courseArr= $sys->getCourseMountedInfo($course);
                          //  dd($course);
                    $lecturer= $courseArr[0]->teacherId;
            if ($resultOpen == 1) {
               
                    $host = $_SERVER['HTTP_HOST'];
                    $ipAddr = $_SERVER['REMOTE_ADDR'];
                    $userAgent = $_SERVER['HTTP_USER_AGENT'];
                   
                    $upper = $request->input('upper');
                    $key = $request->input('key');
                    $student = $request->input('student');
                     $class = $request->input('class');
                    $cw1 = $request->input('cw1');
                    $cw2 = $request->input('cw2');
                    $cw3 = $request->input('cw3');
                    
                    $hw1 = $request->input('hw1');
                    
                    $hw2 = $request->input('hw2');
                    
                    $ctest1 = $request->input('ctest1');
                    $ctest2 = $request->input('ctest2');
                    
                    $project1 = $request->input('project1');
                    $project2 = $request->input('project2');
                    
                    $exam = $request->input('exam');
                    
                     
                    
                    $cw1Old = $request->input('cw1Old');
                    $cw2Old = $request->input('cw2Old');
                    $cw3Old = $request->input('cw3Old');
                    $hw1Old = $request->input('hw1Old');
                    $hw2Old = $request->input('hw2Old');
                    $ctest1Old = $request->input('ctest1Old');
                    $ctest2Old = $request->input('ctest2Old');
                    $project1Old = $request->input('project1Old');
                    $project2Old = $request->input('project2Old');
                    
                    $examOld = $request->input('examOld');
                    
                    for ($i = 0; $i < $upper; $i++) {
                        $keyData = $key[$i];
                        $studentData = $student[$i];
                        $cw1Data = $cw1[$i];
                        $cw2Data = $cw2[$i];
                        $cw3Data = $cw3[$i];
                        
                         $hw1Data = $hw1[$i];
                         $hw2Data = $hw2[$i];
                        
                         $ctest1Data = $ctest1[$i];
                         $ctest2Data = $ctest2[$i];
                         
                         $project1Data = $project1[$i];
                         $project2Data = $project2[$i];
                          
                        $examData = $exam[$i];
                        // for logging
                        $cw1OldData = $cw1Old[$i];
                        $cw2OldData = $cw2Old[$i];
                        $cw3OldData = $cw3Old[$i];
                        
                        $hw1OldData = $hw1Old[$i];
                        $hw2OldData = $hw2Old[$i];
                        
                        $ctest1OldData = $ctest1Old[$i];
                         $ctest2OldData = $ctest2Old[$i];
                         
                          $project1OldData = $project1Old[$i];
                             $project2OldData = $project2Old[$i];
                        $examOldData = $examOld[$i];
                        $fortyPercent = $cw1Data + $cw2Data + $cw3Data + $hw1Data+ $hw2Data+ $ctest1Data+ $ctest2Data+ $project1Data+ $project2Data;
                     
                        $examTotal = $examData;
                        
                        $total = $fortyPercent + $examTotal;
  
                        $OldfortyPercent = $cw1OldData + $cw2OldData + $cw3OldData + $hw1OldData+ $hw2OldData+ $ctest1OldData+ $ctest2OldData+ $project1OldData+ $project2OldData;
                        $oldExam = $examOldData;
                        $oldClassScore = $OldfortyPercent;

                        $examLog = new Models\GradeLogModel();
                        $examLog->actor = $lecturer;
                        $examLog->student = $studentData;
                        $examLog->course = $courseCode;
                        $examLog->oldClassScore = $oldClassScore;
                        $examLog->newClassScore = $fortyPercent;
                        $examLog->oldExamScore = $oldExam;
                        $examLog->newExamScore = $examTotal;
                           $examLog->term = $sem;
                            $examLog->year= $year;
                        $examLog->ip = $ipAddr;
                        $examLog->host = $host;
                        $examLog->userAgent = $userAgent;
                        if ($examLog->save()) {
                            
                            
                            $programme=$sys->getCourseProgrammeMounted($course);
                            
                            $program=$sys->getProgramArray($programme);
                              $gradeSys=$sys->getProgramByGradeSystem($programme);
                              
                            $gradeArray = $sys->getGrade($total, $gradeSys);
                           
                              $grade = $gradeArray[0]->grade;
                              $gradePoint=@$gradeArray[0]->value;
                               $comment=@$gradeArray[0]->comment;
                                
                            Models\AcademicRecordsModel::where("id", $keyData)->where('staff', $lecturer)->update(array("cw1" => $cw1Data, "cw2" => $cw2Data, "cw3" => $cw3Data, "hw1" => $hw1Data,"hw2" => $hw2Data, "ctest1" => $ctest1Data,"ctest2" => $ctest2Data,"project1" => $project1Data,"project2" => $project2Data,"exam" => $examTotal, "total" => $total, 'grade' => $grade, 'gpoint' => $gradePoint,'comments'=>$comment));
                            //Prepare("update tbl_class_members set total='$row[total]' where STUDENT='$indexno_' and  year='$school->YEAR' and term='$school->TERM'")  ;
                            
                            $totalScore= Models\AcademicRecordsModel::where("indexNo",$studentData)->where("year",$year)->where("term",$sem)->sum("total");
                             
                            
                            $totalMember=Models\ClassMembersModel::where("student",$studentData)->where("year",$year)->where("term",$sem)->count();
                            
                            if(empty($totalMember)){
                                 
                                $member=new Models\ClassMembersModel();
                                $member->student=$studentData;
                                $member->class=$class;
                                $member->total=$totalScore;
                                $member->term=$sem;
                                $member->year=$year;
                                $member->save();
                                    
                            }
                            else{
                               Models\ClassMembersModel::where("student",$studentData)->where("year",$year)->where("term",$sem)->update(array("total"=>$totalScore)); 
                            }
                            
                  ////////////////////////////////////////////////////////////
                    // Starting position in subject
                   ////////////////////////////////////////////////////////////
                            $rankQuery= Models\AcademicRecordsModel::where("courseCode",$courseCode)->where("class",$class)
                                                ->where("term",$sem)
                                                ->where("year",$year)->orderBy("total","Desc")->get();
                            
                            
                            
                              
                     $inde=0;
                     
                     $row=count($rankQuery);
                     $oldtotal=-1;
                     $repeat=0;
                    foreach($rankQuery as $query){
                
                    $inde++;
                    $currentotal=$query->total;
                     
                    if($oldtotal==$currentotal){}else{$in=$inde; }
                     $oldtotal=$currentotal;
                     $position=$in."/".$row;
                    
                      
                      
                      Models\AcademicRecordsModel::where("id",$keyData)->update(array("posInSubject"=>$position));
                     
                     }
                            
                            
                            ////////////////////////////////////////////////////////////////////////
                     // starting overall position in class ie class average
                     /////////////////////////////////////////////////////////////////////////
                     $querySubjectPosition= Models\ClassMembersModel::where("class",$class)
                                                        ->where("year",$year)
                                                        ->where("term",$sem)
                                                        ->orderBy("total","Desc")
                                                        ->get();
                       
                     
                        $inde=0;
 
                        $row2=count($querySubjectPosition);
                        foreach($querySubjectPosition as $input){
                       

                        $inde++;
                        $currentotal=$input->total;
                        if($oldtotal==$currentotal){}else{$in=$inde; }
                         $oldtotal=$currentotal;

                          $subjectPosition=$in."/".$row2;
                         //echo "_";
                           //print_r($in_);
                          
                          Models\ClassMembersModel::where("student",$studentData)
                                        ->where("year",$year)
                                        ->where("term",$sem)
                                        ->update(array("position"=>$subjectPosition));

                      }
                            
                            
                            
                            
                            
                            
                            \DB::commit();
                            
                        }
                        
                    }
               
                $mark = Models\AcademicRecordsModel::where("courseCode", $courseCode)->where('staff', $lecturer)->where('year', $year)->where('term', $sem)->paginate(100);
               //dd($mark);
                $total = count($mark);
                $courseName = $sys->getCourse($courseArr[0]->subject);

                  
                return view('courses.markSheet')->with('mark', $mark)
                                ->with('year', $year)
                                ->with('sem', $sem)
                                 ->with('mycode', $code)
                                ->with('course', $courseName)
                                ->with('total', $total);
            } else {
                throw new HttpException(Response::HTTP_UNAUTHORIZED, 'This action is unauthorized.');
      
                }
        } else {
            throw new HttpException(Response::HTTP_UNAUTHORIZED, 'This action is unauthorized.');
      
        }
        
    }
     public function attendanceSheet(Request $request,SystemController $sys){
    if(@\Auth::user()->role=='HOD' || @\Auth::user()->department=='top'|| @\Auth::user()->role=='Dean' || @\Auth::user()->role=='Lecturer'){
           if ($request->isMethod("get")) {
             $course=$sys->getMountedCourseList();

             return view('courses.attendanceSheet')
             ->with('courses',$course)->with('year',$sys->years());
            }
           else{

                $semester = $request->input('semester');
                $year = $request->input('year');
                $course =  $request->input('course') ;
                $level = $request->input('level');
             
                $mark = Models\AcademicRecordsModel::where("course", $course)->where('year',$year)->where('sem',$semester)->where('level',$level)->paginate(100);
            
                  $courseArr= $sys->getCourseMountedInfo($course);
                           // dd($courseArr);
                            $courseDb= $courseArr[0]->ID;
                            $courseCreditDb= $courseArr[0]->COURSE_CREDIT;
                            $courseLecturerDb= $courseArr[0]->LECTURER;
                            $courseName=$sys->getCourseCodeByIDArray($courseArr[0]->COURSE);
                            $displayCourse=$courseName[0]->COURSE_NAME;
                            $displayCode=$courseName[0]->COURSE_CODE;
                            \Session::put('year', $year);
                            $url = url('printAttendance/'.$semester.'/sem/'.$displayCourse.'/course/'.$displayCode.'/code/'.$level.'/level/'.$course.'/id');
                  
                          $print_window = "<script >window.open('$url','','location=1,status=1,menubar=yes,scrollbars=yes,resizable=yes,width=1000,height=500')</script>";
                $request->session()->flash("success",
                "    $print_window");
                 return redirect("/attendanceSheet");
                 
            // return view('courses.printAttendance')->with('mark', $mark)
            //     ->with('year', $year)
            //     ->with('sem', $semester)
            //     ->with('course', $displayCourse)
            //     ->with('code', $displayCode);

                
           }
       }
       else{
            throw new HttpException(Response::HTTP_UNAUTHORIZED, 'This action is unauthorized.');
        }
    }
    public function printAttendance(Request $request,$semester,$course,$code,$level,$id) {
      $year=\Session::get('year');
  $mark = Models\AcademicRecordsModel::where("course", $id)->where('year',$year)->where('sem',$semester)->where('level',$level)->paginate(100);
            
      return view('courses.printAttendance')->with('mark', $mark)
                ->with('year', $year)
                ->with('sem', $semester)
                ->with('course', $course)
                ->with('code', $code);
          
        
    }
    public function showFileUpload(SystemController $sys){
       if(@\Auth::user()->role=='HOD' || @\Auth::user()->department=='top'|| @\Auth::user()->role=='Dean' || @\Auth::user()->role=='Lecturer'){
        $programme=$sys->getProgramList();
         $course=$sys->getMountedCourseList();

         return view('courses.markUpload')->with('programme', $programme)
         ->with('courses',$course)->with('year',$sys->years());
       }
       else{
            throw new HttpException(Response::HTTP_UNAUTHORIZED, 'This action is unauthorized.');
        }
    }
    /*
     * Uploading old academic records here
     * file format Excel
     */
     public function uploadMarks(Request $request, SystemController $sys){

      $this->validate($request, [
            
            'file' => 'required',
            'course' => 'required',
            'sem' => 'required',
            'year' => 'required',
            'level' => 'required',
        ]);
         if(@\Auth::user()->role=='HOD' || @\Auth::user()->department=='top'|| @\Auth::user()->role=='Dean' || @\Auth::user()->role=='Lecturer'){
            $array = $sys->getSemYear();
            $sem = $array[0]->term;
            $year = $array[0]->year;
            $resultOpen = $array[0]->enterResult;
            if ($resultOpen == 1) {
           

            $valid_exts = array('csv', 'xls', 'xlsx'); // valid extensions
            $file = $request->file('file');
            $path = $request->file('file')->getRealPath();

            $ext = strtolower($file->getClientOriginalExtension());
            
            $semester = $request->input('sem');
            $year1 = $request->input('year');
            $course =  $request->input('course') ;
            //$programme = $request->input('program');
            $level = $request->input('level');
            $studentIndexNo = $sys->getStudentIDfromIndexno($request->input('student'));
                   
             
           if (in_array($ext, $valid_exts)) {
               
                    $data = Excel::load($path, function($reader) {
                            
                        })->get();
                if (!empty($data) && $data->count()) {  
                

                       foreach ($data as $key => $value) {

                            $totalRecords = count($data);

                            
                          
                        //$studentDb= $sys->getStudentIDfromIndexno('0'.$value->index_no);
                        //print_r($value);
                            
                         $studentDb= $value->indexno  ;   
                          // dd($studentDb);
                            $courseArr= $sys->getCourseMountedInfo($course);
                           // dd($courseArr);
                            $courseDb= $courseArr[0]->ID;
                            $courseCreditDb= $courseArr[0]->COURSE_CREDIT;
                            $courseLecturerDb= $courseArr[0]->LECTURER;
                            $courseName=$sys->getCourseCodeByIDArray($courseArr[0]->COURSE);
                            $displayCourse=$courseName[0]->COURSE_NAME;
                            $displayCode=$courseName[0]->COURSE_CODE;
            $studentSearch = $sys->studentSearchByCode($year,$semester,$courseDb,$studentDb); // check if the students in the file tally with registered students
                       //dd($studentDb);
                        if (@in_array($studentDb, $studentSearch)) {
                            $indexNo=$value->index_no;
                            $quiz1=$value->quiz1;
                            $quiz2=$value->quiz2;
                            $midsem=$value->midsem1;
                            $exam=$value->exam;
                            $total= round(($quiz2+$quiz1+$midsem+$exam),2);
                            $programmeDetail=$sys->getCourseProgrammeMounted($courseDb);
                            
                            $program=$sys->getProgramArray($programmeDetail);
                            $gradeArray = @$sys->getGrade($total, $program[0]->GRADING_SYSTEM);
                            $grade = @$gradeArray[0]->grade;

                           // dd($gradeArray );
                            $gradePoint =round(( @$gradeArray[0]->value * @$courseArr[0]->COURSE_CREDIT),2);
                            $cgpa= number_format(@(( $gradePoint)/$credit), 2, '.', ',');
                                $oldCgpa= @Models\StudentModel::where("INDEXNO",$studentDb)->select("CGPA","CLASS")->first();
                                $newCgpa=@$cgpa+$oldCgpa->CGPA;
                                 $class=@$sys->getClass($newCgpa);
                                Models\StudentModel::where("INDEXNO",$studentDb)->update(array("CGPA"=>$newCgpa,"CLASS"=>$class));
                           
                            Models\AcademicRecordsModel::where("indexno", $studentDb)->where("course", $courseDb)->where("sem",$semester)->where("year",$year1)->update(array("quiz1" => $quiz1, "quiz2" => $quiz2, "quiz3" =>0, "midSem1" => $midsem, "exam" => $exam, "total" => $total, "lecturer" =>$courseLecturerDb,'grade' => $grade, 'gpoint' => $gradePoint));

                             \DB::commit();
                              
                                   
                                
                       } else {
                                return redirect('/upload/marks')->with("error", " <span style='font-weight:bold;font-size:13px;'>File contain unrecognized students for $displayCourse - $displayCode.please upload only registered students for  $displayCourse - $displayCode  as downloaded from the system!</span> ");
                            
                                  
                            } 
                        }
                          
                         
                        return redirect('/registered_courses')->with("success",  " <span style='font-weight:bold;font-size:13px;'> $totalRecords Marks  successfully uploaded for  $displayCourse - $displayCode!</span> ");
                             
                    
                } else {
                     return redirect('/upload/marks')->with("error", " <span style='font-weight:bold;font-size:13px;'>File is empty</span> ");
                                   
                }
            } else {
                 return redirect('/upload/marks')->with("error", " <span style='font-weight:bold;font-size:13px;'>Please upload only Excel file!</span> ");
                    
            }


              

        }
       else{
            throw new HttpException(Response::HTTP_UNAUTHORIZED, 'This action is unauthorized.');
        }
      }
        else{
               
              redirect("/dashboard")->with('error','Entering of marks has ended contact the Dean of your School');
              
        }
    }
    // show form for edit resource
    public function edit(Request $request,$id,SystemController $sys){
        if (@\Auth::user()->department == 'top' || @\Auth::user()->role == 'HOD' || @\Auth::user()->role == 'Support') {
           

                $course = Models\CourseModel::where("id", $id)->firstOrFail();
                $program = $sys->getProgramList2();
                return view('courses.edit')->with("key",$id)
                                ->with("program", $program)
                                ->with('data', $course)
                                  ;
        }
        else{
            // throw new HttpException(Response::HTTP_UNAUTHORIZED, 'This action is unauthorized.');

            return redirect("/dashboard");
        }
    }

    public function update(Request $request){
        
                $this->validate($request, [


                    'program' => 'required',
                    'name' => 'required',
                    'code' => 'required',
                    
                ]);
                  $name=$request->input("name");
                    $code=$request->input("code");
                    $program=$request->input("program");
                     $id=$request->input("id");
                   // dd($program);
                \DB::beginTransaction();
                try {
                   
                    $query = @Models\CourseModel::where("id", $id)->update(array("name" => $name, "code" => $code, "pcode" => $program));
                    \DB::commit();
                     
                        if( $query){
                        return response()->json(['status'=>'success','message'=>$name.' edited successfully ']);
      
                       }
                       else{
                           return response()->json(['status'=>'error','message'=>$name.' editing failed. try again ']);
       
                       }
                    
                } catch (\Exception $e) {
                    \DB::rollback();
                }
           
        
        
    }
    /**
     * Destroy the given task.
     *
     * @param  Request  $request
     * @param  Task  $task
     * @return Response
     */
    public function destroy(Request $request,   SystemController $sys)
    {
        //dd($request->input("id"));
       if(@\Auth::user()->role=='Admin' ||  @\Auth::user()->department=='top'){
           $hod=@\Auth::user()->fund;
            $array=$sys->getSemYear();
            $sem=$array[0]->term;
            $year=$array[0]->year;

        
            $query1= Models\CourseModel::where('id',$request->input("id"))->where("createdBy",$hod)->delete();
             
          
            
             if($query1){
                
                  \DB::commit();
               return redirect("/courses")->with("success","<span style='font-weight:bold;font-size:13px;'> Course  successfully deleted!</span> ");
         
             }
             else{
                 
                  
               return redirect("/courses")->with("error","<span style='font-weight:bold;font-size:13px;'>Whoops!! you are not the owner of this course</span> ");
             }
            
          } 
//          else{
//                return redirect("/courses")->with("error","<span style='font-weight:bold;font-size:13px;'>Whoops!! you cannot delete a mounted course</span> ");
//           
//          }
           
        
       else {
           // abort(434, "{!!<b>Unauthorize Access detected</b>!!}");
            redirect("/dashboard");
        }
         
    }
     public function destroyAllocatedCourse(Request $request,   SystemController $sys)
    {
        //dd($request->input("id"));
       if(@\Auth::user()->role=='Admin' ||  @\Auth::user()->department=='top'){
           $hod=@\Auth::user()->fund;
            
        
            $query1= Models\CourseAllocationModel::where('id',$request->input("id"))->where("createdBy",$hod)->delete();
             
          
            
             if($query1){
                
                  \DB::commit();
               return redirect("teachers/subject/allocation")->with("success","<span style='font-weight:bold;font-size:13px;'> Course allocation successfully deleted!</span> ");
         
             }
             else{
                 
                  
               return redirect("teachers/subject/allocation")->with("error","<span style='font-weight:bold;font-size:13px;'>Whoops!! you are not the allocator of this course sorry</span> ");
             }
            
          } 
//          else{
//                return redirect("/courses")->with("error","<span style='font-weight:bold;font-size:13px;'>Whoops!! you cannot delete a mounted course</span> ");
//           
//          }
           
        
       else {
           // abort(434, "{!!<b>Unauthorize Access detected</b>!!}");
            redirect("/dashboard");
        }
         
    }
       public function courseDownloadExcel($type)

	{

         
		$data = Models\CourseModel::select('name','code','pcode')->take(5)->get()->toArray();

		return Excel::create('courses_example', function($excel) use ($data) {

			$excel->sheet('mySheet', function($sheet) use ($data)

	        {

				$sheet->fromArray($data);

	        });

		})->download($type);

	}
        
   // naptex broadsheet view
         public function naptexBroadsheet(Request $request, SystemController $sys){

//      $this->validate($request, [
//            
//            'file' => 'required',
//            'course' => 'required',
//            'sem' => 'required',
//            'year' => 'required',
//            'level' => 'required',
//        ]);
         if (@\Auth::user()->role == 'HOD' || @\Auth::user()->department == 'top' || @\Auth::user()->role == 'Dean' || @\Auth::user()->role == 'Lecturer') {
             if ($request->isMethod("get")) {

            return view('courses.broadsheet_naptex');
                             
            } 
            else{
                
            }
        }
    }
    
    // noticeboard broadsheet
     
         public function noticeBoardBroadsheet(Request $request, SystemController $sys){

//      $this->validate($request, [
//            
//            'file' => 'required',
//            'course' => 'required',
//            'sem' => 'required',
//            'year' => 'required',
//            'level' => 'required',
//        ]);
         if (@\Auth::user()->role == 'HOD' || @\Auth::user()->department == 'top' || @\Auth::user()->role == 'Dean' || @\Auth::user()->role == 'Lecturer') {
             if ($request->isMethod("get")) {

            return view('courses.noticeboard');
                             
            } 
            else{
                
            }
        }
    }

}

