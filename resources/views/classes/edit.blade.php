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
 <h5 class="heading_c ">Edit Classes</h5>
<div class="uk-width-xLarge-1-10">
    <div class="md-card">
        <div class="md-card-content" style="">
     
           
             <form  action=""  id="formn" accept-charset="utf-8" method="POST" name="applicationForm"  v-form>
                <input type="hidden" name="_token" value="{!! csrf_token() !!}"> 
                  
                              
                  <div class="uk-grid" data-uk-grid-margin="">

                        <div class="uk-width-medium-1-5">
                            <div class="uk-margin-small-top">
                                
                               <label for="wizard_fullname">Class Name<span class="req uk-text-danger">*</span></label>
                                        <input type="text" name="name" class="md-input name" value="{{$data->name}}"v-model='name' v-form-ctrl='' required class="md-input" />
                                          <p class="uk-text-danger uk-text-small"  v-if="applicationForm.name.$error.required" >Class name is required</p>

                            </div>
                        </div>
                         
                       
                         <div class="uk-width-medium-1-5">
                            <div class="uk-margin-small-top">
                                  <label for="">Next Class :</label>     
                                        <div class="md-input-wrapper md-input-filled">
                                          {!!   Form::select('next',$class,old('next',''),array("required"=>"required","class"=>"md-input next","id"=>"next","v-model"=>"next","v-form-ctrl"=>"","style"=>"","v-select"=>"next")   )  !!}
                                    <span class="md-input-bar"></span>
                                        </div>
                                
                                
                                
                                <p class="uk-text-danger uk-text-small"  v-if="applicationForm.next.$error.required" >Next Class is required</p>

                            </div>
                        </div>

                        
                      <p>&nbsp;</p>
                         <div class="uk-width-medium-1-5">
                            <div class="uk-margin-small-top">
                                
                              
                                        <label for="">Class Teacher :</label>     
                                        <div class="md-input-wrapper md-input-filled">
                                          {!!   Form::select('teacher',$teacher,old('teacher',''),array("required"=>"required","class"=>"md-input teacher","id"=>"teacher","v-model"=>"teacher","v-form-ctrl"=>"","style"=>"","v-select"=>"teacher")   )  !!}
                                    <span class="md-input-bar"></span>
                                        </div> 
                                
                                
                                
                                <p class="uk-text-danger uk-text-small"  v-if="applicationForm.next.$error.required" >Teacher is required</p>

                            </div>
                        </div>

                         
                      <input type="hidden" name="id" class="id" value="{{$key}}"/>
                        
                        
                    
                    </div> 

            <div class="  uk-text-right">
                <input type="button" value="Update"  v-if="applicationForm.$valid"   class="md-btn  update md-btn-success uk-margin-small-top">
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
    next:"<?php echo $data->nextClass ?>",
    teacher:"<?php echo @$data->teacherId ?>",
      
  
 options: [    ]  
    
  },
   
})

</script>
<script>
                    $(document).ready(function(){
            $('.update').on('click', function(e){


            var next = $('.next').val();
             var teacher = $('.teacher').val();
              var name = $('.name').val();
               var id = $('.id').val();
                      
                      
                    UIkit.modal.confirm("Are you sure you want to update   "+ name
                            , function(){
                            modal = UIkit.modal.blockUI("<div class='uk-text-center'>Ok editing " + name + " <br/><img class='uk-thumbnail uk-margin-top' src='{!! url('public/assets/img/spinners/spinner.gif')  !!}' /></div>");
                                    //setTimeout(function(){ modal.hide() }, 500) })()            
                                    $.ajax({
                                     
                                            type: "POST",
                                            url:"{{ url('/classes/edit')}}",
                                            data: { id:id,name:name, teacher:teacher,next:next }, //your form data to post goes 
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
                        window.location.href="{{url('/classes')}}";
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