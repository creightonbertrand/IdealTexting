<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{app_config('AppTitle')}}</title>
    <link rel="icon" type="image/x-icon" href="<?php echo asset(app_config('AppFav')); ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>

    {{--Global StyleSheet Start--}}
    <link href='https://fonts.googleapis.com/css?family=Noto+Sans%3A100%2C100italic%2C200%2C200italic%2C300%2C300italic%2C400%2C400italic%2C500%2C500italic%2C600%2C600italic%2C700%2C700italic%2C800%2C800italic%2C900%2C900italic&ver=5.7.1' rel='stylesheet' type='text/css'>
    {!! Html::style("assets/libs/bootstrap/css/bootstrap.min.css") !!}
    {!! Html::style("assets/libs/bootstrap-toggle/css/bootstrap-toggle.min.css") !!}
    {!! Html::style("assets/libs/font-awesome/css/font-awesome.min.css") !!}
    {!! Html::style("assets/libs/alertify/css/alertify.css") !!}
    {!! Html::style("assets/libs/alertify/css/alertify-bootstrap-3.css") !!}
    {!! Html::style("assets/libs/bootstrap-select/css/bootstrap-select.min.css") !!}
    {{--Custom StyleSheet Start--}}

    @yield('style')

    {{--Global StyleSheet End--}}
    {!! Html::style("assets/css/style.css") !!}
    {!! Html::style("assets/css/admin.css") !!}
    {!! Html::style("assets/css/responsive.css") !!}

</head>


<body class="has-left-bar has-top-bar @if(Auth::user()->menu_open==1) left-bar-open @endif">

