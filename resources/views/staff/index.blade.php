@extends('layouts.app')


@section('style')

@endsection
@section('content')

<div class="md-card-content">
<div style="text-align: center;display: none" class="uk-alert uk-alert-success" data-uk-alert="">

    </div>



    <div style="text-align: center;display: none" class="uk-alert uk-alert-danger" data-uk-alert="">

    </div>

    @if (count($errors) > 0)


    <div class="uk-alert uk-alert-danger  uk-alert-close" style="background-color: red;color: white" data-uk-alert="">

        <ul>
            @foreach ($errors->all() as $error)
            <li>{!!$error  !!} </li>
            @endforeach
        </ul>
    </div>

    @endif
@if(Session::has('success'))
            <div style="text-align: center" class="uk-alert uk-alert-success  uk-alert-close" data-uk-alert="">
                {!! Session::get('success') !!}
            </div>
 @endif

</div>
<div class="uk-modal" id="new_task">
    <div class="uk-modal-dialog">
        <div class="uk-modal-header">
            <h4 class="uk-modal-title">Send sms  here</h4>
        </div>
         
        <form  accept-charset="utf-8"    method="POST" id='form'>
            <input type="hidden" name="_token" value="{!! csrf_token() !!}"> 

            
            <textarea cols="30" rows="4" name="message"class="md-input" required=""></textarea>


            <div class="uk-modal-footer uk-text-right">
                <button type="button" class="md-btn md-btn-flat md-btn-flat-primary md-btn-wave send" id="snippet_new_save"><i   class="material-icons"   >smartphone</i>Send</button>    
                <button type="button" class="md-btn md-btn-flat uk-modal-close md-btn-wave">Close</button>
            </div>
        </form>
    </div>
</div>
<h3 class="heading_b uk-margin-bottom">Staff</h3>
<div style="" class="">
<!--    <div class="uk-margin-bottom" style="margin-left:910px" >-->
<div class="uk-margin-bottom" style="" >
        <a  href="#new_task" data-uk-modal="{ center:true }"> <i title="click to send sms to library users"   class="material-icons md-36 uk-text-success"   >phonelink_ring message</i></a>

        <a href="#" class="md-btn md-btn-small md-btn-success uk-margin-right" id="printTable">Print Table</a>
        <div class="uk-button-dropdown" data-uk-dropdown="{mode:'click'}">
            <button class="md-btn md-btn-small md-btn-success"> columns <i class="uk-icon-caret-down"></i></button>
            <div class="uk-dropdown">
                <ul class="uk-nav uk-nav-dropdown" id="columnSelector"></ul>
            </div>
        </div>





        <div style="margin-top: -5px" class="uk-button-dropdown" data-uk-dropdown="{mode:'click'}">
            <button class="md-btn md-btn-small md-btn-success uk-margin-small-top">Export <i class="uk-icon-caret-down"></i></button>
            <div class="uk-dropdown">
                <ul class="uk-nav uk-nav-dropdown">
                    <li><a href="#" onClick ="$('#ts_pager_filter').tableExport({type: 'csv', escape: 'false'});"><img src='{!! url("public/assets/icons/csv.png")!!}' width="24"/> CSV</a></li>

                    <li class="uk-nav-divider"></li>
                    <li><a href="#" onClick ="$('#ts_pager_filter').tableExport({type: 'excel', escape: 'false'});"><img src='{!! url("public/assets/icons/xls.png")!!}' width="24"/> XLS</a></li>
                    <li><a href="#" onClick ="$('#ts_pager_filter').tableExport({type: 'doc', escape: 'false'});"><img src='{!! url("public/assets/icons/word.png")!!}' width="24"/> Word</a></li>
                    <li><a href="#" onClick ="$('#ts_pager_filter').tableExport({type: 'powerpoint', escape: 'false'});"><img src='{!! url("public/assets/icons/ppt.png")!!}' width="24"/> PowerPoint</a></li>
                    <li class="uk-nav-divider"></li>

                </ul>
            </div>
        </div>




        <i title="click to print" onclick="javascript:printDiv('print')" class="material-icons md-36 uk-text-success"   >print</i>


         <a href="{{url('/workers')}}" ><i   title="reload this page" class="uk-icon-refresh uk-icon-medium "></i></a>
        

    </div>
</div>
<!-- filters here -->
 
