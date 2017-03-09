@extends('layouts.printlayout')
<style>
    
</style>
@inject('sys', 'App\Http\Controllers\SystemController')
<link rel="stylesheet" href="{!! url('public/assets/css/print.css') !!}" media="all">  
<style>
    html, body, #page3,  #page4, #page5 { float: none; }

   @media print
{
    table {float: none !important; }
  div { float: none !important; }
   #page3  { page-break-inside: avoid; page-break-before: always; }
   #page4  { page-break-inside: avoid; page-break-before: always; }
}
     
@page {
  size: A4;
}
 
table, figure {
  page-break-inside: avoid;
}
fieldset legend {
  page-break-before: always;
}
h1, h2, h3, h4, h5 {
  page-break-after: avoid;
}
.biodata{
        padding: 1px;
    }
    body{
        background: none;
    }
    .uppercase{
        font-size: 12px;
        text-align: right;
        font-weight: bolder;
    }
  td{
        font-size: 13px
    }
    .folder table{
        border-collapse: collapse;
    border-spacing: 0;
    
    margin-bottom: 15px;
    }
    .folder td{
        padding:4px;
    }
.folder table {
   border-collapse: collapse;
    border-spacing: 0;
    margin-bottom: 15px;
    
}
.watermark {
 
  display: block;
  position: relative;
}