<nav id="left-nav" class="left-nav-bar">
    <div class="nav-top-sec">
        <div class="app-logo">
            <img src="<?php echo asset(app_config('AppLogo')); ?>" alt="logo" class="bar-logo" width="145px"
                 height="35px">
        </div>

        <a href="#" id="bar-setting" class="bar-setting"><i class="fa fa-bars"></i></a>
    </div>
    <div class="nav-bottom-sec">
        <ul class="left-navigation" id="left-navigation">

            {{--Dashboard--}}
            <li @if(Request::path()== 'admin/dashboard') class="active" @endif><a
                        href="{{url('admin/dashboard')}}"><span class="menu-text">{{language_data('Dashboard')}}</span>
                    <span class="menu-thumb"><i class="fa fa-dashboard"></i></span></a></li>


                {{--Users--}}
                <li class="has-sub @if(Request::path()== 'clients/all' OR Request::path()=='clients/add' OR Request::path()=='clients/view/'.view_id() OR Request::path()=='clients/export-n-import' OR Request::path()== 'clients/groups') sub-open init-sub-open @endif">
                    <a href="#"><span class="menu-text">Users</span> <span
                                class="arrow"></span><span class="menu-thumb"><i class="fa fa-user"></i></span></a>
                    <ul class="sub">

                        <li @if(Request::path()== 'clients/all' OR Request::path()=='clients/view/'.view_id()) class="active" @endif>
                            <a href={{url('clients/all')}}><span
                                        class="menu-text">All Users</span> <span
                                        class="menu-thumb"><i class="fa fa-user"></i></span></a></li>

                        <li @if(Request::path()== 'clients/add') class="active" @endif><a
                                    href={{url('clients/add')}}><span
                                        class="menu-text">Add New User</span> <span
                                        class="menu-thumb"><i class="fa fa-user-plus"></i></span></a></li>

                        <li @if(Request::path()== 'clients/groups') class="active" @endif><a
                                    href="{{url('clients/groups')}}"><span
                                        class="menu-text">Users Groups</span> <span
                                        class="menu-thumb"><i class="fa fa-users"></i></span></a></li>

                        <li @if(Request::path()== 'clients/export-n-import') class="active" @endif><a
                                    href={{url('clients/export-n-import')}}><span
                                        class="menu-text">Export and Import Users</span> <span
                                        class="menu-thumb"><i class="fa fa-file-excel-o"></i></span></a></li>

                    </ul>
                </li>



                

            {{--Keywords--}}
            <li class="has-sub @if(Request::path()== 'keywords/all' OR Request::path()=='keywords/add' OR Request::path()=='keywords/view/'.view_id() OR Request::path()=='keywords/settings') sub-open init-sub-open @endif">
                <a href="#"><span class="menu-text">{{language_data('Keywords')}}</span> <span
                            class="arrow"></span><span class="menu-thumb"><i class="fa fa-keyboard-o"></i></span></a>
                <ul class="sub">

                    <li @if(Request::path()== 'keywords/all' OR Request::path()=='keywords/view/'.view_id()) class="active" @endif>
                        <a href={{url('keywords/all')}}><span class="menu-text">{{language_data('All Keywords')}}</span>
                            <span class="menu-thumb"><i class="fa fa-list"></i></span></a></li>

                    <li @if(Request::path()== 'keywords/add') class="active" @endif><a
                                href={{url('keywords/add')}}><span
                                    class="menu-text">{{language_data('Add New Keyword')}}</span> <span
                                    class="menu-thumb"><i class="fa fa-plus"></i></span></a></li>


                    <li @if(Request::path()== 'keywords/settings') class="active" @endif><a
                                href={{url('keywords/settings')}}><span
                                    class="menu-text">{{language_data('Keyword Settings')}}</span> <span
                                    class="menu-thumb"><i class="fa fa-cog"></i></span></a></li>

                </ul>
            </li>



                {{--Contacts--}}
                <li class="has-sub @if(Request::path()== 'sms/phone-book' OR Request::path()== 'sms/import-contacts' OR Request::path()== 'sms/view-contact/'.view_id() OR Request::path()== 'sms/blacklist-contacts' OR Request::path()== 'sms/add-contact/'.view_id() OR Request::path()== 'sms/edit-contact/'.view_id() OR Request::path()== 'sms/spam-words') sub-open init-sub-open @endif">
                    <a href="#"><span class="menu-text">{{language_data('Contacts')}}</span> <span
                                class="arrow"></span><span class="menu-thumb"><i class="fa fa-book"></i></span></a>
                    <ul class="sub">

                        <li @if(Request::path()== 'sms/phone-book' OR Request::path()== 'sms/view-contact/'.view_id()  OR Request::path()== 'sms/add-contact/'.view_id() OR Request::path()== 'sms/edit-contact/'.view_id()) class="active" @endif>
                            <a href={{url('sms/phone-book')}}><span
                                        class="menu-text"> {{language_data('Phone Book')}}</span> <span
                                        class="menu-thumb"><i class="fa fa-book"></i></span></a></li>

                        <li @if(Request::path()== 'sms/import-contacts') class="active" @endif><a
                                    href={{url('sms/import-contacts')}}><span
                                        class="menu-text"> {{language_data('Import Contacts')}}</span> <span
                                        class="menu-thumb"><i class="fa fa-plus"></i></span></a></li>

                        <li @if(Request::path()== 'sms/blacklist-contacts') class="active" @endif><a
                                    href={{url('sms/blacklist-contacts')}}><span
                                        class="menu-text"> {{language_data('Blacklist Contacts')}}</span> <span
                                        class="menu-thumb"><i class="fa fa-remove"></i></span></a></li>

                        <li @if(Request::path()== 'sms/spam-words') class="active" @endif><a
                                    href={{url('sms/spam-words')}}><span
                                        class="menu-text"> {{language_data('Spam Words')}}</span> <span
                                        class="menu-thumb"><i class="fa fa-stop"></i></span></a></li>

                    </ul>
                </li>





                



                {{--Bulk SMS--}}
                <li class="has-sub @if(Request::path()== 'sms/quick-sms'OR Request::path()== 'sms/send-sms' OR Request::path()=='sms/send-sms-user' OR Request::path()=='sms/send-schedule-sms' OR Request::path()=='sms/send-schedule-sms-file' OR Request::path()== 'sms/update-schedule-sms' OR Request::path()=='sms/manage-update-schedule-sms/'.view_id() OR Request::path()== 'sms/campaign-reports' OR Request::path()=='sms/manage-campaign/'.view_id()) sub-open init-sub-open @endif">
                    <a href="#"><span class="menu-text">{{language_data('Bulk SMS')}}</span> <span
                                class="arrow"></span><span class="menu-thumb"><i class="fa fa-mobile"></i></span></a>
                    <ul class="sub">


                        <li @if(Request::path()== 'sms/quick-sms') class="active" @endif><a
                                    href={{url('sms/quick-sms')}}><span
                                        class="menu-text">{{language_data('Send Quick SMS')}}</span> <span
                                        class="menu-thumb"><i class="fa fa-space-shuttle"></i></span></a></li>
                                        
                        <li @if(Request::path()== 'sms/send-sms-user') class="active" @endif><a
                                    href={{url('sms/send-sms-user')}}><span
                                        class="menu-text"></span> Schedule SMS for User<span
                                        class="menu-thumb"><i class="fa fa-file-text"></i></span></a></li>

                        <li @if(Request::path()== 'sms/send-sms') class="active" @endif><a
                                    href={{url('sms/send-sms')}}><span
                                        class="menu-text">{{language_data('Send Bulk SMS')}}</span> <span
                                        class="menu-thumb"><i class="fa fa-send"></i></span></a></li>

                        <li @if(Request::path()== 'sms/send-schedule-sms') class="active" @endif><a
                                    href={{url('sms/send-schedule-sms')}}><span
                                        class="menu-text">{{language_data('Send')}} {{language_data('Schedule SMS')}}</span>
                                <span class="menu-thumb"><i class="fa fa-send-o"></i></span></a></li>

                        

                

                        <li @if(Request::path()== 'sms/campaign-reports' OR Request::path()=='sms/manage-campaign/'.view_id()  OR Request::path()=='sms/manage-update-schedule-sms/'.view_id() ) class="active" @endif>
                            <a href={{url('sms/campaign-reports')}}><span
                                        class="menu-text">{{language_data('Campaign Reports')}}</span> <span
                                        class="menu-thumb"><i class="fa fa-line-chart"></i></span></a></li>


                    </ul>
                </li>




            <li @if(Request::path()== 'sms/sender-id-management' OR Request::path()=='sms/add-sender-id' OR Request::path()=='sms/view-sender-id/'.view_id()) class="active" @endif>
                <a href={{url('sms/sender-id-management')}}><span
                            class="menu-text">{{language_data('Sender ID Management')}}</span> <span class="menu-thumb"><i
                                class="fa fa-user-secret"></i></span></a></li>


            <li @if(Request::path()=='sms/sms-templates' OR Request::path()=='sms/create-sms-template' OR Request::path()=='sms/manage-sms-template/'.view_id()) class="active" @endif>
                <a href={{url('sms/sms-templates')}}><span class="menu-text">{{language_data('SMS Templates')}}</span>
                    <span class="menu-thumb"><i class="fa fa-file-code-o"></i></span></a></li>

            <li @if(Request::path()=='sms/sms-reply-templates' OR Request::path()=='sms/create-sms-reply-template' OR Request::path()=='sms/manage-sms-reply-template/'.view_id()) class="active" @endif><a href={{url('sms/sms-reply-templates')}}><span class="menu-text">Quick Reply</span> <span class="menu-thumb"><i class="fa fa-file-code-o"></i></span></a></li>

            <li @if(Request::path()=='sms/auto-tags' OR Request::path()=='sms/create-auto-tags' OR Request::path()=='sms/manage-auto-tags/'.view_id()) class="active" @endif><a href={{url('sms/auto-tags')}}><span class="menu-text">Auto Tag</span> <span class="menu-thumb"><i class="fa fa-file-code-o"></i></span></a></li>


            <li @if(Request::path()=='sms/chat-box') class="active" @endif><a href={{url('sms/chat-box')}}><span class="menu-text">{{language_data('Chat SMS')}}</span>
                    <span class="menu-thumb"><i class="fa fa-comments"></i></span>
                </a>
            </li>


            {{--History--}}
            <li class="has-sub @if(Request::path()=='sms/history' OR Request::path()=='sms/view-inbox/'.view_id() OR Request::path()=='sms/reports/download' OR Request::path()=='sms/reports/delete' OR Request::path()=='sms/block-message' OR Request::path()=='sms/view-block-message/'.view_id()) sub-open init-sub-open @endif">
                <a href="#"><span class="menu-text">{{language_data('Reports')}}</span> <span class="arrow"></span><span
                            class="menu-thumb"><i class="fa fa-list"></i></span></a>
                <ul class="sub">

                    <li @if(Request::path()=='sms/history' OR Request::path()=='sms/view-inbox/'.view_id()) class="active" @endif>
                        <a href={{url('sms/history')}}><span class="menu-text">{{language_data('SMS History')}}</span>
                            <span class="menu-thumb"><i class="fa fa-list"></i></span></a></li>


                    <li @if(Request::path()=='sms/block-message' OR Request::path()=='sms/view-block-message/'.view_id()) class="active" @endif>
                        <a href={{url('sms/block-message')}}><span
                                    class="menu-text">{{language_data('Block Message')}}</span> <span
                                    class="menu-thumb"><i class="fa fa-remove"></i></span></a></li>

                </ul>
            </li>

        


            {{--Administrators--}}
            <li class="has-sub @if(Request::path()== 'administrators/all' OR Request::path()=='administrators/manage/'.view_id() OR Request::path()=='administrators/role' OR Request::path()=='administrators/set-role/'.view_id()) sub-open init-sub-open @endif">
                <a href="#"><span class="menu-text">{{language_data('Administrators')}}</span> <span
                            class="arrow"></span><span class="menu-thumb"><i class="fa fa-user"></i></span></a>
                <ul class="sub">
                    <li @if(Request::path()== 'administrators/all'  OR Request::path()=='administrators/manage/'.view_id()) class="active" @endif>
                        <a href={{url('administrators/all')}}><span
                                    class="menu-text">{{language_data('Administrators')}}</span> <span
                                    class="menu-thumb"><i class="fa fa-user"></i></span></a></li>

                    <li @if(Request::path()=='administrators/role' OR Request::path()=='administrators/set-role/'.view_id()) class="active" @endif>
                        <a href={{url('administrators/role')}}><span
                                    class="menu-text">{{language_data('Administrator Roles')}}</span> <span
                                    class="menu-thumb"><i class="fa fa-user-secret"></i></span></a></li>

                </ul>
            </li>


            {{--Setting--}}
            <li class="has-sub @if(Request::path()== 'settings/general' OR Request::path()=='settings/localization' OR Request::path()=='settings/language-settings' OR Request::path()=='settings/language-settings-translate/'.view_id() OR Request::path()=='settings/language-settings-manage/'.view_id()  OR Request::path()=='settings/payment-gateways' OR Request::path()=='settings/payment-gateway-manage/'.view_id() OR Request::path()=='settings/background-jobs' OR Request::path()=='settings/purchase-code') sub-open init-sub-open @endif">
                <a href="#"><span class="menu-text">{{language_data('Settings')}}</span> <span
                            class="arrow"></span><span class="menu-thumb"><i class="fa fa-cogs"></i></span></a>
                <ul class="sub">

                    <li @if(Request::path()== 'settings/general') class="active" @endif><a
                                href={{url('settings/general')}}><span
                                    class="menu-text">{{language_data('System Settings')}}</span> <span
                                    class="menu-thumb"><i class="fa fa-cog"></i></span></a></li>


                    <li @if(Request::path()== 'settings/localization') class="active" @endif><a
                                href={{url('settings/localization')}}><span
                                    class="menu-text">{{language_data('Localization')}}</span> <span class="menu-thumb"><i
                                        class="fa fa-globe"></i></span></a></li>


                    <li @if(Request::path()== 'settings/language-settings' OR Request::path()=='settings/language-settings-manage/'.view_id() OR Request::path()=='settings/language-settings-translate/'.view_id()) class="active" @endif>
                        <a href={{url('settings/language-settings')}}><span
                                    class="menu-text">{{language_data('Language Settings')}}</span> <span
                                    class="menu-thumb"><i class="fa fa-language"></i></span></a></li>
                </ul>
            </li>

        

            {{--Logout--}}
            <li @if(Request::path()== 'admin/logout') class="active" @endif><a href="{{url('admin/logout')}}"><span
                            class="menu-text">{{language_data('Logout')}}</span> <span class="menu-thumb"><i
                                class="fa fa-power-off"></i></span></a></li>

        </ul>
    </div>
