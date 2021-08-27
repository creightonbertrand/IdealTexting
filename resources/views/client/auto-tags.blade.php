@extends('client')

{{--External Style Section--}}
@section('style')
    {!! Html::style("assets/libs/data-table/datatables.min.css") !!}
@endsection


@section('content')

    <section class="wrapper-bottom-sec">
        <div class="p-30 clearfix">
            <h2 class="page-title inline-block">Auto Tags Rules</h2>

            <a href="{{url('user/sms/create-auto-tags')}}" class="btn btn-success btn-sm pull-right"><i class="fa fa-plus"></i> Add Rule</a>
        </div>
        <div class="p-30 p-t-none p-b-none">
            @include('notification.notify')
            <div class="row">

                <div class="col-lg-12">
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="panel-title">{{language_data('SMS Templates',Auth::guard('client')->user()->lan_id)}}</h3>
                        </div>
                        <div class="panel-body p-none">
                            <table class="table data-table table-hover">
                                <thead>
                                <tr>
                                    <th style="width: 5%;">SL#</th>
                                    <th style="width: 30%;">Name</th>
                                    <th style="width: 40%;">Keywords</th>
                                    
                                    
                                    
                                    <th style="width: 25%;">{{language_data('Action',Auth::guard('client')->user()->lan_id)}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($sms_templates as $st)
                                    <tr>
                                        <td data-label="SL">{{ $loop->iteration }}</td>
                                        <td data-label="Sender ID"><p>{{$st->title}}</p></td>
                                        <td data-label="Sender ID"><p>{{$st->keyword}}</p></td>

                                        
                                        <td data-label="Actions">
                                            
                                            <a class="btn btn-success btn-xs" href="{{url('user/sms/manage-auto-tags/'.$st->id)}}" ><i class="fa fa-edit"></i> {{language_data('Manage',Auth::guard('client')->user()->lan_id)}}</a>
                                            <a href="#" class="btn btn-danger btn-xs cdelete" id="{{$st->id}}"><i class="fa fa-trash"></i> {{language_data('Delete',Auth::guard('client')->user()->lan_id)}}</a>
                                            
                                        </td>
                                    </tr>
                                @endforeach

                                </tbody>
                            </table>
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
    {!! Html::script("assets/libs/data-table/datatables.min.js")!!}
    {!! Html::script("assets/js/bootbox.min.js")!!}

    <script>
        $(document).ready(function(){

          $('.data-table').DataTable({
            language: {
              url: '{!! url("assets/libs/data-table/i18n/".get_language_code(Auth::guard('client')->user()->lan_id)->language.".lang") !!}'
            },
            responsive: true
          })

            /*For Delete Sender ID*/
            $( "body" ).delegate( ".cdelete", "click",function (e) {
                e.preventDefault();
                var id = this.id;
              bootbox.confirm("{!! language_data('Are you sure',Auth::guard('client')->user()->lan_id) !!} ?", function (result) {
                    if (result) {
                        var _url = $("#_url").val();
                        window.location.href = _url + "/user/sms/delete-auto-tags/" + id;
                    }
                });
            });

        });
    </script>
@endsection