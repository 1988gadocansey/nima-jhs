<!doctype html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="initial-scale=1.0,maximum-scale=1.0,user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Remove Tap Highlight on Windows Phone IE -->
    <meta name="msapplication-tap-highlight" content="no"/>
    <meta name="_token" content="{!! csrf_token() !!}"/>
    <link rel="icon" type="image/png" href="public/assets/img/favicon-16x16.png" sizes="16x16">
    <link rel="icon" type="image/png" href="public/assets/img/favicon-32x32.png" sizes="32x32">

    <title>SRMS - NIMASCO</title>


    <!-- uikit -->
    <link rel="stylesheet" href="{!! url('public/assets/plugins/uikit/css/uikit.almost-flat.min.css') !!} " media="all">


    <link rel="stylesheet" href="{!! url('public/assets/css/main.min.css') !!}" media="all">
    <link rel="stylesheet" href="{!! url('public/assets/css/combined.min.css') !!}" media="all">

    <link rel="stylesheet" href="{!! url('plugins/sweet-alert/sweet-alert.min.css') !!}" media="all">
    <!-- font awesome -->
    <link rel="stylesheet" href="{!! url('public/assets/css/select2.min.css') !!}" media="all">
    <link rel="stylesheet" href="{!! url('public/assets/css/dropify.css') !!}" media="all">
    <link rel="stylesheet" href="{!! url( 'public/datatables/css/jquery.dataTables.min.css')  !!}" >
    <link rel="stylesheet" href="{!! url( 'public/datatables/css/dataTables.uikit.min.css')  !!}" >
 <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"> 
 <style>
     #header_main .uk-navbar{
         margin:-1px
     }
 </style>

    @yield('style')

