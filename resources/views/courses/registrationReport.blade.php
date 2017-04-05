@extends('layouts.app')

 
@section('style')
 
@endsection
 @section('content')
   <div class="md-card-content">
@if(Session::has('success'))
            <div style="text-align: center" class="uk-alert uk-alert-success" data-uk-alert="">
                {!! Session::get('success') !!}
            </div>
 @endif
 @if(Session::has('error'))
            <div style="text-align: center" class="uk-alert uk-alert-danger" data-uk-alert="">
                {!! Session::get('error') !!}
            </div>
 @endif
 
     @if (count($errors) > 0)

    <div class="uk-form-row">
        <div class="uk-alert uk-alert-danger" style="background-color: red;color: white">

              <ul>
                @foreach ($errors->all() as $error)
                  <li> {{  $error  }} </li>
                @endforeach
          </ul>
    </div>
  </div>
@endif
  </div>
    @inject('sys', 'App\Http\Controllers\SystemController')
 <h5 class="heading_c">Registration Statistics for the {{$years}}  {{$sem}}Sem Academic year</h5>
 
 <div style="">
     <div class="uk-margin-bottom" style="margin-left:750px" >
         <!-- <a  href="#new_task" data-uk-modal="{ center:true }"> <i title="click to send sms to students owing"   class="material-icons md-36 uk-text-success"   >phonelink_ring message</i></a>
 -->
         <a href="#" class="md-btn md-btn-small md-btn-success uk-margin-right" id="printTable">Print Table</a>
        <!--  <a href="#" class="md-btn md-btn-small md-btn-success uk-margin-right" id="">Import from Excel</a>
         -->
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
                                         <li><a href="#" onClick ="$('#ts_pager_filter').tableExport({type:'csv',escape:'false'});"><img src='{!! url("public/assets/icons/csv.png")!!}' width="24"/> CSV</a></li>
                                           
                                            <li class="uk-nav-divider"></li>
                                            <li><a href="#" onClick ="$('#ts_pager_filter').tableExport({type:'excel',escape:'false'});"><img src='{!! url("public/assets/icons/xls.png")!!}' width="24"/> XLS</a></li>
                                            <li><a href="#" onClick ="$('#ts_pager_filter').tableExport({type:'doc',escape:'false'});"><img src='{!! url("public/assets/icons/word.png")!!}' width="24"/> Word</a></li>
                                            <li><a href="#" onClick ="$('#ts_pager_filter').tableExport({type:'powerpoint',escape:'false'});"><img src='{!! url("public/assets/icons/ppt.png")!!}' width="24"/> PowerPoint</a></li>
                                            <li class="uk-nav-divider"></li>
                                           
                                    </ul>
                                </div>
                            </div>
                       
                           
                            
                                                   
                                  <i title="click to print" onclick="javascript:printDiv('print')" class="material-icons md-36 uk-text-success"   >print</i>
                   
                            
                           
     </div>
 </div>
 <div class="uk-width-xLarge-1-1">
    <div class="md-card">
        <div class="md-card-content">
            
                <form action=" "  method="get" accept-charset="utf-8" novalidate id="group">
                   {!!  csrf_field()  !!}
                    <div class="uk-grid" data-uk-grid-margin="">

                        <div class="uk-width-medium-1-5">
                            <div class="uk-margin-small-top">
                                    {!! Form::select('year', 
                                (['' => 'All years'] +$year ), 
                                  old("year",""),
                                    ['class' => 'md-input parent','id'=>"parent",'placeholder'=>'select academic year'] )  !!}
                         </div>
                        </div>
                         <div class="uk-width-medium-1-5">
                            <div class="uk-margin-small-top">
                                 
                                              {!!  Form::select('semester', array('1'=>'1st sem','2'=>'2nd sem','3' => '3rd sem'), null, ['placeholder' => 'select semester','id'=>'parent','class'=>'md-input parent'],old("semester","")); !!}
                          
                            </div>
                        </div>
                        
                        <div class="uk-width-medium-1-5">
                            <div class="uk-margin-small-top">
                                 
                                             {!!  Form::select('level', array( '1'=>'Level 100','2' => 'Level 200', '3' => 'Level 300','400/1'=>'BTECH level 100','400/2'=>'BTECH level 200'), null, ['placeholder' => 'select level','id'=>'parent','class'=>'md-input parent'],old("level","")); !!}
                          
                            </div>
                        </div>
                       
                        

                        
                         <div class="uk-width-medium-1-5">
                            <div class="uk-margin-small-top">                            
                                
                              <button class="md-btn save md-btn-small md-btn-success uk-margin-small-top" type="submit"><i class="material-icons">search</i></button> 
                          
                            </div>
                        </div>
                         
                         
                        
                        
                        
                        
                    
                    </div> 
                         
                   
                </form> 
        </div>
    </div>
 </div>
    <p>&nbsp;</p>
    <div class="uk-width-xLarge-1-1">
    <div class="md-card">
        <div class="md-card-content">
      <div class="uk-overflow-container" id='print'>
          <center><span class="uk-text-success uk-text-bold">{!! $data->total()!!} Records</span></center>
        
                 <table class="uk-table uk-table-hover uk-table-align-vertical uk-table-nowrap tablesorter tablesorter-altair" id="ts_pager_filter"> 
                                  <thead>
                                        <tr>
                                            <th class="uk-width-1-10">N<u>o</u></th>
                                            <th class=" uk-text-small"  >PROGRAMME</th>
                                           <th class=" uk-text-small"  >NO. OF STUDENTS</th>
                                            
                                            <th>LEVEL 100 </th>
                                            <th>LEVEL 200 </th>
                                            <th>LEVEL 300 </th>
                                           
                                            <th>BTECH TOPUP LEVEL 100  </th>
                                            <th>BTECH TOPUP LEVEL 200 </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                         
                                     @foreach($data as $index=> $row) 
                                     <tr>
                                         <td> {{ $data->perPage()*($data->currentPage()-1)+($index+1) }} </td>
                                         
                                         <td class="uk-text-upper">{{@$sys->getProgram($row->PROGRAMMECODE)}}</td>
                                       <td style="text-align: center"class=''>{{$sys->getStudentsTotalPerProgram($row->PROGRAMMECODE)}}<?php $total[] = $sys->getStudentsTotalPerProgram($row->PROGRAMMECODE) ?></td>
                                       <td style="text-align: center"class=''>{{$sys->getStudentsTotalPerProgramLevel($row->PROGRAMMECODE,'1')}} </td>
                                         <td style="text-align: center"class=''>{{$sys->getStudentsTotalPerProgramLevel($row->PROGRAMMECODE,'2')}} </td>
                                         <td style="text-align: center"class=''>{{$sys->getStudentsTotalPerProgramLevel($row->PROGRAMMECODE,'3')}}</td>
                                          
                                         <td style="text-align: center"class=''>{{$sys->getStudentsTotalPerProgramLevel($row->PROGRAMMECODE,'400/1')}}</td>
                                         <td style="text-align: center"class=''>{{$sys->getStudentsTotalPerProgramLevel($row->PROGRAMMECODE,'400/2')}}</td>
                                   
                                     </tr>
                                        @endforeach
                                      
                                       
                                       
                                         
                                    </tbody>
                                    
                             </table>
          <table>
             
          </table>
        
           
     </div>
  
     </div>
 
 </div>
</div>
  
@endsection
@section('js')
 
 <script src="{!! url('public/assets/js/select2.full.min.js') !!}"></script>
<script>
$(document).ready(function(){
  $('select').select2({ width: "resolve" });

  
});


</script>
 <!--  notifications functions -->
    <script src="public/assets/js/components_notifications.min.js"></script>
@endsection