<?php

namespace App\Http\Controllers;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Models\ProgrammeModel;
use App\Models; 
use Yajra\Datatables\Datatables;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
 use Symfony\Component\HttpKernel\Exception\HttpException;

class ProgrammeController extends Controller
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
 
    /**
     * Display a list of all of the user's task.
     *
     * @param  Request  $request
     * @return Response
     */
    public function getIndex(Request $request)
    {
        
        return view('programme.index');
    }
    public function anyData(Request $request)
    {
         
        $program = ProgrammeModel::select([  'id','deptCode', 'code','name','gradeSystem','duration']);


        return Datatables::of($program)
              
             ->addColumn('action', function ($programme_) {
                 return "<a href=\"edit_programme/$programme_->id/id\" class=\"md-btn md-btn-primary md-btn-small md-btn-wave-light waves-effect waves-button waves-light\"><i title='click to edit' class=\"sidebar-menu-icon material-icons md-18\">edit</a>";
            
                //return' <td> <a href=" "><img class="" style="width:70px;height: auto" src="public/Albums/students/'.$student->INDEXNO.'.JPG" alt=" Picture of Employee Here"    /></a>df</td>';
                          
                                         
            })
            ->setRowId('id')
            ->setRowClass(function ($programme_) {
                return $programme_->id % 2 == 0 ? 'uk-text-success' : 'uk-text-warning';
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

     
    public function create(SystemController $sys) {
       if(@\Auth::user()->role=='Dean' || @\Auth::user()->department=='top'){
        $department=$sys->department();
         return view('programme.create')->with('department', $department)
                 ->with('grade', $sys->getGradeSystemIDList());
         }
        else{
          return   view("unauthorized");
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
         
      if(@\Auth::user()->role=='Admin' || @\Auth::user()->department=='top'){
        $this->validate($request, [
            'name' => 'required',
            'department'=>'required',
            'code'=>'required',
            'duration'=>'required',
           
            'grade'=>'required',
        ]);
        //$this->validate($request, [
        //        'title' => 'required|unique:posts|max:255',
        //        'body' => 'required',
        //    ]);
      
      $total=count($request->input('code'));
      $name=$request->input('name');
      $department=$request->input('department');
      $duration=$request->input('duration');
      
      $code=$request->input('code');
      $grade=$request->input('grade');
       
      for($i=0;$i<$total;$i++){
         $program=new ProgrammeModel();
         $program->code=$department[$i];
         $program->deptCode=$code[$i];
         $program->name=$name[$i];
         $program->duration=$duration[$i];
       
         $program->gradeSystem=$grade[$i];
           
         $program->save();
          
      }
       if(!$program){
      
          return redirect("/programmes")->withErrors("Following programmes N<u>o</u> :<span style='font-weight:bold;font-size:13px;'>programme could not be added </span>could not be added!");
          }else{
           return redirect("/programmes")->with("success","Following programme:<span style='font-weight:bold;font-size:13px;'> programme added </span>successfully added! ");
              
              
      }}
        else{
            return redirect("/dashboard");
        }
          
       
    }
    // show form for edit resource
    public function edit($id, SystemController $sys){
        if(@\Auth::user()->role=='Admin' ||  @\Auth::user()->department=='top'){
        $programme= ProgrammeModel::where("ID", $id)->firstOrFail();
        $department=$sys->department();
         return view('programme.edit')->with('department', $department)
                 ->with('grade', $sys->getGradeSystemIDList())
                 ->with('data', $programme);
         }
        else{
         return view("unauthorized");
            }
    }

    public function update(Request $request, $id){
      if(@\Auth::user()->role=='Admin'  ||  @\Auth::user()->department=='top'){
        \DB::beginTransaction();
        try {
        $name = $request->input('name');
        $department = $request->input('department');
        $duration = $request->input('duration');
       
        $code = $request->input('code');
        $grade = $request->input('grade');
        $query = ProgrammeModel::where("id", $id)->update(array("name" => $name, "code" => $code, "duration" => $duration, "gradeSystem" => $grade, "deptCode" => $department));
          \DB::commit();
        if (!$query) {

            return redirect("/programmes")->withErrors("<u>o</u> :<span style='font-weight:bold;font-size:13px;'> $name could not be updated!</span>");
        } else {
            return redirect("/programmes")->with("success", "<span style='font-weight:bold;font-size:13px;'> $name successfully updated!</span> ");
        }
         } catch (\Exception $e) {
            \DB::rollback();
        }
        }
        else{
           
            
             return view("unauthorized");
        }
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