</head>
<body class="top_menu">
    <!-- main header -->
    <header id="header_main">
        <div class="header_main_content">
            <nav class="uk-navbar">
                <div class="main_logo_top">
                    <a href="/dashboard"><img src='{{url("public/assets/img/logo.png")}}' alt="" height="15" width="71"/></a>
                    <span  style="color:white"  >Welcome {{@Auth::user()->name }} |  Designation : {{@Auth::user()->role }}</span>
                </div>

                <!-- secondary sidebar switch -->
                <a href="#" id="sidebar_secondary_toggle" class="sSwitch sSwitch_right sidebar_secondary_check">
                    <span class="sSwitchIcon"></span>
                </a>

                <div class="uk-navbar-flip">
                    <ul class="uk-navbar-nav user_actions">
                        <li><a href="#" id="full_screen_toggle" class="user_action_icon uk-visible-large"><i class="material-icons md-24 md-light">&#xE5D0;</i></a></li>
                        <li><a href="#" id="main_search_btn" class="user_action_icon"><i class="material-icons md-24 md-light">&#xE8B6;</i></a></li>
                        <li data-uk-dropdown="{mode:'click',pos:'bottom-right'}">
                            <a href="#" class="user_action_icon"><i class="material-icons md-24 md-light">&#xE7F4;</i><span class="uk-badge">16</span></a>
                            <div class="uk-dropdown uk-dropdown-xlarge">
                                <div class="md-card-content">
                                    <ul class="uk-tab uk-tab-grid" data-uk-tab="{connect:'#header_alerts',animation:'slide-horizontal'}">
                                        <li class="uk-width-1-2 uk-active"><a href="#" class="js-uk-prevent uk-text-small">Messages (12)</a></li>
                                        <li class="uk-width-1-2"><a href="#" class="js-uk-prevent uk-text-small">Alerts (4)</a></li>
                                    </ul>
                                    <ul id="header_alerts" class="uk-switcher uk-margin">
                                        <li>
                                            <ul class="md-list md-list-addon">
                                                <li>
                                                    <div class="md-list-addon-element">
                                                        <span class="md-user-letters md-bg-cyan">ao</span>
                                                    </div>
                                                    <div class="md-list-content">
                                                        <span class="md-list-heading"><a href="pages_mailbox.html">Consequatur ut repudiandae.</a></span>
                                                        <span class="uk-text-small uk-text-muted">Et sunt qui quod aut laborum et nisi.</span>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="md-list-addon-element">
                                                        <img class="md-user-image md-list-addon-avatar" src="public/assets/img/avatars/avatar_07_tn.png" alt=""/>
                                                    </div>
                                                    <div class="md-list-content">
                                                        <span class="md-list-heading"><a href="pages_mailbox.html">Sunt sequi.</a></span>
                                                        <span class="uk-text-small uk-text-muted">Beatae quibusdam sed possimus pariatur optio repellendus.</span>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="md-list-addon-element">
                                                        <span class="md-user-letters md-bg-light-green">qi</span>
                                                    </div>
                                                    <div class="md-list-content">
                                                        <span class="md-list-heading"><a href="pages_mailbox.html">Et voluptatum ut.</a></span>
                                                        <span class="uk-text-small uk-text-muted">Est et nostrum dignissimos suscipit nihil animi voluptatem quam.</span>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="md-list-addon-element">
                                                        <img class="md-user-image md-list-addon-avatar" src="public/assets/img/avatars/avatar_02_tn.png" alt=""/>
                                                    </div>
                                                    <div class="md-list-content">
                                                        <span class="md-list-heading"><a href="pages_mailbox.html">Similique iure et.</a></span>
                                                        <span class="uk-text-small uk-text-muted">Laborum et alias veritatis accusamus sit consequatur quod.</span>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="md-list-addon-element">
                                                        <img class="md-user-image md-list-addon-avatar" src="public/assets/img/avatars/avatar_09_tn.png" alt=""/>
                                                    </div>
                                                    <div class="md-list-content">
                                                        <span class="md-list-heading"><a href="pages_mailbox.html">Repellendus consequuntur.</a></span>
                                                        <span class="uk-text-small uk-text-muted">Nesciunt et id eum quas est soluta aut et.</span>
                                                    </div>
                                                </li>
                                            </ul>
                                            <div class="uk-text-center uk-margin-top uk-margin-small-bottom">
                                                <a href="page_mailbox.html" class="md-btn md-btn-flat md-btn-flat-primary js-uk-prevent">Show All</a>
                                            </div>
                                        </li>
                                        <li>
                                            <ul class="md-list md-list-addon">
                                                <li>
                                                    <div class="md-list-addon-element">
                                                        <i class="md-list-addon-icon material-icons uk-text-warning">&#xE8B2;</i>
                                                    </div>
                                                    <div class="md-list-content">
                                                        <span class="md-list-heading">Repudiandae accusantium.</span>
                                                        <span class="uk-text-small uk-text-muted uk-text-truncate">Et fugit ipsum quia non ducimus quo.</span>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="md-list-addon-element">
                                                        <i class="md-list-addon-icon material-icons uk-text-success">&#xE88F;</i>
                                                    </div>
                                                    <div class="md-list-content">
                                                        <span class="md-list-heading">Eos earum qui.</span>
                                                        <span class="uk-text-small uk-text-muted uk-text-truncate">Accusamus nisi vitae dolorem et vel repudiandae ratione tenetur.</span>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="md-list-addon-element">
                                                        <i class="md-list-addon-icon material-icons uk-text-danger">&#xE001;</i>
                                                    </div>
                                                    <div class="md-list-content">
                                                        <span class="md-list-heading">Rerum accusantium.</span>
                                                        <span class="uk-text-small uk-text-muted uk-text-truncate">Ut voluptatem eos sapiente hic qui molestiae.</span>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="md-list-addon-element">
                                                        <i class="md-list-addon-icon material-icons uk-text-primary">&#xE8FD;</i>
                                                    </div>
                                                    <div class="md-list-content">
                                                        <span class="md-list-heading">Id molestias.</span>
                                                        <span class="uk-text-small uk-text-muted uk-text-truncate">Excepturi delectus rem doloribus quia fugiat.</span>
                                                    </div>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </li>
                        <li data-uk-dropdown="{mode:'click',pos:'bottom-right'}">
                            <a href="#" class="user_action_image"><img class="md-user-image" style="height: auto;width: 30px" src='{{url("/public/albums/profile/gad.JPG")}}' alt=""/></a>
                            <div class="uk-dropdown uk-dropdown-small">
                                <ul class="uk-nav js-uk-prevent">
                                  
                                     <li><a href='{!! url("/change_password") !!}'>Change Password</a></li>
                             <li><a href='{!! url("/logout") !!}'>Logout</a></li>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
        <div class="header_main_search_form">
            <i class="md-icon header_main_search_close material-icons">&#xE5CD;</i>

        </div>
    </header><!-- main header end -->
    <div id="top_bar">
        <div class="md-top-bar">
             <ul id="menu_top" class="uk-clearfix">
            <li class="uk-hidden-small"><a href='{!! url("/dashboard") !!}'><i title='home'class="material-icons">&#xE88A;</i><span>Home</span></a></li>
            
            <li data-uk-dropdown class="uk-hidden-small">

                  
                    <a href="#"><i class="sidebar-menu-icon material-icons md-18">school</i><span>Administration Module</span></a>
                    <div class="uk-dropdown uk-dropdown-scrollable">
                        <ul class="uk-nav uk-nav-dropdown">
                            <li><a href='{!! url("/students") !!}'>Students</a></li>
                           @if( @Auth::user()->department=='top' )
                             <li><a href='{!! url("/upload/students") !!}'>Upload bulk Student data</a></li>
                          
                            
                          
                              
                                  <li><a href='{!! url("/department/add") !!}'>Create Departments</a></li>
                            <li><a href='{!! url("/departments") !!}'>View Departments</a></li>
                          
                               <li><a href='{!! url("/add_students") !!}'>Add Students</a></li>
                               <li><a href='{!! url("/create_grade") !!}'>Create Grading System</a></li>
                               <li><a href='{!! url("/grade_system") !!}'>View Grading Systems</a></li>
                                  <li><a href='{!! url("/calender") !!}'>Academic Calender</a></li>
                                <li><a href='{!! url("/create_programme") !!}'>Add Programmes</a></li>
                                 <li><a href='{!! url("/programmes") !!}'>View Programmes</a></li>
                                  <li><a href='{!! url("/house/create") !!}'>Create Houses</a></li>
                                <li><a href='{!! url("/house/view") !!}'>View Houses</a></li>
                 
                               @endif
                        </ul>
                    </div>
                </li>
                        
            @if(@Auth::user()->role=='Dean' || @Auth::user()->role=='Registrar'|| @Auth::user()->role=='HOD'  || @Auth::user()->role=='Support' || @Auth::user()->role=='Lecturer' || @Auth::user()->department=='top')
                <li data-uk-dropdown class="uk-hidden-small">
                    <a href="#"><i class="sidebar-menu-icon material-icons md-18">book</i><span>Academics Modules</span></a>
                    <div class="uk-dropdown uk-dropdown-scrollable">
                        <ul class="uk-nav uk-nav-dropdown">
                            @if( @Auth::user()->role=='Lecturer')
                            <li><a href='{!! url("/registered_courses") !!}'>My Class Lists</a></li>
                           
                            <li><a href='{!! url("/upload/marks") !!}'>Upload Marks from Excel </a></li>
                             
                              <li><a href='{!! url("/broadsheet/noticeboard") !!}'>Print Broadsheet</a></li>
                            
                          @elseif( @Auth::user()->role=='Admin' || @Auth::user()->department=='top' || @Auth::user()->role=='HOD')
                          
                            <li><a href='{!! url("/classes/") !!}'>View Classes</a></li>
