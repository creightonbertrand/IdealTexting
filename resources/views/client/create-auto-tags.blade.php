@extends('client')

@section('content')

    <section class="wrapper-bottom-sec">
        <div class="p-30">
            <h2 class="page-title">Auto Tags Rules</h2>
        </div>
        <div class="p-30 p-t-none p-b-none">

            @include('notification.notify')
            <div class="row">

                <div class="col-lg-6">
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="panel-title">Create Auto Tags Rules</h3>
                        </div>
                        <div class="panel-body">
                            <form class="" role="form" action="{{url('user/sms/post-auto-tags')}}" method="post">


                                <div class="form-group">
                                    <label>Name</label>
                                    <input type="text" class="form-control" required name="name"/>
                                </div>
                                

                                <div class="form-group">
                                    <label>Keywords</label>
                                    <textarea class="form-control" id="keyword" name="keyword" rows="4"></textarea>
                                </div>

                                <div class="form-group">
                                    <label>Tags</label>
                                    <input type="text" class="form-control"  required name="tags"/>
                                </div>


                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <button type="submit" class="btn btn-success btn-sm pull-right"><i class="fa fa-save"></i> {{language_data('Save',Auth::guard('client')->user()->lan_id)}}</button>
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

    <script>
        $(document).ready(function () {

            var $get_msg = $('#message');

            $('#merge_value').on('change', function () {
                var caretPos = $get_msg[0].selectionStart;
                var textAreaTxt = $get_msg.val();
                var txtToAdd = this.value;

                $get_msg.val(textAreaTxt.substring(0, caretPos) + txtToAdd + textAreaTxt.substring(caretPos) );
            });

        });
    </script>

@endsection
