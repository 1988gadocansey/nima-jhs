@extends('layouts.app')


@section('style')
<style>
    input{
        text-transform: uppercase
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
<h5 class="heading_b uk-margin-bottom">Add Books to library  here</h5>
<div class="uk-width-xLarge-1-10">
    <div class="md-card">
        <div class="md-card-content" style="">

             
            
        <form  novalidate id="wizard_advanced_form" class="uk-form-stacked"    method="post" accept-charset="utf-8"  name="bookForm"  v-form>

                {!!  csrf_field() !!}
                <div data-uk-observe="" id="wizard_advanced" role="application" class="wizard clearfix">
                    <div class="steps clearfix">
                        <ul role="tablist">
                            <li role="tab" class="fill_form_header first current" aria-disabled="false" aria-selected="true" v-bind:class="{ 'error' : !in_payment_section}">
                                <a aria-controls="wizard_advanced-p-0" href="#wizard_advanced-h-0" id="wizard_advanced-t-0">
                                    <span class="current-info audible">current step: </span><span class="number">1</span> <span class="title">Step 1</span>
                                </a>
                            </li>
                            <li role="tab" class="payment_header disabled" aria-disabled="true"   v-bind:class="{ 'error' : in_payment_section}" >
                                <a aria-controls="wizard_advanced-p-1" href="#wizard_advanced-h-1" id="wizard_advanced-t-1">
                                    <span class="number">2</span> <span class="title">Step 2</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class=" clearfix " style="box-sizing: border-box;display: block;padding:15px!important;position: relative;">

                        <!-- first section -->
                        {{-- <h3 id="wizard_advanced-h-0" tabindex="-1" class="title current">Fill Form</h3> --}}
                        <section id="fill_form_section" role="tabpanel" aria-labelledby="fill form section" class="body step-0 current" data-step="0" aria-hidden="false"   v-bind:class="{'uk-hidden': in_payment_section} ">

                            <div data-uk-grid-margin="" class="uk-grid uk-grid-width-medium-1-4 uk-grid-width-large-1-4">


                                <div class="parsley-row">
                                    <div class="uk-input-group">

                                        <div class="md-input-wrapper md-input-filled"><label for="wizard_referer">Book Title :</label><input type="text" id="title" name="title" class="md-input"   required="required"     v-model="title"  v-form-ctrl><span class="md-input-bar"></span></div>                
                                        <p  class=" uk-text-danger uk-text-small  "   v-if="bookForm.title.$error.required">Title of book is required</p>                                      
                                    </div>
                                </div>

                                <div class="parsley-row">
                                    <div class="uk-input-group">

                                        <div class="md-input-wrapper md-input-filled"><label for="wizard_referer">Copy Number:</label><input type="text" id="copyno" name="copyno" class="md-input"   required="required"       v-model="copyno"  v-form-ctrl><span class="md-input-bar"></span></div>                
                                        <p  class=" uk-text-danger uk-text-small  "   v-if="bookForm.copyno.$error.required">Copy number required</p>                                      
                                    </div>
                                </div>

                                <div class="parsley-row">
                                    <div class="uk-input-group">

                                        <div class="md-input-wrapper md-input-filled"><label for="wizard_referer">Author:</label><input type="text" id="author" name="author" class="md-input"   required="required"       v-model="author"  v-form-ctrl><span class="md-input-bar"></span></div>                
                                        <p  class=" uk-text-danger uk-text-small  "   v-if="bookForm.author.$error.required">Author is required</p>                                      
                                    </div>
                                </div>
                                <div class="parsley-row">
                                    <div class="uk-input-group">

                                        <label for="">Status :</label>     
                                        <div class="md-input-wrapper md-input-filled">
                                            {!!   Form::select('status',array("AVALIABLE"=>"Avaliable in Library","NEW"=>"New","DAMAGE"=>"Damage","BORROWED"=>"Borrowed"),old('status',''),array('placeholder'=>'Select status',"required"=>"required","class"=>"md-input","v-model"=>"title","v-form-ctrl"=>"","v-select"=>"status"))  !!}
                                            <span class="md-input-bar"></span>
                                        </div>    
                                        <p class="uk-text-danger uk-text-small"  v-if="bookForm.status.$error.required">Status is required</p>                                        
                                    </div>
                                </div>

                            </div>

                             <div data-uk-grid-margin="" class="uk-grid uk-grid-width-medium-1-4 uk-grid-width-large-1-4">


                                <div class="parsley-row">
                                    <div class="uk-input-group">

                                        <div class="md-input-wrapper md-input-filled"><label for="wizard_twitter">Edition :</label><input type="text" name="edition" class="md-input"  required="required"  v-model="edition"  v-form-ctrl   ><span class="md-input-bar"></span></div>
                                        <p class="uk-text-danger uk-text-small " v-if="bookForm.edition.$error.required" >Edition   is required</p>                                           
                                    </div>
                                </div>
                               <div class="parsley-row">
                                    <div class="uk-input-group">

                                        <div class="md-input-wrapper md-input-filled"><label for="wizard_twitter">Date Published :</label><input type="text" name="publication" class="md-input" data-uk-datepicker="{format:'DD/MM/YYYY'}" required="required"  v-model="publication"  v-form-ctrl   ><span class="md-input-bar"></span></div>
                                        <p class="uk-text-danger uk-text-small " v-if="bookForm.publication.$error.required" >Date of publication is required</p>                                           
                                    </div>
                                </div>
                                <div class="parsley-row">
                                    <div class="uk-input-group">

                                        <div class="md-input-wrapper md-input-filled"><label for="wizard_referer">Place of Pulbication :</label><input type="text" id="place" name="place" class="md-input"  v-model="place"  v-form-ctrl><span class="md-input-bar"></span></div>                
                                      </div>
                                </div>



                                
                                   <div class="parsley-row">
                                    <div class="uk-input-group">

                                        <div class="md-input-wrapper md-input-filled"><label for="wizard_referer">Publisher :</label><input type="text" id="pubisher" name="publisher" class="md-input"          v-model="publisher"  v-form-ctrl><span class="md-input-bar"></span></div>                
                                     </div>
                                </div>
                            </div>

               <div data-uk-grid-margin="" class="uk-grid uk-grid-width-medium-1-4 uk-grid-width-large-1-4">
                                  <div class="parsley-row">
                                    <div class="uk-input-group">

                                        <div class="md-input-wrapper md-input-filled"><label for="wizard_referer">No of copies :</label><input type="text" id="copies" name="copies" class="md-input"   required="required"      v-model="copies"  v-form-ctrl><span class="md-input-bar"></span></div>                
                                        <p  class=" uk-text-danger uk-text-small  "   v-if="bookForm.copies.$error.required">No of copied is required</p>                                      
                                    </div>
                                </div>

 
                                  <div class="parsley-row">
                                    <div class="uk-input-group">

                                        <div class="md-input-wrapper md-input-filled"><label for="wizard_twitter">Accention No :</label><input type="text" name="accention" class="md-input"  v-model="accention"  v-form-ctrl   ><span class="md-input-bar"></span></div>
                                     </div>
                                </div>
                                <div class="parsley-row">
                                    <div class="uk-input-group">

                                        <div class="md-input-wrapper md-input-filled"><label for="wizard_twitter">ISBN :</label><input type="text" name="isbn" class="md-input"  v-model="isbn"  v-form-ctrl   ><span class="md-input-bar"></span></div>
                                     </div>
                                </div>

                                
                                <div class="parsley-row">
                                    <div class="uk-input-group">

                                        <div class="md-input-wrapper md-input-filled"><label for="wizard_referer">Copyright Year :</label><input type="text" id="copyright" name="copyright" class="md-input"         v-model="copyright"  v-form-ctrl><span class="md-input-bar"></span></div>                
                                      </div>
                                </div>

                               

                            </div>
                                 <div data-uk-grid-margin="" class="uk-grid uk-grid-width-medium-1-4 uk-grid-width-large-1-4">


                                <div class="parsley-row">
                                    <div class="uk-input-group">

                                        <div class="md-input-wrapper md-input-filled"><label for="wizard_twitter">Date Received :</label><input type="text" name="received" class="md-input" data-uk-datepicker="{format:'DD/MM/YYYY'}" required="required"  v-model="received"  v-form-ctrl   ><span class="md-input-bar"></span></div>
                                        <p class="uk-text-danger uk-text-small " v-if="bookForm.received.$error.required" >Date book is received is required</p>                                           
                                    </div>
                                </div>
                             <div class="parsley-row">
                                    <div class="uk-input-group">

                                        <div class="md-input-wrapper md-input-filled"><label for="wizard_twitter">Date Book added to Library :</label><input type="text" name="added" class="md-input" data-uk-datepicker="{format:'DD/MM/YYYY'}" required="required"  v-model="added"  v-form-ctrl   ><span class="md-input-bar"></span></div>
                                        <p class="uk-text-danger uk-text-small " v-if="bookForm.added.$error.required" >Date book is added to library is required</p>                                           
                                    </div>
                                </div>    

                               <div class="parsley-row">
                                    <div class="uk-input-group">

                                        <div class="md-input-wrapper md-input-filled"><label for="wizard_twitter">Collation No :</label><input type="text" name="collation" class="md-input"  v-model="collation"  v-form-ctrl   ><span class="md-input-bar"></span></div>
                                     </div>
                                </div>
                                      <div class="parsley-row">
                                    <div class="uk-input-group">

                                        <div class="md-input-wrapper md-input-filled"><label for="wizard_referer">Book Category:</label><input type="text" id="category" name="category" class="md-input"   required="required"      v-model="category"  v-form-ctrl><span class="md-input-bar"></span></div>                
                                        <p  class=" uk-text-danger uk-text-small  "   v-if="bookForm.category.$error.required">Category of book is required</p>                                      
                                    </div>
                                </div>



                            </div>

                          

      </section>

      <!-- second section -->
      {{-- <h3 id="payment-heading-1" tabindex="-1" class="title">Payment</h3> --}}
      <section id="payment_section" role="tabpanel" aria-labelledby="payment section" class="body step-1 "  v-bind:class="{'uk-hidden': !in_payment_section} "  data-step="1"  aria-hidden="true">
        <h2 class="heading_a">
       <div data-uk-grid-margin="" class="uk-grid uk-grid-width-medium-1-4 uk-grid-width-large-1-4">


                                <div class="parsley-row">
                                    <div class="uk-input-group">
                                        
                                        <div class="md-input-wrapper md-input-filled"><label for="wizard_email">Class Mark :</label><input type="text" id="class" name="class" class="md-input"   v-model="class"v-form-ctrl  ><span class="md-input-bar"></span></div>                                            
                                        
                                    </div>
                                </div>

                               
                                 
 
                               <div class="parsley-row">
                                    <div class="uk-input-group">
                                        
                                        <div class="md-input-wrapper md-input-filled"><label for="wizard_email">Format of Book</label><input type="text" id="" name="format" class="md-input"   v-model="format"v-form-ctrl  ><span class="md-input-bar"></span></div>                                            
                                        
                                    </div>
                                </div>
                            <div class="parsley-row">
                                    <div class="uk-input-group">
                                        
                                        <div class="md-input-wrapper md-input-filled"><label for="wizard_email">Series</label><input type="text" id="" name="series" class="md-input"   v-model="series"v-form-ctrl  ><span class="md-input-bar"></span></div>                                            
                                        
                                    </div>
                                </div>
          <div class="parsley-row">
                                    <div class="uk-input-group">
                                        
                                        <div class="md-input-wrapper md-input-filled"><label for="wizard_email">Location</label><input type="text" id="" name="location" class="md-input" placeholder="eg children's library"   v-model="location"v-form-ctrl  ><span class="md-input-bar"></span></div>                                            
                                        
                                    </div>
                                </div>
         
         <div class="parsley-row">
                                    <div class="uk-input-group">
                                        
                                        <div class="md-input-wrapper md-input-filled"><label for="wizard_email">Type of Book</label><input type="text" id="" name="type" class="md-input"   v-model="type"v-form-ctrl  ><span class="md-input-bar"></span></div>                                            
                                        
                                    </div>
                                </div>

     </div> 

              
</section>

</div>
<div class="actions clearfix "  >
    <ul aria-label="Pagination" role="menu">
        <li class="button_previous " aria-disabled="true"  v-on:click="go_to_fill_form_section()"  v-show="in_payment_section==true"  >
            <a role="menuitem" href="#previous" >
                <i class="material-icons"></i> Previous
            </a>
        </li>
        <li class="button_next button"   v-on:click="go_to_payment_section()"  aria-hidden="false" aria-disabled="false"  v-show="bookForm.$valid && in_payment_section==false"  > 
            <a role="menuitem" href="#next"  >Next 
                <i class="material-icons">
                </i>
            </a>
        </li>
        <li class="button_finish "    aria-hidden="true"  v-show="bookForm.$valid && in_payment_section==true"  >
          
         <button  v-show="bookForm.$valid"  class="md-btn md-btn-primary uk-margin-small-top client"  name="submit_order"  v-on:click="submit_form" type="button">Save</button>
        </li>
    </ul>
</div>
</div>
</form>

            <div class="uk-modal" id="confirm_modal"   >
                <div class="uk-modal-dialog"  v-el:confirm_modal>
                    <div class="uk-modal-header uk-text-large uk-text-success uk-text-center" >Confirm Data</div>
                    Are you certain of all the info
                    {{-- <div class="uk-modal-footer ">
        <center>
          <button class="md-btn md-btn-primary uk-margin-small-top" type="submit" name="submit_order" > Cancel</button>
          <button class="md-btn md-btn-primary uk-margin-small-top" type="submit" name="submit_order" > Ok</button>
          </center>
        </div> --}}
                </div>
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
   
    
 options: [      
    ],
    in_payment_section : false,
  },
  methods : {
    go_to_payment_section : function (event){
    UIkit.modal.confirm(vm.$els.confirm_modal.innerHTML, function(){
        
      vm.$data.in_payment_section=true
})

    },
    
    go_to_fill_form_section : function (event){    
      vm.$data.in_payment_section=false
    }
  }
})

</script>
<script>
                    $(document).ready(function(){
            $('.client').on('click', function(e){


            var year = $('.year').val();
                   
                    UIkit.modal.confirm("Are you sure every data is accurate?? "
                            , function(){
                            modal = UIkit.modal.blockUI("<div class='uk-text-center'>Saving data <br/><img class='uk-thumbnail uk-margin-top' src='{!! url('public/assets/img/spinners/spinner.gif')  !!}' /></div>");
                                    //setTimeout(function(){ modal.hide() }, 500) })()            
                                    $.ajax({
                                     
                                            type: "POST",
                                            url:"{{url('book/add')}}",
                                            data: $('#wizard_advanced_form').serialize(), //your form data to post goes 
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
                         //window.location.href="{{url('/books')}}";
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