<!--                            <li><a href='{!! url("/create_course") !!}'>Add Courses</a></li>-->
                            <li><a href='{!! url("/courses") !!}'>View Courses</a></li>
                            <li><a href='{!! url("/upload/courses") !!}'>Upload Bulk Courses</a></li>
                            <li><a href='{!! url("/teachers/subject/create") !!}'>Create Subject Allocations</a></li>
                          
                            <li><a href='{!! url("/teachers/subject/allocation") !!}'>View Subject Allocations</a></li>
                          
                            <li><a href='{!! url("/registered_courses") !!}'>Class Lists</a></li>
                              <li><a href='{!! url("/upload/marks") !!}'>Upload Marks from Excel </a></li>
                         
                             
                            <li><a href='{!! url("/mount_course") !!}'>Mount Courses for term</a></li>
                           
                            <li><a href='{!! url("/mounted_view") !!}'>View Mounted Courses</a></li>
                            <li><a href='{!! url("/attendanceSheet") !!}'>Print Exam Attendance Sheet</a></li>
                            <li><a href='{!! url("/attendanceSheet") !!}'>Print Report Cards</a></li>
                           
                            <li><a href='{!! url("/transcript") !!}'>Transcript</a></li>
                             <li><a href='{!! url("/broadsheet/noticeboard") !!}'>Broadsheet Noticeboard</a></li>
                             
                          @endif
                             
                               </ul>
                    </div>
                </li>
                @endif
             @if( @Auth::user()->department=='Finance' || @Auth::user()->department=='top')
                        @if(@Auth::user()->role=='FO')
                          <li data-uk-dropdown="justify:'#top_bar'">
                               <a href="#"> <i class="sidebar-menu-icon material-icons">work</i><span>Financial Reporting </span></a>
                              <div class="uk-dropdown uk-dropdown-width-4">
                    <div class="uk-grid uk-dropdown-grid" data-uk-grid-margin>
                        <div class="uk-width-1-4">
                            <ul class="uk-nav uk-nav-dropdown uk-panel">
                               <li class="uk-nav-header">Settings</li>
                                           <li><a href='{!! url("/upload_students") !!}'>Upload bulk Student data</a></li>
                                           <li><a href='{!! url("/upload_applicants") !!}'>Upload bulk Applicants data</a></li>
                                           <li><a href='{!! url("create_bank") !!}'>Create Banks</a></li>
                                           <li><a href='{!! url("banks") !!}'>View Banks</a></li>
                                           <li><a href='{!! url("create_fees") !!}'>Create Fees</a></li>
                                           <li><a href='{!! url("upload_fees") !!}'>Upload Summaried Fees</a></li>
                                           <li><a href='{!! url("/uploadDetailFees") !!}'>Upload Fee Components</a></li>
                                            <li><a href='{!! url("/finance/protocol") !!}'>Protocols/Policies</a></li>
                                            
                                             <li class="uk-nav-divider"></li>
                                             

                            </ul>
                        </div>
                              <div class="uk-width-1-4">
                            <ul class="uk-nav uk-nav-dropdown uk-panel">
                                
                                           <li class="uk-nav-header">Transactions</li>
                                           <li><a href='{!! url("pay_fees") !!}'>Pay Fees</a></li>
                                           <li><a href='{!! url("/print/receipt") !!}'>Print Fee Receipt</a></li>
                                            <li><a href='{!! url("view_payments") !!}'>Payments Ledger</a></li>

                            </ul>
                        </div>
                        <div class="uk-width-1-4">
                            <div class="uk-accordion" data-uk-accordion="{showfirst:false}">
                                  <p class="uk-nav-header">Reports</p>
                               <h3 class="uk-accordion-title">Fees</h3>
                                <div class="uk-accordion-content">
                                    <p><a href='{!! url("/finance/reports/fees/") !!}'>View Fees</a></p>
                                </div>
                                <h3 class="uk-accordion-title">Summarized Reports</h3>
                                <div class="uk-accordion-content">
                                    <p><a href='{!! url("view_payments_master") !!}'>Fee Payment by Students  Report</a></p>
                                    <p><a href='{!! url("finance/reports/programs") !!}'>Fee Payment by Programs Report</a></p>
                                      
                                   <p><a href='{!! url("/owing_paid") !!}'>Fees Owings By Students Reports</a></p>
                                     <p><a href='{!! url("/finance/reports/owing") !!}'>Fees Owings By Programs Reports</a></p>
                                
                                 <p><a href='{!! url("finance/reports/ledger/student") !!}'>Print Student Ledger</a></p>
                               
                                </div>
                                <h3 class="uk-accordion-title">Cummulative Reports</h3>
                                <div class="uk-accordion-content">
                                     <p><a href='{!! url("finance/reports/cummulative") !!}'> Fees Cummulative Reports by Program</a></p>
                               
                                
                                </div>
                            </div>
                        </div>
                       
                    </div>
                </div>
            </li>
                           @else
                           <li data-uk-dropdown class="uk-hidden-small">
                               <a href="#"> <i class="sidebar-menu-icon material-icons">work</i><span>Finance Module</span></a>
                               <div class="uk-dropdown uk-dropdown-scrollable">
                                   <ul class="uk-nav uk-nav-dropdown">
                                          <li><a href='{!! url("/finance/reports/fees/") !!}'>View Fees</a> <li>
                                       <li><a href='{!! url("pay_fees") !!}'>Pay Fees</a></li>
                                       <li><a href='{!! url("/print/receipt") !!}'>Print Fee Receipt</a></li>
                                       <li><a href='{!! url("/finance/protocol") !!}'>Protocols/Policies</a></li>

                                       <li><a href='{!! url("pay_transcript") !!}'>PayTranscript printing</a></li>
                                       <li><a href='{!! url("pay_fees_penalty") !!}'>Late Registration payment</a></li>
                                       <li><a href='{!! url("view_payments") !!}'>Payments Transactions</a></li>
                                   </ul>
                               </div>
                           </li>
                           @endif
                @endif
                 @if( @Auth::user()->role=='FO' || @Auth::user()->department=='top' || @Auth::user()->role=='Registrar')
                <li data-uk-dropdown class="uk-hidden-small">
                    <a href="#"> <i class="sidebar-menu-icon material-icons">work</i><span>Staff Module</span></a>
                    <div class="uk-dropdown uk-dropdown-scrollable">
                        <ul class="uk-nav uk-nav-dropdown">
                              <li><a href='{!! url("/workers") !!}'>Staffs</a></li>
                           <li><a href='{!! url("/workers/add") !!}'>Add Staff</a></li>
                           
                           
                            <li><a href='{!! url("/directory") !!}'>Staff Directory</a></li>
                          
                            
                        </ul>
                    </div>
                </li>
                @endif
                 
                 @if(@Auth::user()->role=='Liberian' ||  @Auth::user()->department=='top')
             
                <li data-uk-dropdown class="uk-hidden-small">
                    <a href="#"> <span class="menu_icon"><i class="material-icons">account_balance</i></span><span>Library</span></a>
                    <div class="uk-dropdown uk-dropdown-scrollable">
                        <ul class="uk-nav uk-nav-dropdown">
                               <li><a href='{!! url("/book/add") !!}'>Add Books</a></li>
                            <li><a href='{!! url("/book/upload") !!}'>Upload Books</a></li>
                            <li><a href='{!! url("/books") !!}'>View Books</a></li>
                            <li><a href='{!! url("/books/discharge") !!}'>Discharged Books</a></li>
                            <li><a href='{!! url("/books/charge") !!}'>Charged Books</a></li>
                             <li><a href='{!! url("/books/borrow") !!}'>Borrow Book</a></li>
                             <li><a href='{!! url("/books/return") !!}'>Return Book</a></li>
                                <li><a href='{!! url("/transactions") !!}'>Transactions Log</a></li>
                            
                        </ul>
                    </div>
                </li>
                 @endif
                 @if( @Auth::user()->role=='Admin')
                <li data-uk-dropdown class="uk-hidden-small">
                    <a href="#"> <span class="menu_icon"><i class="material-icons">&#xE8C0;</i></span><span>Settings</span></a>
                    <div class="uk-dropdown uk-dropdown-scrollable">
                        <ul class="uk-nav uk-nav-dropdown">
                            <li><a href='{!! url("/search_password") !!}'>Search student password</a></li>
                              <li><a href='{!! url("/power_users") !!}'>View Users</a></li>
                               <li><a href='{!! url("/systems/synchronizations") !!}'>System Synchronizations</a></li>
                            <li><a href='{!! url("/systems/view") !!}'>System Settings</a></li>
                            <li><a href='{!! url("/systems/sms") !!}'>Sms Logs</a></li>
                            <li><a href='{!! url("/systems/user/logs") !!}'>User Logs</a></li>
                            
                             <li><a href='{!! url("/logs") !!}'>System Error Logs</a></li>

                             <li><a href='{!! url("/change_password") !!}'>Change Password</a></li>
                             <li><a href='{!! url("/logout") !!}'>Logout</a></li>
                        </ul>
                    </div>
                </li>
                @endif
                  @if( @Auth::user()->role!='Admin')
                 <li data-uk-dropdown class="uk-hidden-small">
                    <a href="#"> <span class="menu_icon"><i class="material-icons">lock</i></span><span>My Account</span></a>
                    <div class="uk-dropdown uk-dropdown-scrollable">
                        <ul class="uk-nav uk-nav-dropdown">
                                        <li><a href='{!! url("/change_password") !!}'>Change Password</a></li>
                                       <li><a href='{!! url("/logout") !!}'>Logout</a></li>
                             
                                
                        </ul>
                    </div>
                </li>
                @endif
            <li data-uk-dropdown="justify:'#top_bar'" class="uk-visible-small">
                <a href="#"><i class="material-icons">&#xE5D2;</i><span>Menu</span></a>
                <div class="uk-dropdown uk-dropdown-width-2">
                    <div class="uk-grid uk-dropdown-grid" data-uk-grid-margin>
                        <div class="uk-width-1-2">
                            <ul class="uk-nav uk-nav-dropdown ">
                          
                             <li><a href='{!! url("/logout") !!}'>Logout</a></li>
                       
                             
                            
                            </ul>
                        </div>
                      
                    </div>
                </div>
            </li>
        </ul>
        </div>
    </div>

    <div id="page_content">
        <div id="page_content_inner">




            @yield('content')


        </div>
    </div>
    <br/>
            <div class="footer uk-text-small"><center><?php date("Y");?> All Rights Reserved | NIMASCO - Powered by GADEKSYSTEMS - 0505284060 </center></div>
      
           
    <!-- common functions -->
    <script src="{!! url('public/assets/js/common.min.js') !!}"></script>
     <!-- uikit functions -->
    <script src="{!! url('public/assets/js/uikit_custom.min.js') !!}"></script>

    <!-- altair common functions/helpers -->
    <script src="{!! url('public/assets/js/altair_admin_common.min.js') !!}"></script>
     <!--  contact list functions -->
    <script src='{{url("public/assets/js/pages/page_contact_list.min.js")}}'></script>
 
    
</body>
</html>