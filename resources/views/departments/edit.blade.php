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
<h5 class="heading_b">Edit Departments here</h5>
<td class="uk-text-center">
                    
                      {!!Form::open(['action' => ['DepartmentController@destroy', 'id'=>$data->id], 'method' => 'DELETE','name'=>'myform' ,'style' => 'display: inline;'])  !!}

                                                   <i onclick="UIkit.modal.confirm('Are you sure you want to delete this item?', function(){ document.forms[0].submit(); });" title="click to delete this" class="sidebar-menu-icon material-icons md-18 uk-text-danger">delete</i>
                                                        <input type='hidden' name='item' value='{{$data->ID}}'/>  
                                                     {!! Form::close() !!}
                 
                </td>
<div class="uk-width-xLarge-1-10">
    <div class="md-card">
        <div class="md-card-content" style="">

            
            <form    id="form" accept-charset="utf-8" method="POST" name="applicationForm"  v-form>
                <input type="hidden" name="_token" value="{!! csrf_token() !!}"> 
                 <table id="paymentTable" class="uk-table"border="0" style="font-weight:bold">
	  <tr id="paymentRow" payment_row="payment_row"> 
            
              <td valign="top">Department Name &nbsp;<input type="text"   class="md-input md-input name" required="" value="{{$data->name}}"  v-model='name' v-form-ctrl='' name="name" style="width:auto;"></td>

          <input type="hidden" name="id" value="{{$data->id}}"/>
	    
      </table>
      <table align="center">
       
          <tr><td><input type="button" value="Save"  v-show="applicationForm.$valid"  class="md-btn create  md-btn-success uk-margin-small-top">
      <input type="reset" value="Cancel" class="md-btn   md-btn-default uk-margin-small-top">
    </td></tr></table>
            </form>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
                    $(document).ready(function(){
            $('.create').on('click', function(e){
  

            var name = $('.name').val();
              
                    UIkit.modal.confirm("Are you sure you want to edit " + name + " department ?" 
                            , function(){
                            modal = UIkit.modal.blockUI("<div class='uk-text-center'>Ok updating " + name + " department ..... <br/><img class='uk-thumbnail uk-margin-top' src='{!! url('public/assets/img/spinners/spinner.gif')  !!}' /></div>");
                                    //setTimeout(function(){ modal.hide() }, 500) })()            
                                    $.ajax({
                                     
                                            type: "POST",
                                            url:"{{ url('/department/update')}}",
                                           data: $('#form').serialize(),
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
                         window.location.href="{{url('/departments')}}";
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