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
  
 <h4 class="heading_c uk-margin-bottom">System Settings</h4>
 
  <form action="page_settings.html" class="uk-form-stacked" id="page_settings">
                <div class="uk-grid" data-uk-grid-margin>
                    
                    <div class="uk-width-large-1-3 uk-width-medium-1-2">
                        <div class="md-card">
                            <div class="md-card-content">
                                <h4 class="uk-text-success uk-heading_c">Fetch data from local system to online</h4>
                                <ul class="md-list">
                                    <li>
                                        <div class="md-list-content">
                                            <div class="uk-float-right">
                                                <input type="checkbox" data-switchery checked id="settings_site_online" name="settings_site_online" />
                                            </div>
                                            <span class="md-list-heading">Site Online</span>
                                            <span class="uk-text-muted uk-text-small">Lorem ipsum dolor sit amet&hellip;</span>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="md-list-content">
                                            <div class="uk-float-right">
                                                <input type="checkbox" data-switchery id="settings_seo" name="settings_seo" />
                                            </div>
                                            <span class="md-list-heading">Search Engine Friendly URLs</span>
                                            <span class="uk-text-muted uk-text-small">Lorem ipsum dolor sit amet&hellip;</span>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="md-list-content">
                                            <div class="uk-float-right">
                                                <input type="checkbox" data-switchery id="settings_url_rewrite" name="settings_url_rewrite" />
                                            </div>
                                            <span class="md-list-heading">Use URL rewriting</span>
                                            <span class="uk-text-muted uk-text-small">Lorem ipsum dolor sit amet&hellip;</span>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="uk-width-large-1-3 uk-width-medium-1-2">
                        <div class="md-card">
                            <div class="md-card-content">
                                <h4 class="uk-text-success uk-heading_c">Fetch data from Online system to local system</h4>
                                
                                <ul class="md-list">
                                    <li>
                                        <div class="md-list-content">
                                            <div class="uk-float-right">
                                                <input type="checkbox" data-switchery data-switchery-color="#7cb342" checked id="settings_top_bar" name="settings_top_bar" />
                                            </div>
                                            <span class="md-list-heading">Top Bar Enabled</span>
                                            <span class="uk-text-muted uk-text-small">Lorem ipsum dolor sit amet&hellip;</span>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="md-list-content">
                                            <div class="uk-float-right">
                                                <input type="checkbox" data-switchery data-switchery-color="#7cb342" id="settings_api" name="settings_api" />
                                            </div>
                                            <span class="md-list-heading">Api Enabled</span>
                                            <span class="uk-text-muted uk-text-small">Lorem ipsum dolor sit amet&hellip;</span>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="md-list-content">
                                            <div class="uk-float-right">
                                                <input type="checkbox" data-switchery data-switchery-color="#d32f2f" id="settings_minify_static" checked name="settings_minify_static" />
                                            </div>
                                            <span class="md-list-heading">Minify JS files automatically</span>
                                            <span class="uk-text-muted uk-text-small">Lorem ipsum dolor sit amet&hellip;</span>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="uk-width-large-1-3 uk-width-medium-1-2">
                        <div class="md-card">
                            <div class="md-card-content">
                                <h4 class="uk-text-success uk-heading_c">Visit Statistics</h4>
                                <ul class="md-list">
                                    <li>
                                        <div class="md-list-content">
                                            <div class="uk-float-right">
                                                <input type="checkbox" data-switchery checked id="settings_site_online" name="settings_site_online" />
                                            </div>
                                            <span class="md-list-heading">Site Online</span>
                                            <span class="uk-text-muted uk-text-small">Lorem ipsum dolor sit amet&hellip;</span>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="md-list-content">
                                            <div class="uk-float-right">
                                                <input type="checkbox" data-switchery id="settings_seo" name="settings_seo" />
                                            </div>
                                            <span class="md-list-heading">Search Engine Friendly URLs</span>
                                            <span class="uk-text-muted uk-text-small">Lorem ipsum dolor sit amet&hellip;</span>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="md-list-content">
                                            <div class="uk-float-right">
                                                <input type="checkbox" data-switchery id="settings_url_rewrite" name="settings_url_rewrite" />
                                            </div>
                                            <span class="md-list-heading">Use URL rewriting</span>
                                            <span class="uk-text-muted uk-text-small">Lorem ipsum dolor sit amet&hellip;</span>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                

            </form>
@endsection
@section('js')
  
 <!--  settings functions -->
    <script src="{!!url('public/assets/js/pages/page_settings.min.js')!!}"></script>
    
 
@endsection