</nav>

<main id="wrapper" class="wrapper">

    <div class="top-bar clearfix">
        <ul class="top-info-bar">

            <li class="dropdown bar-notification @if(count(latest_five_invoices(0))>0) active @endif">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i
                            class="fa fa-shopping-cart"></i></a>
                <ul class="dropdown-menu arrow" role="menu">
                    <li class="title">{{language_data('Recent 5 Unpaid Invoices')}}</li>
                    @foreach(latest_five_invoices(0) as $in)
                        <li>
                            <a href="{{url('invoices/view/'.$in->id)}}">{{language_data('Amount')}} : {{$in->total}}</a>
                        </li>
                    @endforeach
                    <li class="footer"><a href="{{url('invoices/all')}}">{{language_data('See All Invoices')}}</a></li>
                </ul>
            </li>

            <li class="dropdown bar-notification @if(count(latest_five_tickets(0))>0) active @endif">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i
                            class="fa fa-envelope"></i></a>
                <ul class="dropdown-menu arrow message-dropdown" role="menu">
                    <li class="title">{{language_data('Recent 5 Pending Tickets')}}</li>
                    @foreach(latest_five_tickets(0) as $st)
                        <li>
                            <a href="{{url('support-tickets/view-ticket/'.$st->id)}}">
                                <div class="name">{{$st->name}} <span>{{$st->date}}</span></div>
                                <div class="message">{{$st->subject}}</div>
                            </a>
                        </li>
                    @endforeach

                    <li class="footer"><a href="{{url('support-tickets/all')}}">{{language_data('See All Tickets')}}</a>
                    </li>
                </ul>
            </li>
        </ul>


        <div class="navbar-right">

            <div class="clearfix">
                <div class="dropdown user-profile pull-right">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                        <span class="user-info">{{Auth::user()->fname}} {{Auth::user()->lname}}</span>

                        @if(Auth::user()->image!='')
                            <img class="user-image"
                                 src="<?php echo asset('assets/admin_pic/' . Auth::user()->image); ?>"
                                 alt="{{Auth::user()->fname}} {{Auth::user()->lname}}">
                        @else
                            <img class="user-image" src="<?php echo asset('assets/admin_pic/profile.jpg'); ?>"
                                 alt="{{Auth::user()->fname}} {{Auth::user()->lname}}">
                        @endif

                    </a>
                    <ul class="dropdown-menu arrow right-arrow" role="menu">
                        <li><a href="{{url('admin/edit-profile')}}"><i
                                        class="fa fa-edit"></i> {{language_data('Update Profile')}}</a></li>
                        <li><a href="{{url('admin/change-password')}}"><i
                                        class="fa fa-lock"></i> {{language_data('Change Password')}}</a></li>
                        <li class="bg-dark">
                            <a href="{{url('admin/logout')}}" class="clearfix">
                                <span class="pull-left">{{language_data('Logout')}}</span>
                                <span class="pull-right"><i class="fa fa-power-off"></i></span>
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="top-info-bar m-r-10">

                    <div class="dropdown pull-right bar-notification">
                        <a href="#" class="dropdown-toggle text-success" data-toggle="dropdown" role="button"
                           aria-expanded="false">
                            <img src="<?php echo asset('assets/country_flag/' . \App\Language::find(app_config('Language'))->icon); ?>"
                                 alt="Language">
                        </a>
                        <ul class="dropdown-menu lang-dropdown arrow right-arrow" role="menu">
                            @foreach(get_language() as $lan)
                                <li>
                                    <a href="{{url('language/change/'.$lan->id)}}"
                                       @if($lan->id==app_config('Language')) class="text-complete" @endif>
                                        <img class="user-thumb"
                                             src="<?php echo asset('assets/country_flag/' . $lan->icon); ?>"
                                             alt="user thumb">
                                        <div class="user-name">{{$lan->language}}</div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

            </div>

        </div>
    </div>

    {{--Content File Start Here--}}

    @yield('content')

    {{--Content File End Here--}}

    <input type="hidden" id="_url" value="{{url('/')}}">
    <input type="hidden" id="_language_code" value="{{get_language_code()}}">
    <input type="hidden" id="_sms_gateway_count" value="{{active_sms_gateway()}}">
    <input type="hidden" id="_unsubscribe_message" value="{{ app_config('unsubscribe_message') }}">