.watermark::after {
  content: "";
 background:url(http://srms.tpolyonline.com/public/logins/images/logo.png);
  opacity: 0.2;
  top: 0;
  left: 30;
  bottom: 0;
  right: 0;
  position: absolute;
  z-index: -1;   
   background-size: contain;
  content: " ";
  display: block;
  position: absolute;
  height: 100%;
  width: 100%;
  background-repeat: no-repeat;
}
</style>
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="card">
       
            @if(Session::has('success'))
             <div class="card-panel light-green lighten-3">
            <div style="text-align: center" class=" white-text alert  alert-success"   >
                {!! Session::get('success') !!} <a href="{{url('form/step2')}}">Click to Move to Next Step</a>
            </div></div>
            @endif
             @if(Session::has('error'))
             <div class="card-panel red">
            <div style=" " class=" white-text alert  alert-danger"  >
                {!! Session::get('error') !!}
            </div></div>
            @endif

            @if (count($errors) > 0)

             <div class="card-content blue-grey">
                <div class=" alert  alert-danger  " style="background-color: red;color: white">

                    <ul>
                        @foreach ($errors->all() as $error)
                        <li> {{  $error  }} </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif
             <a  onclick="javascript:printDiv('print')" class="md-btn md-btn-flat md-btn-flat-primary md-btn-wave">Click to print form</a>
        
        </div> 
           
         
              
     
            
          <center><span class="heading_c uk-text-upper"> Personal Records of {{  strtoupper($data->name)}}</span></center>
          <hr>
              <div class="panel-body">
                  <div id='print'>
                
                    <div id='page1'>  
                        <table border='0'>
                            <tr>
                                <td> <img src='{{url("public/assets/img/logo.jpg")}}' style="width:220px;height: auto"  class="image-responsive"/> 
                                        
                                </td>
                                 
                                <td align='right' style="width:600px">
                                        <p style="font-size:14px">GHANA LIBRARY LIBRARY BOARD<br/>
                                          KUMASI METROPOLITAN LIBRARY  <br/>
                                            TEL:+233-031-2022917/8<br/>
                                            EMAIL:info@kml.org<br/>
                                            P.O.BOX 256,KUMASI,GHANA
                                        
                                        
                                        
                                </td>
                            </tr>
                        </table>
                        <hr>
                         <center>LIBRARY CARD NO - {{@$data->cardNo}}   </center>
                         <hr>
                     
                         <fieldset><legend style="background-color:#1A337E;color:white;">BIODATA INFORMATION</legend>
                            <table class=''><tr>

                                    <td>
                                        <table   class="folder table" >
                                            <tr>
                                                <td width="210" class="uppercase" align="right"><span>TITLE:</span></td>
                                                <td width="408" class="capitalize">{{ $data->title }}</td>								
                                            </tr>
                                            <tr>
                                                <td width="210" class="uppercase" align="right"><span>SURNAME:</span></td>
                                                <td width="408" class="capitalize">{{ strtoupper($data->lastname) }}</td>								
                                            </tr>
                                            <tr>
                                                <td width="210" class="uppercase" align="right"><span>FIRST NAME:</span></td>

                                                <td width="408" class="capitalize">{{ $data->firstname }}</td>
                                            </tr>
                                            <tr>
                                                <td class="uppercase" style=""align="right"><span>OTHERNAMES:</span></td>
                                                <td class="capitalize"><?php echo strtoupper($data->othernames) ?></td>
                                            </tr>
                                            <tr>
                                                <td class="uppercase" align="right"><span>GENDER:</span></td>
                                                <td class="capitalize"><?php echo strtoupper($data->gender) ?></td>
                                            </tr>
                                            <tr>
                                                <td class="uppercase" align="right"><span>DATE OF BIRTH</span>:</td>
                                                <td class="capitalize"><?php echo $data->dob ?></td>
                                            </tr>
                                             

                                            <tr>
                                                <td class="uppercase" align="right"><span>PHONE:</span></td>
                                                <td class="capitalize"><?php echo "+233" . \substr($data->contact, -9); ?></td>
                                            </tr>

                                           

                 
                                        </table>

                                    </td>

                                    <td valign="top" >
                                        <img   style="width:190px;height:179px  "  <?php
                                        $pic = $data->cardNo;
                                      
                                        ?>   src='{{url("public/albums/members/$pic.jpg")}}' alt="  Affix member picture here"    />
                                    </td>
                                    <td>
                                
                                </td>

                                </tr>
                            </table>
                           </fieldset>
                         <p>&nbsp;</p>
                              <fieldset><legend  style="background-color:#1A337E;color:white;">OTHER INFORMATION</legend>
                             <table  class="folder table">
                                <tr>
                                    <td>
                                        <table >
                                            <tr>
                                               <td class="uppercase" ><strong>HOMETOWN:</strong></td>
                                                <td class="capitalize">{!! strtoupper($data->hometown) !!}</td>

                                            </tr>
                                            <tr>
                                               <td class="uppercase" style=""><strong>RESIDENTIAL ADDRESS:</strong></td>
                                                <td class="capitalize">{!! strtoupper($data->address) !!}</td>

                                            </tr>
                                            <tr>
                                                <td class="uppercase"><strong>HOUSE NO:</strong></td>
                                                <td class="capitalize">{!!strtoupper( $data->houseno) !!}</td>
                                                
                                            </tr>
                                            <tr>
                                                 <td class="uppercase"><strong>RELIGION:</strong></td>
                                                <td class="capitalize">{!! strtoupper($data->religion) !!}</td>
                                               
                                            </tr>

                                        </table>
                                    </td>
                                    @if($data->category=="CHILDREN")
                                    <td>
                                        <table>
                                            <tr>
                                                  <td class="uppercase"  ><strong>PARENT NAME:</strong></td>
                                                <td class="capitalize">{!! strtoupper($data->parent) !!}</td>

                                            </tr>
                                            <tr>
                                                 <td class="uppercase"  ><strong>PARENT PHONE:</strong></td>
                                                <td class="capitalize">{!! strtoupper($data->parent_phone) !!}</td>

                                            </tr>
                                            <tr>
                                                 <td class="uppercase"><strong>SCHOOL:</strong></td>
                                                <td class="capitalize">{!! strtoupper($data->school) !!}</td>

                                                
                                            </tr>
                                            <tr>
                                                 <td class="uppercase"><strong>CLASS:</strong></td>
                                                <td class="capitalize">{!! strtoupper($data->class )!!}</td>

                                            </tr>

                                        </table>
                                    </td>@endif
                                </tr>


                            </table>
                               </fieldset> 
                    </div>
                       <p>&nbsp;</p>
                      <div id='page2'>
                          
                          
                      <fieldset><legend style="background-color:#1A337E;color:white;">BOOK BORROWING TRANSACTIONS</legend>
                             <table class="folder table">
                               


                            </table>
                    </fieldset>
                        
                  
                         
                             </div>
                        
 
    </div>
</div>
    </div>
</div>
@endsection
<script>
    $(document).ready(function(){
        // Wrap each tr and td's content within a div
        // (todo: add logic so we only do this when printing)
        $("table tbody th, table tbody td").wrapInner("<div></div>");
    })
</script>
<script>
$('#final').click(function(){
     /* when the submit button in the modal is clicked, submit the form */
    alert('Finalizing and submitting form to the University wait....');
    $('#formfield').submit();
});

</script>