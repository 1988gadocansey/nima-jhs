@extends('layouts.printlayout')

@section('content')
@inject('help', 'App\Http\Controllers\SystemController')
<div align="center"  >
    <style>
     
    #report{
       
        border: #ff9900;
         border-style: solid;
         display: table;
        border-collapse: separate;
        border: solid 1px #98BF21;
        line-height: 1.4em;
        border-collapse:separate;
    }
    
</style>
<style>
    td{
        font-size: 13px
    }
    .biodata{
        border-collapse: collapse;
    border-spacing: 0;
    
    margin-bottom: 15px;
    }
    .biodata td{
        padding:4px;
    }
    .uk-table {
    border-collapse: collapse;
    border-spacing: 0;
    margin-bottom: 15px;
    width:926px;
}


/*.uk-table td{
    border:none;
}
.uk-table th{
    border-collapse: collapse
}*/
        </style>
                 
               

                    {{$student}}
                    
                    {{$grade}}
                    
                    
               

</div>
        @endsection

        @section('js')
        <script type="text/javascript">

         //window.print();
 

        </script>

        @endsection