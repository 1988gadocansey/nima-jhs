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
 <h5 class="heading_c ">Edit Course</h5>
<div class="uk-width-xLarge-1-10">
    <div class="md-card">
        <div class="md-card-content" style="">
     
           
             <form  action=""   accept-charset="utf-8" method="POST" name="applicationForm"  v-form>
                <input type="hidden" name="_token" value="{!! csrf_token() !!}"> 
                  
                              
                  <div class="uk-grid" data-uk-grid-margin="">

                        <div class="uk-width-medium-1-5">
                            <div class="uk-margin-small-top">
                                
                               <label for="wizard_fullname">Course Name<span class="req uk-text-danger">*</span></label>
                                 <div class="md-input-wrapper md-input-filled">        
                               <input type="text" class="md-input name" name="name" value="{{$data->name}}"v-model='name' v-form-ctrl='' required class="md-input" />
                                          <p class="uk-text-danger uk-text-small"  v-if="applicationForm.name.$error.required" >Course Name is required</p>
                                 </div>
                                 </div>
                        </div>
                         
                       
                         <div class="uk-width-medium-1-5">
                            <div class="uk-margin-small-top">
                                
                               <label for="wizard_fullname">Course Code<span class="req uk-text-danger">*</span></label>
                                 <div class="md-input-wrapper md-input-filled">        
                               <input type="text" name="code" value="{{$data->code}}"v-model='code' v-form-ctrl='' required="" class="md-input code" />      
                                <p class="uk-text-danger uk-text-small"  v-if="applicationForm.code.$error.required" >Course Code is required</p>
                                 </div>
                                 </div>
                        </div>

                        
                      <p>&nbsp;</p>
                         <div class="uk-width-medium-1-5">
                            <div class="uk-margin-small-top">
                                
                              
                                        <label for="">Program <span class="req uk-text-danger">*</span></label>
                                        <p></p>
                                        <div class="md-input-wrapper md-input-filled">
                                          {!!   Form::select('program',$program,old('program',''),array("required"=>"required","class"=>"md-input program","id"=>"program","v-model"=>"program","v-form-ctrl"=>"","style"=>"","v-select"=>"program")   )  !!}
                                    <span class="md-input-bar"></span>
                                        </div> 
                                
                                
                                
                                <p class="uk-text-danger uk-text-small"  v-if="applicationForm.program.$error.required" >Program is required</p>

                            </div>
                        </div>

                         
                      <input type="hidden" name="id" class="id" value="{{$key}}"/>
                        
                        
                    
                    </div> 

            <div class="  uk-text-right">
                <input type="button" value="Update" v-show="applicationForm.$valid"  class="md-btn  update md-btn-success uk-margin-small-top">
       <button type="button" class="md-btn md-btn-flat uk-modal-close md-btn-wave">Cancel</button>
            </div>
        </form>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="{!! url('public/assets/js/select2.full.min.js') !!}"></script>
<script>
                    $(document).ready(function () {
            $('.select').select2({width: "resolve"});
            });</script>
<!--<script>
        $(document).ready(function(){
            $("#form").on("submit",function(event){
                event.preventDefault();
       UIkit.modal.alert('Updating class: {{$data->name}}');
         $(event.target).unbind("submit").submit();
    
                        
            });
            
    
                    
    
    });
</script>-->
   
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
    name:"<?php echo $data->name ?>",
    code:"<?php echo @$data->code ?>",
    program:"<?php echo @$data->pcode ?>",
      
  
 options: [    ]  
    
  },
   
})

</script>
<script>
                    $(document).ready(function(){
            $('.update').on('click', function(e){


            var program = $('.program').val();
             var code = $('.code').val();
              var name = $('.name').val();
               var id = $('.id').val();
                      //alert(code);
                     
                    UIkit.modal.confirm("Are you sure you want to update   "+ name
                            , function(){
                            modal = UIkit.modal.blockUI("<div class='uk-text-center'>Ok editing " + name + " <br/><img class='uk-thumbnail uk-margin-top' src='{!! url('public/assets/img/spinners/spinner.gif')  !!}' /></div>");
                                    //setTimeout(function(){ modal.hide() }, 500) })()            
                                    $.ajax({
                                     
                                            type: "POST",
                                            url:"{{ url('/edit_course/course')}}",
                                            data: { id:id,name:name, program:program,code:code }, //your form data to post goes 
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
                        window.location.href="{{url('/courses')}}";
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
@endsection    