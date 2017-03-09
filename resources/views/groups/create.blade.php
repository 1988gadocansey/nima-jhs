@extends('layouts.app')


@section('style')
<style>
    table td{
     border:none;   
    }
    
</style>
  
@endsection
@section('content')
 @inject('sys', 'App\Http\Controllers\SystemController')
  
<div class="uk-width-xLarge-1-10">
  <h2 class="heading_b uk-margin-bottom">Create Class Groupings</h2>
 <table class="uk-table uk-table-hover uk-table-align-vertical uk-table-nowrap " id=""> 
                                  <thead>
                                        <tr>
                                             <th class="uk-width-1-10">PROGRAMS</th>
                                             
                                            <th>LEVEL 100 </th>
                                            <th>LEVEL 200 </th>
                                            <th>LEVEL 300 </th>
                                            <th>LEVEL 400 </th>
                                            <th>BTECH TOPUP LEVEL 100  </th>
                                            <th>BTECH TOPUP LEVEL 200   </th>
 
                                        </tr>
                                    </thead>
                                    <tbody>
                                         @foreach($data as $index=> $row) 
                                     <tr>

                                         <td>{{$row->PROGRAMME}}</td>
                                         <td class=''>{{@$sys->getStudentsTotalPerProgramLevel($row->PROGRAMMECODE,'1')}}</td>
                                         <td class=''>{{@$sys->getStudentsTotalPerProgramLevel($row->PROGRAMMECODE,'2')}}</td>
                                         <td class=''>{{@$sys->getStudentsTotalPerProgramLevel($row->PROGRAMMECODE,'3')}}</td>
                                         <td class=''>{{@$sys->getStudentsTotalPerProgramLevel($row->PROGRAMMECODE,'4')}}</td>
                                        <td class=''>{{@$sys->getStudentsTotalPerProgramLevel($row->PROGRAMMECODE,'400/1')}}</td>
                                        <td class=''>{{@$sys->getStudentsTotalPerProgramLevel($row->PROGRAMMECODE,'400/2')}}</td>
                                        
                                         
                                     </tr>
                                        @endforeach
                                    </tbody>
                        </table>
            <div class="md-card">
                <div class="md-card-content">
                    <div id="wizard_vertical">

                        <h3>Create Groups</h3>
                        <section>
                            <h2 class="heading_b">
                                Get started
                                <span class="sub-heading">Create the name of the groups you are creating</span>
                            </h2>
                            <hr class="md-hr">
                              
                            <form   id="form" name="applicationForm"   accept-charset="utf-8" method="POST"  v-form>
                                <input type="hidden" name="_token" value="{!! csrf_token() !!}"> 
                                <table id="paymentTable" class=""border="0" style="font-weight:bold">
                                    <tr id="paymentRow" payment_row="payment_row"> 
                                        <td>Program &nbsp;
                                            {!! Form::select('program', 
                                            (['' => 'select program'] +$program ), 
                                            old("program",""),
                                            ['class' => 'md-input gad program','style'=>'width:200px','v-model'=>'program','v-form-ctrl'=>'','v-select'=>''] )  !!}
                                        </td>
                                        <td>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</td>
                                        <td>Level &nbsp;
                                            {{ Form::select('level', array(''=>'select level', '100'=>'HND level 100','200' => 'HND level 200', '300' => 'HND level 300','400/1'=>'BTECH level 100','400/2'=>'BTECH level 200'), null, ["required"=>"required",'class' => 'md-input','v-model'=>'level','v-form-ctrl'=>'','v-select'=>'']) }}

                                        </td>
                                         <td>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</td>
                                        <td valign="top">Group Name &nbsp;<input type="text"   class="md-input name" required=""   v-model='name' v-form-ctrl='' name="name[]" style="width:auto;"></td> 

                                        <td>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</td>
                                        <td>Total Per Group &nbsp;
                                            <input type="number" required="" class="md-input" name="total"   value="{{ old('total') }}" />
                                   
                                        </td>
                                         <td>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</td>
                                      
                                        
                                </table>
                                <table align="center">

                                    <tr><td><input type="submit" value="Create"  v-show="applicationForm.$valid"  class="md-btn save  md-btn-success uk-margin-small-top">
                                            <input type="reset" value="Cancel" class="md-btn   md-btn-danger uk-margin-small-top">
                                        </td></tr></table>
                            </form>
                        </section>
                        <h3>Assign Lectures</h3>
                        <section>
                            <h2 class="heading_b">
                                How to customize
                                <span class="sub-heading">Create your own style with the customizer.</span>
                            </h2>
                            <hr class="md-hr">
                            <p>UIkit comes with a customizer that enables you to make adjustments to the theme you are using with just a few clicks and no need for any CSS knowledge. You can then download your new CSS and even the pending Less variables, all ready to use.</p>
                            <p><span class="uk-badge uk-badge-primary">NOTE</span> To optimize performance, we recommend disabling add-ons, like Web Developer and Firebug in <code>Firefox</code></p>
                            <h3 class="heading_a">Usage</h3>
                            <p>Choose the theme that you would like to customize from the select box. Click inside a color square to open the dialog and change your color. To adjust a numeric value, like width or margin, just click in the text area and type your own value. You can even use a different unit and the customizer will recalculate automatically. Once you are satisfied with your adjustments, hit Get CSS to download your new theme and copy it into your UIkit <code>/css</code> folder.</p>
                            <h4 class="heading_c">Advanced mode</h4>
                            <p>The variables within the customizer are separated into two main parts. First, variables which are displayed by default and variables which only appear in the Advanced Mode. The visible variables are often the global ones, because they usually define the value of component variables. To see the component variables, just check the Advanced mode box.</p>
                            <h4 class="heading_c">More</h4>
                            <p>By default, variables whose value is defined through another variable are hidden. In Advanced mode you can see a (More) button next to groups that include these kinds of variables. This option is extremely powerful as it enables you to set your own value for any component variable.</p>
                        </section>
                        <h3>View Groups and Students</h3>
                        <section>
                            <h2 class="heading_b">
                                Less & Sass files
                                <span class="sub-heading">A separate Bower UIkit repository contains all distribution files including Less and Sass.</span>
                            </h2>
                            <hr class="md-hr">
                            <p>The great thing about UIkit is that you can easily integrate its source files in your project to keep your custom project workflow for building assets and stay with your preferred CSS preprocessor.</p>
                            <p>UIkit uses the package manager <a href="http://bower.io/">Bower</a> to load assets. Therefore UIkit automatically generates the distributions into a separate <a href="https://github.com/uikit/bower-uikit">Bower UIkit repository</a>. It contains all CSS, Less, SCSS and JavaScript files from UIkit and its components.</p>
                            <p class="uk-text-large">&hellip;</p>
                        </section>

                    </div>
                </div>
            </div>
</div>

@endsection

@section('js')
    <script>
       </script>

 

 
@endsection