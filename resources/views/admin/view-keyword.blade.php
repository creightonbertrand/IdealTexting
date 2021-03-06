@extends('admin')

@section('content')

    <section class="wrapper-bottom-sec">
        <div class="p-30">
            <h2 class="page-title">{{language_data('Manage Keyword')}}</h2>
        </div>
        <div class="p-30 p-t-none p-b-none">
            @include('notification.notify')
            <div class="row">
                <div class="col-lg-6">
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="panel-title">{{language_data('Manage Keyword')}}</h3>
                        </div>
                        <div class="panel-body">
                            <form class="" role="form" method="post" action="{{url('keywords/post-manage-keyword')}}" enctype="multipart/form-data">
                                {{ csrf_field() }}

                                <div class="form-group">
                                    <label>{{language_data('Title')}}</label>
                                    <input type="text" class="form-control" required name="title" value="{{$keyword->title}}">
                                </div>

                                <div class="form-group">
                                    <label>{{language_data('Keyword Name')}}</label>
                                    <input type="text" class="form-control" name="keyword_name" required value="{{$keyword->keyword_name}}">
                                </div>

                                <div class="form-group">
                                    <label>{{language_data('Reply Text For Recipient')}}</label>
                                    <textarea class="form-control" rows="5" name="reply_text" >{{$keyword->reply_text}}</textarea>
                                </div>

                                

{{-- 
                                <div class="form-group">
                                    <label>{{language_data('Status')}}</label>
                                    <select class="selectpicker form-control" name="status">
                                        <option value="available" @if($keyword->status == 'available') selected @endif>Available</option>
                                        <option value="assigned" @if($keyword->status == 'assigned') selected @endif>Assigned</option>
                                    </select>
                                </div> --}}
                                <input type="hidden" value="available" name="status">

                                <div class="form-group">
                                    <label>{{language_data('Select Client')}}</label>
                                    <select class="selectpicker form-control" name="client"  data-live-search="true">
                                        <option value="0" @if($keyword->user_id == '0') selected @endif>{{language_data('None')}}</option>
                                        @foreach($clients as $cl)
                                            <option value="{{$cl->id}}" @if($keyword->user_id == $cl->id) selected @endif>{{$cl->username}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- <div class="form-group">
                                    <label>{{language_data('Price')}}</label> --}}
                                    <input type="hidden" value="0" name="price">
                                {{-- </div> --}}

                                <input type="hidden" name="validity" value="0">

                                {{-- <div class="form-group">
                                    <label>{{language_data('Price')}}</label>
                                    <input type="text" class="form-control" required name="price" value="{{$keyword->price}}">
                                </div>


                                <div class="form-group">
                                    <label>{{language_data('Validity')}}</label>
                                    <select class="selectpicker form-control" name="validity">
                                        <option value="0"  @if($keyword->validity == '0') selected @endif>{{language_data('Unlimited')}}</option>
                                        <option value="month1"  @if($keyword->validity == 'month1') selected @endif>{{language_data('Month')}}</option>
                                        <option value="months2"  @if($keyword->validity == 'months2') selected @endif>{{language_data('2 Months')}}</option>
                                        <option value="months3"  @if($keyword->validity == 'months3') selected @endif>{{language_data('3 Months')}}</option>
                                        <option value="months6"  @if($keyword->validity == 'months6') selected @endif>{{language_data('6 Months')}}</option>
                                        <option value="year1"  @if($keyword->validity == 'year1') selected @endif>{{language_data('Year')}}</option>
                                        <option value="years2"  @if($keyword->validity == 'years2') selected @endif>{{language_data('2 Years')}}</option>
                                        <option value="years3"  @if($keyword->validity == 'years3') selected @endif>{{language_data('3 Years')}}</option>
                                    </select>
                                </div> --}}

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Minimum Reply Delay Time (Seconds)</label>
                                            <input type="number" min="1" value="30" class="form-control" name="minimum_delay" required value="{{old('minimum_delay')}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Maximun Reply Delay Time (Seconds)</label>
                                            <input type="number" min="1" value="60" class="form-control" name="maximum_delay" required value="{{old('maximum_delay')}}">
                                        </div>
                                    </div>
                                </div>

                                <input type="hidden" value="{{$keyword->id}}" name="keyword_id">
                                <button type="submit" class="btn btn-success btn-sm pull-right"><i class="fa fa-save"></i> {{language_data('Update')}} </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </section>

@endsection

{{--External Style Section--}}
@section('script')
    {!! Html::script("assets/libs/handlebars/handlebars.runtime.min.js")!!}
    {!! Html::script("assets/js/form-elements-page.js")!!}
@endsection
