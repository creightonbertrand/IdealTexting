@extends('client')

@section('content')

    <section class="wrapper-bottom-sec">
        <div class="p-30"></div>
        <div class="p-15 p-t-none p-b-none m-l-10 m-r-10">

            @include('notification.notify')
        </div>

        
        <div class="p-15 p-t-none p-b-none">
            <div class="row">
                <div class="col-md-8">
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="panel-title text-center">{{language_data('SMS History By Date',Auth::guard('client')->user()->lan_id)}}</h3>
                        </div>
                        <div class="panel-body">
                            {!! $sms_history->render() !!}
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="panel-title text-center">{{language_data('SMS Success History',Auth::guard('client')->user()->lan_id)}}</h3>
                        </div>
                        <div class="panel-body">
                            {!! $sms_status_json->render() !!}
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
