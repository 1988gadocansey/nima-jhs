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
  
 <div style="">
     <div class="uk-margin-bottom" style="margin-left:700px" >
           
         <a href="{{url('/upload/courses')}}" class="md-btn md-btn-small md-btn-primary uk-margin-right" >Upload Course from Excel</a>
       
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
 <div class="uk-modal" id="new_task">
    <div class="uk-modal-dialog">
        <div class="uk-modal-header">
            <h4 class="uk-modal-title">Create Course</h4>
        </div>
                 <form  action=""  id="formn" accept-charset="utf-8" method="POST" name="applicationFormn"  v-form>
                <input type="hidden" name="_token" value="{!! csrf_token() !!}"> 
                  <div class="uk-grid">
                                    <div class="uk-width-small-1-2 parsley-row">
                                          <label>Program<span class="req uk-text-danger">*</span></label>
                                <p></p>
                                        {!! Form::select('program', 
                                (['' => 'select program'] +$program ), 
                                  old("program",""),
                                    ['class' => 'md-input ddd','style'=>'width:400px','v-model'=>'program', 'required'=>'','v-form-ctrl'=>'','v-select'=>''] )  !!}
                                    
                                    </div>
                                </div>
              <div class="uk-grid">
                                    <div class="uk-width-small-1-2 parsley-row">
                                        <label for="wizard_fullname">Course Name<span class="req uk-text-danger">*</span></label>
                                        <input type="text" name="name" v-model='name' v-form-ctrl='' required class="md-input" />
                                     
                                    </div>
                                     <div class="parsley-row">
                                        <div class="uk-input-group">
                                            
                                            <label for="wizard_phone">Course Code<span class="req uk-text-danger">*</span></label>
                                            <input type="text" class="md-input" v-model='code' v-form-ctrl="" name="code" id="code" required=""/>
                                             
                                        </div>
                                    </div>
                  <div class="uk-form-row">
                                <label>Type<span class="req uk-text-danger">*</span></label>
                                <p></p>
                                {{ Form::select('type', array(  'Elective'=>'Elective','Core'=>'Core'), null, ['class' => 'md-input','v-model'=>'type','v-form-ctrl'=>'','v-select'=>'',"required"=>""]) }}
                                  
                               </div>
                                </div>
                              
                                
                 

            <div class="uk-modal-footer uk-text-right">
               <input type="submit" value="Save" id='save'  class="md-btn   md-btn-success uk-margin-small-top">
       <button type="button" class="md-btn md-btn-flat uk-modal-close md-btn-wave">Close</button>
            </div>
        </form>
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
                                    {!! Form::select('program', 
                                (['' => 'All programs'] +$program ), 
                                  old("program",""),
                                    ['class' => 'md-input parent','id'=>"parent",'placeholder'=>'select program'] )  !!}
                         </div>
                        </div>
                        
                       
                         <div class="uk-width-medium-1-5">
                            <div class="uk-margin-small-top">
                                
                                {!!  Form::select('by', array('code'=>'Course Code','name'=>'Course Name' ), null, ['placeholder' => 'select criteria','class'=>'md-input'],old("by","")); !!}
                          
                            </div>
                        </div>

                        
                         <div class="uk-width-medium-1-5">
                            <div class="uk-margin-small-top">                            
                                <input type="text" style=" "   name="search"  class="md-input" placeholder="search by course name or course code">
                            </div>
                        </div>
                         
                         <div  align='center'>
                            
                            <button class="md-btn  md-btn-small md-btn-success uk-margin-small-top" type="submit"><i class="material-icons">search</i></button> 
                             
                        </div>
                        
                       
                        
                        
                    
                    </div> 
                         
                   
                </form> 
        </div>
    </div>
 </div>
 <h5>Courses</h5>
 <div class="uk-width-xLarge-1-1">
    <div class="md-card">
        <div class="md-card-content">
   <div class="uk-overflow-container" id='print'>
         <center><span class="uk-text-success uk-text-bold">{!! $data->total()!!} Records</span></center>
                <table class="uk-table uk-table-hover uk-table-align-vertical uk-table-nowrap tablesorter tablesorter-altair" id="ts_pager_filter"> 
               <thead>
                 <tr>
                     <th class="filter-false remove sorter-false"  >NO</th>
                      <th>COURSE</th>
                     <th  style="text-align: ">CODE</th>
                     <th  style="text-align: ">TYPE</th>
                     <th>PROGRAMME</th> 
                    
                      <th  class="filter-false remove sorter-false uk-text-center" colspan="2" data-priority="1">ACTION</th>   
                                     
                </tr>
             </thead>
      <tbody>
                                        
                                         @foreach($data as $index=> $row) 
                                         
                                         
                                        <tr align="">
                                            <td> {{ $data->perPage()*($data->currentPage()-1)+($index+1) }} </td>
                                            <td> {{ strtoupper(@$row->name) }}</td>
                                            <td> {{ strtoupper(@$row->code)	 }}</td>
                                            <td> {{ strtoupper(@$row->type)	 }}</td>
                                            <td> {{strtoupper( @$row->programme->name)	 }}</td>
                                               
                                            <td class="uk-text-center"> 
                                                
                                            <a href='{{url("edit_course/$row->id/id")}}' ><i title='Click to edit course' class="md-icon material-icons">edit</i></a> 
                                              
                                             {!!Form::open(['action' =>['CourseController@destroy', 'id'=>$row->id], 'method' => 'DELETE','name'=>'c' ,'style' => 'display: inline;'])  !!}

                                                      <button type="submit" onclick="return confirm('Are you sure you want to delete   {{$row->name . $row->classId}} -  {{ @$row->programme->name	 }}?')" class="md-btn  md-btn-danger md-btn-small   md-btn-wave-light waves-effect waves-button waves-light" ><i  class="sidebar-menu-icon material-icons md-18">delete</i></button>
                                                        
                                                     {!! Form::close() !!}
                                            </td>
                                          
                                        </tr>
                                            @endforeach
                                    </tbody>
                                    
                             </table>
           {!! (new Landish\Pagination\UIKit($data->appends(old())))->render() !!}
     </div>
     </div>
<div class="md-fab-wrapper">
        <a class="md-fab md-fab-small md-fab-accent md-fab-wave" href="#new_task" data-uk-modal="{ center:true }">
            <i class="material-icons md-18">&#xE145;</i>
        </a>
    </div>
 </div>
</div>
  
@endsection
@section('js')
 <script type="text/javascript">
      
$(document).ready(function(){
 
$(".parent").on('change',function(e){
 
   $("#group").submit();
 
});
});

</script>
 
 <!--  notifications functions -->
    <script src="public/assets/js/components_notifications.min.js"></script>
    <script>


//code for ensuring vuejs can work with select2 select boxes
Vue.directive('select', {
  twoWay: true,
  priority: 1000,
  params: [ 'options'],
  bind: function () {
    var self = this
    $(this.el)
      .select2({
        data: this.params.options,
         width: "resolve"
      })
      .on('change', function () {
        self.vm.$set(this.name,this.value)
        Vue.set(self.vm.$data,this.name,this.value)
      })
  },
  update: function (newValue,oldValue) {
    $(this.el).val(newValue).trigger('change')
  },
  unbind: function () {
    $(this.el).off().select2('destroy')
  }
})


var vm = new Vue({
  el: "body",
  ready : function() {
  },
 data : {
   
   
 options: [    ]  
    
  },
   
})

</script>
@endsection