@inject('sys', 'App\Http\Controllers\SystemController')
<div class="uk-width-xLarge-1-1">
    <div class="md-card">
        <div class="md-card-content">

            <form action=" "  method="get" accept-charset="utf-8" novalidate id="group">
                {!!  csrf_field()  !!}
                <div class="uk-grid" data-uk-grid-margin="">

                    <div class="uk-width-medium-1-5">
                        <div class="uk-margin-small-top">
                            <select placeholder='type courses' style="width: 210px"    name="library" required="required" class= 'md-input parent'v-model='course' v-form-ctrl='' v-select=''>
                                <option value="">--select library --</option>
                                <option value=""selected="">All library</option>
                                @foreach($library as $item)

                                <option value="{{$item->id}}">{{strtoupper($item->name)}} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                     <div class="uk-width-medium-1-5">
                        <div class="uk-margin-small-top">
                            
                            {!!  Form::select('gender', array(''=>'All gender','MALE'=>'Male','FEMALE' => 'Female'), null, ['placeholder' => 'select gender','id'=>'parent','class'=>'md-input parent'],old("level","")); !!}

                        </div>
                    </div>
                     
                     <div class="uk-width-medium-1-5">
                        <div class="uk-margin-small-top">
                            {!! Form::select('region', 
                            (['' => 'Search by Regions'] +$region  ), 
                            old("region",""),
                            ['class' => 'md-input parent','id'=>"parent"] )  !!}
                        </div>
                    </div>
                   
                    <div class="uk-width-medium-1-5">
                        <div class="uk-margin-small-top">
                            {!! Form::select('religion', 
                            (['' => 'Search by Religions'] +$religion  ), 
                            old("religion",""),
                            ['class' => 'md-input parent','id'=>"parent"] )  !!}
                        </div>
                    </div>

                     <div class="uk-width-medium-1-5">
                        <div class="uk-margin-small-top">
                                   {!!   Form::select('leave',array("On Duty"=>"At Post",'On Leave'=>"On Leave"),old('leave',''),array('placeholder'=>'Select leave status',"class"=>"md-input","v-model"=>"leave","v-form-ctrl"=>"","v-select"=>"leave"))  !!}
                                                   
                        </div>
                    </div>
                     
                      
                      
                    </div>
                  <div class="uk-grid" data-uk-grid-margin="">
                     
                <div class="uk-width-medium-1-5">
                        <div class="uk-margin-small-top">
                                {!!   Form::select('marital_status',array("MARRIED"=>"Married",'SINGLE'=>"Single"),old('marital_status',''),array('placeholder'=>'Select marital status',"required"=>"required","class"=>"md-input","v-model"=>"marital_status","v-form-ctrl"=>"","v-select"=>"marital_status"))  !!}
                                          
                        </div>
                    </div>
                      <div class="uk-width-medium-1-5">
                        <div class="uk-margin-small-top">
                            {!! Form::select('designation', 
                            (['' => 'Search by designation'] +$designation ), 
                            old("designation",""),
                            ['class' => 'md-input parent','id'=>"parent"] )  !!}
                        </div>
                    </div>
                      <div class="uk-width-medium-1-5">
                        <div class="uk-margin-small-top">
                            {!! Form::select('department', 
                            (['' => 'Search by departments'] +$department  ), 
                            old("department",""),
                            ['class' => 'md-input parent','id'=>"parent"] )  !!}
                        </div>
                    </div>
                    <div class="uk-width-medium-1-5">
                        <div class="uk-margin-small-top">

                            {!!  Form::select('by', array('emp_number'=>'Search by employee Number','name'=>'Name','grade'=>'Search by Grade','position'=>'search position', 'required'=>''), null, ['placeholder' => 'select search type','class'=>'md-input'], old("","")); !!}
                        </div>
                    </div>
                     
                    <div class="uk-width-medium-1-5">
                        <div class="uk-margin-small-top">                            
                            <input type="text" style=" " required=""  name="search"  class="md-input" placeholder="search by card number  or by name ">
                        </div>
                    </div>
                       <div class="uk-width-medium-1-5">
                        <div class="uk-margin-small-top"> 
                                <button class="md-btn  md-btn-small md-btn-success uk-margin-small-top" type="submit"><i class="material-icons">search</i></button> 
                      
                        </div>
                       </div>
                      
        </div>


                </div>
  
            </form> 
        </div>
    </div>
 

