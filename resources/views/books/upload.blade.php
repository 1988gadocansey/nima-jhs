@extends('layouts.app')

 
   
@section('style')
 
        <script src="{!! url('public/assets/js/jquery.min.js') !!}"></script>
 
        <script src="{!! url('public/assets/js/jquery-ui.min.js') !!}"></script>
 
    <link rel="stylesheet" href="{!! url('public/assets/css/jquery-ui.css') !!}" media="all">
        
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
 
 <div align="center">
     <form method="POST" enctype="multipart/form-data"  accept-charset="utf-8"  name="applicationForm" id="form" v-form>
         <input type="hidden" name="_token" value="{!! csrf_token() !!}"> 

         <div class="uk-width-small-1-2">
              
                 <div class="md-card">
                     <div class="md-card-content">
                         <h3 class="heading_a uk-margin-small-bottom">
                            Upload books to library here  Max size - Excel file formats only(10MB)
                         </h3>
                         <input type="file" id="input-file-e" required="" name="file" v-model="file" v-form-ctrl="" class="dropify file" data-max-file-size="200000K" />
                     </div>
                 </div>
                 <div class="uk-grid"  >
                 <div class="uk-width-1-1">
                     <input type="submit"  v-show="applicationForm.$valid" class="md-btn md-btn-primary upload" value="upload"  />
                 </div>
            
             </div>
             
         </div>
        
     </form>
      <p>click to download book upload template(PLEASE ADHERE TO THE TEMPLATE)<a href='{{url("public/uploads/book.csv")}}' download="book.csv">click download template</a></p>
 
 </div>
 
   
   
 @endsection
 
@section('js')
 <script>
                    $(document).ready(function(){
            $('.uploads').on('click', function(e){

                     
                    UIkit.modal.confirm("Are you sure every data is accurate?? "
                            , function(){
                            modal = UIkit.modal.blockUI("<div class='uk-text-center'>Uploading members <br/><img class='uk-thumbnail uk-margin-top' src='{!! url('public/assets/img/spinners/spinner.gif')  !!}' /></div>");
                                    //setTimeout(function(){ modal.hide() }, 500) })()            
                                    $.ajax({
                                     
                                            type: "POST",
                                            url:"{{url('book/upload')}}",
                                           data: $('#form').serialize(),
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
                        window.location.href="{{url('/books')}}";
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