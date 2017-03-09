@extends('layouts.app')

 
@section('style')
       @inject('obj', 'App\Http\Controllers\SystemController')
@endsection
 @section('content')
   <div class="md-card-content">
@if(Session::has('success'))
            <div style="text-align: center" class="uk-alert uk-alert-success" data-uk-alert="">
                {!! Session::get('success') !!}
            </div>
 @endif
 
    @if(Session::has('error'))
            <div style="text-align: center" class="uk-alert uk-alert-danger" data-uk-alert="">
                {!! Session::get('error') !!}
            </div>
 @endif
 @if (count($errors) > 0)

    <div class="uk-form-row">
        <div class="uk-alert uk-alert-danger" style="background-color: red;color: white">

              <ul>
                @foreach ($errors->all() as $error)
                  <li> {{  $error  }} </li>
                @endforeach
          </ul>
    </div>
  </div>
@endif
  </div>
  
 </div> 
 <h4 class="heading_c uk-margin-bottom">Reset Account Password</h4>
 
 <div class="uk-width-large-8-10" style="margin-left: 50px">
    <div class="md-card">
        <div class="md-card-content">
             <form action="" method="post" class="form-horizontal row-border"   id="form" data-validate="parsley" >
             <div class="uk-grid" data-uk-grid-margin>
                  <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                        <div class="uk-width-medium-1-2">
                            <div class="uk-form-row">
                                <div class="uk-grid">
                                    <div class="uk-width-medium-1-2">
                                        <label> User Name</label>
                                        <input type="text" class="md-input" name="username" required="" value=""  />
                                    </div>

                                    <div class="uk-width-medium-1-2">
                                        <label>Old Password</label>
                                        <input type="text" class="md-input" name="username" required=""   value=""/>
                                    </div>
                                 
                                </div>
                                 <div class="uk-grid">
                                    <div class="uk-width-medium-1-2">
                                        <label> User Name</label>
                                        <input type="text" class="md-input" name="username" required="" value=""  />
                                    </div>

                                    <div class="uk-width-medium-1-2">
                                        <label>Old Password</label>
                                        <input type="text" class="md-input" name="username" required=""   value=""/>
                                    </div>
                                 
                                </div>
                            
                            </div>
                        </div>
                    <div class="uk-width-medium-1-2">
                      
                            <div class="uk-form-row">
                                <div class="uk-grid">
                                    
                                    
                                    <div class="uk-width-medium-1-2">
                                        <label> New Password</label>
                                        <input type="text" class="md-input" name="confirm" required=""   value=""/>
                                    </div>
                                
                                <div class="uk-width-medium-1-2">
                                    <label> Email</label>
                                       
                                       <input type="email" class="md-input" name="email" value="" required="" />
                                </div>
                                </div>
                            </div>
                                 
                           
                        </div>
                     
                    </div>
                     
              

                 <div class="uk-grid" align='center'>
                            <div class="uk-width-1-1">
                                <button type="submit" class="md-btn md-btn-primary"><i class=" "></i>update</button>
                            </div>
                        </div>
        </form>
 
 </div>
    </div>
 </div>
@endsection
@section('js')
  
<script>
    
 
 var oTable = $('#gad').DataTable({
     
        
        processing: true,
        serverSide: true,
        ajax: {
            url:  "{!! route('power_users.data') !!}"
             
        },
        columns: [
           
        
          {data: 'id', name: 'users.id'},
           {data: 'staffID', name: 'tpoly_workers.staffID'},
           
            {data: 'Photo', name: 'Photo', orderable: false, searchable: false},
            
              {data: 'name', name: 'users.name'},
               {data: 'email', name: 'users.email'},
            {data: 'department', name: 'users.department'},
            {data: 'role', name: 'users.role'},]
              
    });
    

    
</script>
 
@endsection