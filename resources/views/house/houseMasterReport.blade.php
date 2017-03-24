@extends('layouts.app')

 
@section('style')
 <style>
    .marks{
         
height: auto;
margin: 0;
padding: 4px;
line-height: 24px;
border: 1px solid rgba(0,0,0,.12);
color: #212121;
box-sizing: border-box;
-webkit-transition: height .1s ease;
transition: height .1s ease;
border-radius: 0;
-webkit-appearance: none;
    }
</style>
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


</div>
  
 <div style="">
     <div class="uk-margin-bottom" style="margin-left:900px" >
          
         
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
                     <a href="{{url('/teachers/subject/allocation')}}" ><i   title="refresh this page" class="uk-icon-refresh uk-icon-medium "></i></a>
                          
                            
                            
                           
     </div>
 </div>
  <h5 class="heading_a">House Masters Report</h5>
 <div class="uk-width-xLarge-1-1">
    <div class="md-card">
        <div class="md-card-content">
            
                <form action=" "  method="get" accept-charset="utf-8" novalidate id="group">
                   {!!  csrf_field()  !!}
                    <div class="uk-grid" data-uk-grid-margin="">
 
                        <div class="uk-width-medium-1-5">
                            <div class="uk-margin-small-top">
                                  
                                    {!! Form::select('classs', 
                                (['' => 'All classes'] +$class ), 
                                  old("classs",""),
                                    ['class' => 'md-input parent selects','id'=>"parent",'placeholder'=>'select class'] )  !!}
                      
                            </div>
                        </div>
                       <div class="uk-width-medium-1-5">
                            <div class="uk-margin-small-top">
                                
                                {!!  Form::select('term', array(''=>'All terms','1'=>'1st Term','2'=>'2nd Term','3'=>'3rd Term' ), null, ['placeholder' => 'select term','class'=>'md-input selects parent','id'=>"parent"],old("term","")); !!}
                          
                            </div>
                        </div>
                         <div class="uk-width-medium-1-5">
                            <div class="uk-margin-small-top">
                                  
                                    {!! Form::select('year', 
                                (['' => 'All academic years'] +$year ), 
                                  old("year",""),
                                    ['class' => 'md-input parent selects','id'=>"parent",'placeholder'=>'select academic year'] )  !!}
                      
                            </div>
                        </div>  
                         
                       
                        
                        
                    
                    </div> 
                         
                   
                </form> 
        </div>
    </div>
 </div>
 <p></p>
 <div class="uk-width-xLarge-1-1">
    <div class="md-card">
        <div class="md-card-content">
   <div class="uk-overflow-container" id='print'>
       <form   accept-charset="utf-8" method="POST" id="form" name="update"  v-form>
                      {!!  csrf_field()  !!}
         <center><span class="uk-text-success uk-text-bold">{!! $data->total()!!} Records</span></center>
                <table class="uk-table uk-table-hover uk-table-align-vertical uk-table-nowrap tablesorter tablesorter-altair" id="ts_pager_filter"> 
               <div class="table-responsive">
                
                            <thead>
                                <tr>
                                    
                                     <th  data-type="numeric" data-identifier="true">No</th>
                                     <th data-column-id="Student"   data-toggle="tooltip">Student</th>
                                     <th data-column-id="Class" data-type=" " data-toggle="tooltip">Class</th>
                                    <th style="text-align" data-type="string" data-column-id="Total Score" style="text-align:center">Total Score</th>
                                    
                                        <th data-column-id="Position" data-order="asc" style="text-align: ">House Masters Report</th>
                                      
                                </tr>
                            </thead>
                            <tbody>
                                         <?php $count=0;?>
                                         @foreach(@$data as $index=> $row) 
                                         
                                           <?php $count++;?>
                                        <tr align="">
                                            <td> {{ $data->perPage()*($data->currentPage()-1)+($index+1) }} </td>
                                            <td> {{ strtoupper(@$row->name) }}</td>
                                            <td> {{ strtoupper(@$row->class)	 }}</td>
                                            <td> {{ @$row->total 	 }}</td>
                                             
                                            
                                           
                                            
                            
                                          
                                                 <td><input style="width:100%" name="housemaster[]"  class="marks" type="text" value="{{@$row->house_mast_report}}"/></td>
                                         
                                          
                            
                            
                            
                            
                            
                            
                            
                            
                            <input type="hidden" class="id"name="id[]" value="{{@$row->id}}"/>
                                            <input type="hidden" class="class"name="class[]" value="{{@$row->class}}"/>
                                             <input type="hidden" class="student"name="student[]" value="{{@$row->indexno}}"/>
                                               
                                             
                                          
                                        </tr>
                                             @endforeach
                                                <input type="hidden" name="upper" value="<?php echo $count;?>" id="upper" />
                                         
                                    </tbody>
                                    
                             </table>
           {!! (new Landish\Pagination\UIKit($data->appends(old())))->render() !!}
             <center><div style="position: fixed;  bottom: 0px;left: 45%  ">
                        <p>
                            <input type="hidden" class="upper" name="upper" value="{{$count++}}" id="upper" />
                            
                            <button type="button"  class="md-btn md-btn-success md-btn-small updates"><i class="fa fa-save" ></i>Update records</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            
                                   
                        </p>
                    </div></center>    
       </form>
     </div>
     </div>
<div class="md-fab-wrapper">
        <a class="md-fab md-fab-small md-fab-accent md-fab-wave" href="{{url('/teachers/subject/create')}}"  >
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
  <script src="{!! url('public/assets/js/select2.full.min.js') !!}"></script>
<script>
                    $(document).ready(function () {
            $('.selects').select2({width: "resolve"});
            });</script>
<script>
                    $(document).ready(function(){
            $('.updates').on('click', function(e){
  
 
                      
                    UIkit.modal.confirm("Are you sure you want to update  this report sheet??  " 
                            , function(){
                            modal = UIkit.modal.blockUI("<div class='uk-text-center'>Ok updating report sheet for the academic year..... <br/><img class='uk-thumbnail uk-margin-top' src='{!! url('public/assets/img/spinners/spinner.gif')  !!}' /></div>");
                                    //setTimeout(function(){ modal.hide() }, 500) })()            
                                    $.ajax({
                                     
                                            type: "POST",
                                            url:"{{ url('/report_housemaster')}}",
                                            data: $('#form').serialize(), //your form data to post goes 
                                            dataType: "json",
                                    }). done(function(data){
                //  var objData = jQuery.parseJSON(data);
                modal.hide();
                        //                                    
                        //                                     UIkit.modal.alert("Action completed successfully");

                        //alert(data.status + data.data);
                        if (data.status == 'success'){
                $(".uk-alert-success").show();
                        $(".uk-alert-success").text(data.status + " " + data.message);
                        $(".uk-alert-success").fadeOut(4000);
                        //window.location.href="{{url('/teachers/subject/allocation')}}";
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