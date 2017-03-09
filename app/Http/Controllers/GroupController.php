<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models;
use Validator;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
 
class GroupController extends Controller
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
     
    /**
     * Display a list of all of the user's task.
     *
     * @param  Request  $request
     * @return Response
     */
    public function getIndex(Request $request, SystemController $sys)
    {
        
        return view('students.passwords')->with("programme",$sys->getProgramByIDList());
    }
    public function anyData(Request $request)
    {
         if( @\Auth::user()->department=='top' || @\Auth::user()->role=="Dean"|| @\Auth::user()->role=="HOD" || @\Auth::user()->role=="Admin"){
        
       
        $students = Models\StudentModel::join('tpoly_programme', 'tpoly_students.PROGRAMMECODE', '=', 'tpoly_programme.PROGRAMMECODE')->join('tpoly_log_portal', 'tpoly_log_portal.username', '=', 'tpoly_students.INDEXNO')
           ->select(['tpoly_students.ID', 'tpoly_students.NAME','tpoly_students.INDEXNO', 'tpoly_programme.PROGRAMME','tpoly_students.LEVEL','tpoly_students.INDEXNO','tpoly_students.STATUS','tpoly_log_portal.real_password']);
         
         }
         else{
              $students = Models\StudentModel::join('tpoly_log_portal', 'tpoly_log_portal.username', '=', 'tpoly_students.INDEXNO')
           ->select(['tpoly_students.ID', 'tpoly_students.NAME', 'tpoly_students.PROGRAMMECODE','tpoly_students.LEVEL','tpoly_students.INDEXNO','tpoly_students.STATUS','tpoly_log_portal.real_password']) 
            ->whereHas('programme', function($q) {
            $q->whereHas('departments', function($q) {
                $q->whereIn('DEPTCODE', array(@\Auth::user()->department));
            });
        });          
                      
         }

        return Datatables::of($students)
                         
             
               ->editColumn('id', '{!! $ID!!}')
            ->addColumn('Photo', function ($student) {
               // return '<a href="#edit-'.$student->ID.'" class="md-btn md-btn-primary md-btn-small md-btn-wave-light waves-effect waves-button waves-light">View</a>';
            
                return' <a href="#"><img class="md-user-image-large" style="width:60px;height: auto" src="public/albums/students/'.$student->INDEXNO.'.jpg" alt=" Picture of Student Here"    /></a>';
                          
                                         
            })
              
            
            ->setRowId('id')
             
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

     public function createGroup(Request $request, SystemController $sys)
    { 
          if (@\Auth::user()->role == 'HOD' || @\Auth::user()->role == 'Registrar' || @\Auth::user()->department == 'top') {

           if ($request->isMethod("get")) {
               
                $user_department= @\Auth::user()->department;
              $data= \DB::table('tpoly_programme')->where('DEPTCODE',$user_department)->orderby("PROGRAMME")
                 ->paginate(100);
              
               $programs=$sys->getProgramList();
               $lecturers=$sys->getLectureList_All();
            return view('groups.create')->with("program",$programs)->with("data",$data)
                    ->with("lecturers",$lecturers);
                             
            } 
            else{
                
                  $this->validate($request, [
            'level' => 'required',
            'name'=>'required',
            'total'=>'required',
            'program'=>'required',
             
        ]);
        
//      $pieces =$request->input('total');
//      $group = explode("-", $pieces);
//      $limit=$group[1];
    
       
      $total=$request->input('total');
      $level=$request->input('level');
     $name=$request->input('name');
      $program=$request->input('program');
       $querySize=  Models\StudentModel::where("PROGRAMMECODE",$program)
                  ->where("LEVEL",$level)->where("STATUS",'In SCHOOL')->where("CLASS_GROUPS","0")->orderBy("INDEXNO")->count("*");
       $classSize=  Models\StudentModel::where("PROGRAMMECODE",$program)
                  ->where("LEVEL",$level)->where("STATUS",'In SCHOOL')->where("CLASS_GROUPS","0")->orderBy("INDEXNO")->get()->toArray();
       dd(count($classSize));
       $perGroup=  array_slice($classSize, $total);
           dd(  $perGroup);
        foreach($perGroup as $groups=>$group){
//          $query=  Models\StudentModel::where("PROGRAMMECODE",$program)
//                  ->where("LEVEL",$level)->limit($total)->orderBy("INDEXNO")->get();
            Models\StudentModel::where("INDEXNO",$group["INDEXNO"])->update(
                    array("CLASS_GROUPS"=>$name)
                    );
        }
        
        
       }
     }
  else{
      return redirect("/dashboard");
  }
           
    }
    
    
    /**
     * Create a new task.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
         
       
    }
     public function showChange(Request $request)
    {
          return view('users.reset');
       
    }
     public function reset(Request $request)
    {
         $this->validate($request, [

            'oldPass' => 'required',
            'password' => 'required|min:7',
            'confirm' => 'required|min:7',
             
        ]);
        $checker= @\Auth::user()->password;
     
       $user= @\Auth::user()->id;
       $password=$request['password'];
       $oldPassword=$request['oldPass'];
       $confirm=$request['confirm'];
         //NB bycrpt always change per seconds so we can't do $password==$checker ..IMPOSSIBLE
      if(\Hash::check($oldPassword, $checker)){
       if($password==$confirm){
         $query=  \App\User::where('id',$user)->update(array('password'=>bcrypt($password)));
             
         if($query){
              return redirect("/logout")->with("success","<span style='font-weight:bold;font-size:13px;'>Password successfully changed.. </span> ");
         
         }
       }
       else{
           return redirect("/change_password")->with("error","<span style='font-weight:bold;font-size:13px;'>Password do not match . </span> ");
           
       }
      }
      else{
           return redirect("/change_password")->with("error","<span style='font-weight:bold;font-size:13px;'> This password does not exist . </span> ");
        
      }
       
    }
    // show form for edit resource
    public function edit($id){
        }

    public function update(Request $request, $id){
        
    }
    /**
     * Destroy the given task.
     *
     * @param  Request  $request
     * @param  Task  $task
     * @return Response
     */
    public function destroy(Request $request, Task $task)
    {
        
    }
}
