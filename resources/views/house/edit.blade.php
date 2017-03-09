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
 <h5 class="heading_a">Editing House..</h5>
<div class="uk-width-xLarge-1-10">
    <div class="md-card">
        <div class="md-card-content" style="">

           
            <form    id="form" accept-charset="utf-8" method="POST" name="applicationForm"  v-form>
                <input type="hidden" name="_token" value="{!! csrf_token() !!}"> 
                 <table id="paymentTable" class="uk-table"border="0" style="font-weight:bold">
	  <tr id="paymentRow" payment_row="payment_row"> 
               
              <td valign="top">House Name &nbsp;<input type="text"  v-model='name' v-form-ctrl=''   class="md-input md-input" value="{{$data->house}}" name="name" style=""></td>
 
            <td>House Master/Mistress &nbsp;
	   {!! Form::select('staff', 
                                (['' => 'select staff'] +$staff ), 
                                  old("staff",""),
                                    ['class' => 'md-input gad','style'=>'','v-model'=>'staff','v-form-ctrl'=>'','v-select'=>''] )  !!}
	  </td>
          
	    
      </table>
      <table align="center">
       
        <tr><td><input type="submit" value="Save" id='save'v-show="applicationForm.$valid"  class="md-btn   md-btn-success uk-margin-small-top">
      <input type="reset" value="Cancel" class="md-btn   md-btn-default uk-margin-small-top">
    </td></tr></table>
            </form>
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
@endsection