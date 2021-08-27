@extends('admin')

@section('content')

    <section class="wrapper-bottom-sec">
        <div class="p-30"></div>
        <div class="p-15 p-t-none p-b-none m-l-10 m-r-10">

            @include('notification.notify')
        </div>
        

        <div class="p-15 p-t-none p-b-none">
            <div class="row">
                

                <div class="col-sm-4 m-b-15">
                    <div class="z-shad-1">
                        <div class="bg-success text-white p-15 clearfix">
                            <span class="pull-left font-45 m-l-10"><i class="fa fa-user"></i></span>

                            <div class="pull-right text-right m-t-15">
                                <span class="small m-b-5 font-15">{{$total_clients}} Users</span>
                                <br>
                                <a href="{{url('clients/add')}}" class="btn btn-complete btn-xs text-uppercase">{{language_data('Add New')}}</a>
                            </div>

                        </div>
                    </div>
                </div>


                <div class="col-sm-4 m-b-15">
                    <div class="z-shad-1">
                        <div class="bg-primary text-white p-15 clearfix">
                            <span class="pull-left font-45 m-l-10"><i class="fa fa-users"></i></span>

                            <div class="pull-right text-right m-t-15">
                                <span class="small m-b-5 font-15">{{$total_groups}} User Groups</span>
                                <br>
                                <a href="{{url('clients/groups')}}" class="btn btn-complete btn-xs text-uppercase">{{language_data('Add New')}}</a>
                            </div>

                        </div>
                    </div>
                </div>


                <div class="col-md-4">
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="panel-title text-center">{{language_data('SMS Success History')}}</h3>
                        </div>
                        <div class="panel-body">
                            {!! $sms_status_json->render() !!}
                        </div>
                    </div>
                </div>

            </div>

        </div>
        <div class="p-15 p-t-none p-b-none">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="panel-title text-center">{{language_data('SMS History By Date')}}</h3>
                        </div>
                        <div class="panel-body">
                            {!! $sms_history->render() !!}
                        </div>
                    </div>
                </div>

            </div>

        </div>



    </section>

@endsection


{{--External Style Section--}}
@section('style')
    {!! Html::script("assets/libs/chartjs/chart.js")!!}
@endsection
