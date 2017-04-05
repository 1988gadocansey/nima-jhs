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
                                  
                                    {!! Form::select('year', 
                                (['' => 'All academic years'] +$year ), 
                                  old("year",""),
                                    ['class' => 'md-input parent selects','id'=>"parent",'placeholder'=>'select academic year'] )  !!}
                      
                            </div>
                        </div>  
                         <div class="uk-width-medium-1-5">
                            <div class="uk-margin-small-top">
                                
                                {!!  Form::select('term', array(''=>'All terms','1'=>'1st Term','2'=>'2nd Term','3'=>'3rd Term' ), null, ['placeholder' => 'select term','class'=>'md-input selects parent','id'=>"parent"],old("term","")); !!}
                          
                            </div>
                        </div>
                       
                        
                        
                    
                    </div> 
                         
                   
                </form> 
        </div>
    </div>
 </div>
 <h5 class="heading_b">Broadsheet</h5>
 <div class="uk-width-xLarge-1-1">
    <div class="md-card">
        <div class="md-card-content">
   <div class="uk-overflow-container" id='print'>
       <form   accept-charset="utf-8" method="POST" id="form" name="applicationForms"  v-form>
                      {!!  csrf_field()  !!}
                 <table class="uk-table uk-table-hover uk-table-align-vertical uk-table-nowrap tablesorter tablesorter-altair" id="ts_pager_filter"> 
               <thead>
                 <tr>
                     <th class="filter-false remove sorter-false"  >NO</th>
                      <th>COURSE</th>
                     <th  style="text-align:center">CODE</th>
                     <th>PROGRAMME</th> 
                     <th>CLASS</th> 
                     <th style="text-align:">TEACHER</th>
                     <th style="text-align:">PHONE N<u>O</u></th>
                      <th style="text-align:">  DESIGNATION</th>
                     <th style="text-align: ">ACADEMIC YEAR</th>
                      <th style="text-align:center">TERM</th>
                                      
                </tr>
             </thead>
      <tbody>
                                        
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
  
 
                      
                    UIkit.modal.confirm("Are you sure you want to update the  allocation for this academic year??  " 
                            , function(){
                            modal = UIkit.modal.blockUI("<div class='uk-text-center'>Ok updating teacher subject allocation for the academic year..... <br/><img class='uk-thumbnail uk-margin-top' src='{!! url('public/assets/img/spinners/spinner.gif')  !!}' /></div>");
                                    //setTimeout(function(){ modal.hide() }, 500) })()            
                                    $.ajax({
                                     
                                            type: "POST",
                                            url:"{{ url('/courses/allocation/update')}}",
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