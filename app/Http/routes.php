<?php

/*
  |--------------------------------------------------------------------------
  | Routes File
  |--------------------------------------------------------------------------
  |
  | Here is where you will register all of the routes in an application.
  | It's a breeze. Simply tell Laravel the URIs it should respond to
  | and give it the controller to call when that URI is requested.
  |
 */

/*
  |--------------------------------------------------------------------------
  | Application Routes
  |--------------------------------------------------------------------------
  |
  | This route group applies the "web" middleware group to every route
  | it contains. The "web" middleware group is defined in your HTTP
  | kernel and includes session state, CSRF protection, and more.
  |
 */

Route::group(['middleware' => ['web']], function () {
 Route::auth();
    Route::get('/', function () {
        return view('auth/login');
    })->middleware('guest');
    Route::post('missHapp', 'LostPasswordController@sendNewPassword');
    Route::get('/lock', function () {
        return view('auth/screenLock');
    });
    
    Route::controller('search_password', 'PasswordController', [
        'anyData' => 'search_password.data',
        'getIndex' => 'search_password',
    ]);
     Route::controller('power_users', 'UserController', [
        'anyData' => 'power_users.data',
        'getIndex' => 'power_users',
    ]);
    
   Route::get('dashboard', 'HomeController@index');
  Route::get('/change_password', 'PasswordController@showChange');
    Route::post('/change_password', 'PasswordController@reset');
    
    
    Route::get('laracharts', 'HomeController@getLaraChart');
      Route::get('/graph', 'HomeController@buildChart');
    // student routes
    Route::controller('students', 'StudentController', [
        'anyData' => 'students.data',
        'getIndex' => 'students',
    ]);
    Route::get('students', 'StudentController@index');
    Route::post('/sms', 'StudentController@sms');
    Route::get('/add_students', 'StudentController@create');
    Route::get('/upload/students', 'StudentController@showUploadForm');
    Route::post('/upload/students', 'StudentController@uploadData');
    Route::post('/create_account', 'PasswordController@createStudentAccount');
    
    Route::post('/add_students', 'StudentController@store');
    Route::get('/student_show/{id}/id', 'StudentController@show'); // for printout
    Route::get('/edit_student/{id}/id', 'StudentController@edit');
    Route::post('/edit_student/{id}/id', 'StudentController@update');
    
    // routes for learning
    Route::get('autocomplete', 'SearchController@index');
    Route::get('clone', function () {
        return view('clone');
    });

    // domentories
     Route::match(array("get", "post"), '/domentories','DomentoriesController@process');
     
    Route::get('/domService', 'DomentoriesController@listDoms');
    
    Route::controller('/banks', 'BankController', [
        'anyData' => 'banks.data',
        'getIndex' => 'banks',
        
    ]);
    Route::get('/create_bank', 'BankController@form');
    Route::post('/create_bank', 'BankController@store');
    Route::get('/edit_bank/{id}/id', 'BankController@edit');
    Route::post('/edit_bank/{id}/id', 'BankController@update');
    
    // fees route
    Route::get('/view_fees', 'FeeController@getIndex');
    Route::get('/create_fees', 'FeeController@createform');
    Route::post('/create_fees', 'FeeController@store');
    Route::get('/upload_fees', 'FeeController@showUpload');
    Route::post('/upload_fees', 'FeeController@uploadStudentsFee');
    Route::delete('/delete_fees', 'FeeController@destroy');
     
    Route::get('/run_bill/{id}/id', 'FeeController@approve');
     
    Route::get('/pay_fees', 'FeeController@showPayform');
    Route::post('/pay_fees', 'FeeController@showStudent');
    // late fee payment ie fee penalty
    Route::get('/pay_fees_penalty', 'FeeController@showPayform');
    Route::post('/pay_fees_penalty', 'FeeController@showStudentPenalty');
    Route::post('/processPayment', 'FeeController@processPayment');
    Route::delete('/delete_payment', 'FeeController@destroyPayment');
    
    Route::get('/printreceipt/{receiptno}', 'FeeController@printreceipt');
    Route::get('/printreceiptLate/{receiptno}', 'FeeController@printreceiptLate');
    Route::match(array("get", "post"), '/uploadDetailFees','FeeController@uploadFeesComponent');
     Route::match(array("get", "post"), '/print/receipt','FeeController@printOldReceipt');
      Route::match(array("get", "post"), '/finance/protocol','FeeController@allowRegister');
       Route::post('/processProtocol', 'FeeController@processProtocol');
   

   /* Route::controller('/view_payments', 'PaymentController', [
        'anyData' => 'view_payments.data',
        'getIndex' => 'view_payments',
        
    ]);*/
     Route::get('/view_payments', 'PaymentController@payments');
     Route::get('/view_payments_master', 'FeeController@masterLedger');
     // this route will process both get and post so am using route match
     Route::match(array("get", "post"), '/fee_summary', "FeeController@feeSummary");
     Route::get('/owing_paid', 'FeeController@owingAndPaid');
     Route::post('/fireOwingSMS', 'FeeController@sendFeeSMS');
    Route::get('search/autocomplete', 'SearchController@autocomplete');
    
    
    // updating students levels based on indexno
    Route::get('/gad', 'StudentController@gad');
    Route::post('/gad', 'StudentController@updateLevel');
    
    
    
    // load staff csv -STAFF ROUTES
    Route::controller('/staff', 'StaffController', [
        'anyData' => 'staff.data',
        'getIndex' => 'staff',
        
    ]);
    Route::get('/getStaffCSV', 'StaffController@showFileUpload');
    Route::post('/uploadStaff', 'StaffController@uploadStaff');
    Route::get('/directory', 'StaffController@directory');
    Route::get('/add_staff', 'StaffController@create');
    Route::post('/add_staff', 'StaffController@store');
    Route::post('/power_users', 'UserController@createStaffAccount');
    
    
    // Academic routes
    Route::controller('programmes', 'ProgrammeController', [
        'anyData' => 'programmes.data',
        'getIndex' => 'programmes',
    ]);
    Route::get('/create_programme','ProgrammeController@create');
    Route::post('/create_programme','ProgrammeController@store');
    
    Route::get('/edit_programme/{id}/id','ProgrammeController@edit');
    Route::post('/edit_programme/{id}/id','ProgrammeController@update');
    Route::get('/classes/create','ProgrammeController@createClass');
    Route::post('/classes/create','ProgrammeController@storeClass');
    Route::get('/classes/view','ProgrammeController@viewClasses');
    
    Route::get('/create_grade','GradeController@create');
    Route::post('/create_grade','GradeController@store');
    
    Route::get('/grade_system','GradeController@index');
    Route::delete('/delete_grade', 'GradeController@destroy');
   
    
     Route::get('/class/list','CourseController@classList');
    
    
    Route::get('/grade_system/{type}/slug','GradeController@show');
    Route::post('/update_grades/','GradeController@update');
    Route::get('/house/view','HouseController@index');
    Route::post('/houses','HouseController@update');
    Route::get('/house/create', "HouseController@create");
    Route::post('/house/create','HouseController@store');
    Route::get('/house/{id}/edit','HouseController@edit');
    Route::post('/house/{id}/edit','HouseController@update');
    
    //Academic Modules
     /************** course management ****************************/
    Route::get('/courses','CourseController@index');
    Route::post('/courses','CourseController@store');
    Route::get('/edit_course/{id}/id','CourseController@edit');
   
    Route::post('/edit_course/course','CourseController@update');
    
    /************** Class management ****************************/
    Route::match(array("get", "post"), '/classes', "ClassController@index");
    Route::get('/classes/{id}/edit','ClassController@edit');
   Route::post('/classes/edit','ClassController@update');
   
    Route::delete('/delete_class', 'ClassController@destroy');
    
    Route::get('/mount_course','CourseController@mountCourse');
    Route::post('/mount_course','CourseController@mountCourseStore');
    Route::get('/mounted_view','CourseController@viewMounted');
    Route::get('/registered_courses','CourseController@viewRegistered');
    Route::get('/marksheet/{course}/course/{code}/code','CourseController@enterMark');
    Route::post('/marksheet/{course}/course/{code}/code','CourseController@storeMark');
    
    Route::delete('/delete_course', 'CourseController@destroy');
    Route::delete('/delete_mounted', 'CourseController@destroy_mounted');
    
    Route::get('/teachers/subject/allocation', "CourseController@subjectAllocator");
   
    Route::match(array("get", "post"), '/teachers/subject/create', "CourseController@allocationCreator");
   
    Route::delete('/deleteAllocation', 'CourseController@destroyAllocatedCourse');
    
    Route::post('/courses/allocation/update','CourseController@updateAllocation');
     
    Route::get('/upload/marks','CourseController@showFileUpload');
    Route::post('/upload/marks','CourseController@uploadMarks');
    Route::match(array("get", "post"), '/attendanceSheet', "CourseController@attendanceSheet");
    Route::match(array("get", "post"), '/transcript', "CourseController@transcript");
    Route::match(array("get", "post"), '/upload/courses', "CourseController@uploadCourse");
    Route::get('/courseDownloadExcel/{type}', 'CourseController@courseDownloadExcel');
    Route::get('/marksDownloadExcel/{code}/code', 'CourseController@marksDownloadExcel');
    
    
    Route::match(array("get", "post"), '/upload/mounted', "CourseController@uploadMounted");
    Route::match(array("get", "post"), '/upload/legacy', "CourseController@uploadLegacy");
    Route::match(array("get", "post"), '/mounted/{id}/edit', "CourseController@updateMounted");
   
    Route::get('/printAttendance/{semester}/sem/{course}/course/{code}/code/{level}/level/{id}/id','CourseController@printAttendance');

    Route::get('/calender','AcademicCalenderController@index');
    Route::get('/create_calender','AcademicCalenderController@createCalender');
    Route::post('/create_calender','AcademicCalenderController@storeCalender');
    Route::delete('/delete_calender', 'AcademicCalenderController@destroy');
    Route::get('/fireCalender/{item}/id/{action}/action','AcademicCalenderController@updateCalender');
    Route::match(array("get", "post"), '/printReceipt', "PaymentController@printLostReceipt");
    // E-Payments goes here
    Route::get('/pay_transcript', 'PaymentController@showPayform');
    Route::post('/pay_transcript', 'PaymentController@showStudent');
    Route::post('/process_transcript', 'PaymentController@processTranscript');
    Route::get('/printreceiptTranscript/{receiptno}', 'PaymentController@printreceiptTranscript');
    
    
    Route::get('/view_payments_master', 'FeeController@masterLedger');
     
     Route::controller('departments', 'DepartmentController', [
        'anyData' => 'departments.data',
        'getIndex' => 'departments',
    ]);
     Route::get('department/add', 'DepartmentController@create');
     Route::post('department/add', 'DepartmentController@store');
     Route::get('/edit_department/{id}/id', 'DepartmentController@edit');
     Route::post('/department/update', 'DepartmentController@update');
      
    Route::delete('/department/update', 'DepartmentController@destroy');
   
     // sms management
      Route::post('/sms', 'SmsController@smsMember');
     Route::post('/staff/sms', 'SmsController@smsStaff');
    
         Route::post('/sms/reminder', 'SmsController@alert');
      // book management
      Route::get('/books', 'BookController@index');
    
    Route::get('book/add', 'BookController@create');
     Route::post('book/add', 'BookController@store');
   Route::get('/edit_book/{id}/id', 'BookController@edit');
    Route::post('books/update', 'BookController@update');
     Route::delete('book_destroy', 'BookController@destroy');
     Route::get('/books/discharge', 'BookController@discharges');
     Route::get('/books/charge', 'BookController@charges');
     Route::get('book/upload', 'BookController@bookUploadForm');
    Route::post('book/upload', 'BookController@uploadBooks');
   
    
     // staff
    Route::get('/workers', 'StaffController@index');
    Route::get('/workers/add', 'StaffController@create');
    Route::post('/workers/add', 'StaffController@store');
    Route::post('/workers/update', 'StaffController@update');
    Route::get('/edit_worker/{id}/id', 'StaffController@edit');
 Route::delete('worker_destroy', 'StaffController@destroy');
    Route::post('/workers/update', 'StaffController@update');

 Route::get('/directory', 'StaffController@directory');
Route::post('/power_users', 'UserController@createStaffAccount');
    
// book transactions
Route::get('books/borrow', 'TransactionController@showForm');
Route::post('books/borrow', 'TransactionController@showMember');
Route::post('/books/process', 'TransactionController@store');
Route::get('books/return', 'TransactionController@showForm');
Route::post('books/return', 'TransactionController@showReturn');
Route::post('/books/return/process', 'TransactionController@storeReturn');

Route::get('/transactions', 'TransactionController@index');

    
     // system settings
    
    Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
    Route::get('/systems/view','SettingsController@index');
    Route::get('/systems/sms','SettingsController@smsLogs');
   Route::get('/systems/user/logs','SettingsController@logs');
   
    Route::get('/systems/synchronizations','SettingsController@showSync');
    
    Route::get('/systems/user/logs','SettingsController@logs');
    
    Route::get('/finance/reports/summary/','ReportController@summaryPayment');
   Route::get('/finance/reports/programs/','ReportController@summaryPaymentPrograms2');
   Route::get('/finance/reports/owing/','ReportController@summaryOwingsPrograms');
    Route::get('/finance/reports/fees/','ReportController@showBills');
   Route::post('finance/bills/create','FeeController@createBill');
   Route::get('/finance/reports/ledger/student','FeeController@showPayform');
   Route::post('/finance/reports/ledger/student','ReportController@studentLedger');
    Route::match(array("get", "post"), '/finance/reports/cummulative', "ReportController@programLedger");
    Route::match(array("get", "post"), '/broadsheet/noticeboard', "CourseController@noticeBoardBroadsheet");
    Route::match(array("get", "post"), '/broadsheet/naptex', "CourseController@naptexBroadsheet");
    
    Route::match(array("get", "post"), '/groups/create', "GroupController@createGroup");
      Route::match(array("get", "post"),'/systems/users/update','SettingsController@updateUsers');
    
   Route::get('/applicants/view/','ApplicantController@index');
   Route::get('/applicants/sms/','ApplicantController@admitMessage');
   Route::get('/systems/cards/','ApplicantController@cards');
   Route::post('/applicants/admit','ApplicantController@admit');
   Route::get('/applicant_show/{id}/id', 'ApplicantController@show'); // for printout
   
   
    ////////////////////////////////////////////////////////////////////
    // Admissions//
//    Route::group(['middleware' => 'RoleMiddleware'], function()
//        {
//             Route::match(array("get", "post"), '/admissions/upload/cards', "FormController@uploadCards");
//
//        });
    });


    
  
  
  
  
  
  
  
  
Route::get('importExport', 'MaatwebsiteDemoController@importExport');

Route::get('downloadExcel/{type}', 'MaatwebsiteDemoController@downloadExcel');

Route::post('importExcel', 'MaatwebsiteDemoController@importExcel');
/*
|--------------------------------------------------------------------------
| API routes
|--------------------------------------------------------------------------
*/

Route::group(['prefix' => 'api', 'namespace' => 'API'], function () {
    Route::group(['prefix' => 'v1'], function () {
        require config('infyom.laravel_generator.path.api_routes');
    });
});