<!-- end filters -->
<div class="uk-width-xLarge-1-1">
    <div class="md-card">
        <div class="md-card-content">


            <div class="uk-overflow-container" id='print'>
                <center><span class="uk-text-success uk-text-bold">{!! $data->total()!!} Records</span></center>
                <table class="uk-table uk-table-hover uk-table-align-vertical uk-table-nowrap tablesorter tablesorter-altair" id="ts_pager_filter"> 
                    <thead>
                        <tr>
                            <th>No</th><th>StaffId</th><th>Photo</th><th>First Name</th><th>Last Name</th><th>Name</th><th>Date Of Birth</th><th>Gender</th><th>Maratial Status</th><th>Hometown</th><th>Place of Residence</th><th>Grade</th><th>Position</th><th>Dependents</th><th>Leave Status</th><th>SSNIT</th><th>Nationality</th><th>Date Hired</th><th>Phone</th><th>Email</th><th>Designations</th><th>Department</th><th>Actions</th>
                                         </tr>
                    </thead>
                    <tbody>

                        @foreach($data as $index=> $row) 




                        <tr align="">
                            <td> {{ $data->perPage()*($data->currentPage()-1)+($index+1) }} </td>
                            <td> {{ strtoupper(@$row->emp_number) }}</td>
                             <td>Photo</td>
                           <td> {{ strtoupper(@$row->firstname)}}</td>
                          <td> {{ strtoupper(@$row->surname)}}</td>
                          
                         <td> {{ strtoupper(@$row->name)}}</td>
                             <td> {{   $row->dob   }}</td>
                             
                            <td> {{ strtoupper(@$row->sex) }}</td>
                           
                            
                           
                            <td> {{ strtoupper(@$row->marital) }}</td>
                           
                             
                            <td> {{strtoupper( @$row->hometown) }} </td>
                           
                            
                           
                            <td> {{ strtoupper(@$row->placeofresidence) }}</td>
                            <td> {{ strtoupper(@$row->grade) }}</td>
                            <td> {{ strtoupper(@$row->position) }}</td>
                             <td> {{ strtoupper(@$row->dependentsNo) }}</td>
                            <td> {{ strtoupper(@$row->leaves)  }}</td>
                            <td> {{  @$row->ssnit }}</td>
                            <td> {{ strtoupper(@$row->nationality)  }}</td>
                            <td> {{ strtoupper(@$row->dateHired) }}</td>
                            <td> {{  @$row->phone }}</td>
                           <td> {{ strtoupper(@$row->email)  }}</td>
                           <td> {{ strtoupper(@$row->designation)  }}</td>
                            <td> {{ strtoupper(@$row->departments->name)  }}</td>
                           
                           <td>  <a  href='{{url("edit_worker/$row->id/id")}}' ><i title='edit member detials' class="md-icon material-icons">edit</i></a> 
                          
<!--                              <a onclick="return MM_openBrWindow('{{url("/worker/$row->id/show")}}','mark','width=800,height=500')" ><i title='Click to view member .. please allow popups on browser' class="md-icon material-icons">&#xE8F4;</i></a> 
                           -->
                    
                      {!!Form::open(['action' => ['StaffController@destroy', 'id'=>$row->id], 'method' => 'DELETE','name'=>'myform' ,'style' => 'display: inline;'])  !!}

                                                   <i onclick="UIkit.modal.confirm('Are you sure you want to delete this person?', function(){ document.forms[2].submit(); });" title="click to delete this" class="sidebar-menu-icon material-icons md-18 uk-text-danger">delete</i>
                                                        <input type='hidden' name='item' value='{{$row->id}}'/>  
                                                     {!! Form::close() !!}
                 
                             </td>

                        </tr>
                        @endforeach
                    </tbody>

                </table>
                   {!! (new Landish\Pagination\UIKit($data->appends(old())))->render() !!}
            </div>
        </div>


    </div>
    <div class="md-fab-wrapper">
        <a class="md-fab md-fab-small md-fab-accent md-fab-wave" title="add new staff" href="{{url('workers/add')}}"  >
            <i class="material-icons md-18">&#xE145;</i>
        </a>
    </div>
</div> 
@endsection
@section('js')
<script type="text/javascript">

    $(document).ready(function () {

        $(".parent").on('change', function (e) {

            $("#group").submit();

        });
    });

</script>
<script src="{!! url('public/assets/js/select2.full.min.js') !!}"></script>
<script>
     $(document).ready(function () {
         $('select').select2({width: "resolve"});


     });


</script>
<script>
                    $(document).ready(function(){
            $('.send').on('click', function(e){


                    
                    UIkit.modal.confirm("Are you sure every data is accurate?? "
                            , function(){
                            modal = UIkit.modal.blockUI("<div class='uk-text-center'>Send sms to members <br/><img class='uk-thumbnail uk-margin-top' src='{!! url('public/assets/img/spinners/spinner.gif')  !!}' /></div>");
                                    //setTimeout(function(){ modal.hide() }, 500) })()            
                                    $.ajax({
                                     
                                            type: "POST",
                                            url:"{{url('/staff/sms')}}",
                                            data: $('#form').serialize(), //your form data to post goes 
                                            dataType: "json",
                                    }).done(function(data){
                //  var objData = jQuery.parseJSON(data);
                modal.hide();
                        //                                    
                        //                                     UIkit.modal.alert("Action completed successfully");

                        //alert(data.status + data.data);
                        if (data.status == 'success'){
                $(".uk-alert-success").show();
                        $(".uk-alert-success").text(data.status + " " + data.message);
                        $(".uk-alert-success").fadeOut(4000);
                        // window.location.href="{{url('/members')}}";
                }
                else{
                $(".uk-alert-danger").show();
                        $(".uk-alert-danger").text(data.status + " " + data.message);
                        $(".uk-alert-danger").fadeOut(4000);
                }


                });
                            }
                    );
            });
            
             
            });</script>
<script src="assets/js/components_notifications.min.js"></script>
@endsection