</main>

{{--Global JavaScript Start--}}
{!! Html::script("assets/libs/jquery-1.10.2.min.js") !!}
{!! Html::script("assets/libs/jquery.slimscroll.min.js") !!}
{!! Html::script("assets/libs/bootstrap/js/bootstrap.min.js") !!}
{!! Html::script("assets/libs/bootstrap-toggle/js/bootstrap-toggle.min.js") !!}
{!! Html::script("assets/libs/alertify/js/alertify.js") !!}
{!! Html::script("assets/libs/bootstrap-select/js/bootstrap-select.min.js") !!}
{!! Html::script("assets/libs/bootstrap-select/js/i18n/".get_language_code()->language_code.".js") !!}
{!! Html::script("assets/js/scripts.js") !!}
{{--Global JavaScript End--}}

<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-Token': $('input[name="_token"]').val()
        }
    });

    var _url = $('#_url').val();

    var width = $(window).width();
    if (width <= 768) {
        $("body").removeClass('left-bar-open')
    } else {
        $('#bar-setting').click(function (e) {
            e.preventDefault();
            $.post(_url + '/admin/menu-open-status');
        });
    }

    var _active_gateway = $('#_sms_gateway_count').val();

    if (_active_gateway == 0) {
        alertify.log("<i class='fa fa-times-circle'></i> <span>There is no active sms gateway yet. <a href=" + _url + '/sms/http-sms-gateway' + "> Click </a>  to configure one.</span>", "warning", 0);
    }

</script>

{{--Custom JavaScript Start--}}

@yield('script')

{{--Custom JavaScript End Here--}}
</body>

</html>
