@extends('layouts.app')


@section('style')
<style>
    .md-card{
        width: auto;

    }
    
</style>
 <script src="{!! url('public/assets/js/jquery.min.js') !!}"></script>
 
        <script src="{!! url('public/assets/js/jquery-ui.min.js') !!}"></script>
 
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
     <h5 class="heading_c ">Create Subject Allocations here</h5>
<div class="uk-width-xLarge-1-10">
    <div class="md-card">
        <div class="md-card-content" style="">

       
            <form  action=""  id="form" accept-charset="utf-8" method="POST" name="applicationForm"  v-form>
                <input type="hidden" name="_token" value="{!! csrf_token() !!}"> 
                   <div class="uk-grid" data-uk-grid-margin="">

                        <div class="uk-width-medium-1-5">
                            <div class="uk-margin-small-top">
                                
                              
                                        <label for="">Staff <span class="req uk-text-danger">*</span></label>
                                        <p></p>
                                        <div class="md-input-wrapper md-input-filled">
                                          {!!   Form::select('staff',$staff,old('staff',''),array("required"=>"required","class"=>"md-input staff","id"=>"staff","v-model"=>"staff","v-form-ctrl"=>"","style"=>"","v-select"=>"staff"))  !!}
                                     <p class="uk-text-danger uk-text-small"  v-if="applicationForm.staff.$error.required" >Subject Teacher is required</p>

                                          <span class="md-input-bar"></span>
                                        </div> 
                                
                                
                                
                               
                            </div>
                        </div>
                         
                       
                          <div class="uk-width-medium-1-2">
                            <div class="uk-margin-small-top">
                                
                              
                                        <label for="">Subject <span class="req uk-text-danger">*</span></label>
                                        <p></p>
                                        <div class="md-input-wrapper md-input-filled">
                                          {!!   Form::select('subject',$subject,old('subject',''),array("required"=>"required","class"=>"md-input subject","id"=>"subject","v-model"=>"subject","v-form-ctrl"=>"","style"=>"","v-select"=>"subject")   )  !!}
                                     <p class="uk-text-danger uk-text-small"  v-if="applicationForm.subject.$error.required" >Subject is required</p>

                                          <span class="md-input-bar"></span>
                                        </div> 
                                
                                
                                
                             
                            </div>
                        </div>

                        
                      <p>&nbsp;</p>
                          <div class="uk-width-medium-1-5">
                            <div class="uk-margin-small-top">
                                
                              
                                        <label for="">Class <span class="req uk-text-danger">*</span></label>
                                        <p></p>
                                        <div class="md-input-wrapper md-input-filled">
                                          {!!   Form::select('classs',$class,old('classs',''),array("required"=>"required","class"=>"md-input classs","id"=>"class","v-model"=>"classs","v-form-ctrl"=>"","style"=>"","v-select"=>"classs")   )  !!}
                                    <p class="uk-text-danger uk-text-small"  v-if="applicationForm.classs.$error.required" >Class is required</p>

                                          <span class="md-input-bar"></span>
                                        </div> 
                                
                                
                                
                                
                            </div>
                        </div>

                         
                         
                        
                    
                    </div>
                 <table align="center">
       
                     <tr><td><input type="button" value="Save" v-show="applicationForm.$valid"   class="md-btn   md-btn-success saves uk-margin-small-top">
      <input type="reset" value="Clear" class="md-btn   md-btn-danger uk-margin-small-top">
    </td></tr></table>
            </form>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="{!! url('public/assets/js/select2.full.min.js') !!}"></script>
<script>
                    $(document).ready(function () {
            $('.selects').select2({width: "resolve"});
            });</script>
<script>
                    $(document).ready(function(){
            $('.saves').on('click', function(e){
 

            var staff = $('.staff').val();
             var classs = $('.classs').val();
              var subject = $('.subject').val();
                
                  
                     
                    UIkit.modal.confirm("Are you sure you want to allocate   " + subject + " to " + staff
                            , function(){
                            modal = UIkit.modal.blockUI("<div class='uk-text-center'>Ok allocating " + subject + " to " + staff + " <br/><img class='uk-thumbnail uk-margin-top' src='{!! url('public/assets/img/spinners/spinner.gif')  !!}' /></div>");
                                    //setTimeout(function(){ modal.hide() }, 500) })()            
                                    $.ajax({
                                     
                                            type: "POST",
                                            url:"{{ url('/teachers/subject/create')}}",
                                            data: {  staff:staff, subject:subject,classs:classs }, //your form data to post goes 
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