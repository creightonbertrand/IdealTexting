@extends('client')

{{--External Style Section--}}
@section('style')
    {!! Html::style("assets/libs/data-table/datatables.min.css") !!}
@endsection


@section('content')

    <section class="wrapper-bottom-sec">
        <div class="p-30">
            <h2 class="page-title">Get Phone Number</h2>
        </div>
        <div class="p-30 p-t-none p-b-none">
            @include('notification.notify')
            <div class="row">

                <div class="col-lg-4">
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="panel-title">Phone Number Search</h3>
                        </div>
                        <div class="panel-body">
                            <form class="" role="form" method="post" action="{{url('user/sms/phone-number')}}">

                                <div class="form-group">
                                    <label>Number Type</label>
                                    <select class="selectpicker form-control message_type" name="number_type">
                                        <option value="local">Local Number</option>
                                        <option value="tollfree">Toll Free</option>
                                        {{-- <option value="shortcode">Short Code</option> --}}
                                        
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Area Code</label>
                                    <input type="text" class="form-control" name="area_code">
                                </div>

                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <button type="submit" class="btn btn-success btn-sm pull-right"><i class="fa fa-plus"></i> Search </button>
                            </form>
                        </div>
                    </div>
                </div>
                <form role="form" id="number_form" action="{{url('user/sms/post-phone-number')}}" method="post">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                </form>
                <div class="col-lg-8">
                    <div class="panel">
                        <div class="panel-heading" style="padding-bottom: 40px;">
                            <h3 class="panel-title pull-left">Available Phone Numbers</h3>
                            <button id="buyTriger" class="btn btn-success btn-xs pull-right m-r-20"><i class="fa fa-plus"></i> Bulk Buy</button>
                        </div>
                        
                        <div class="panel-body p-none">
                            <table class="table data-table table-hover">
                                <thead>
                                <tr>
                                    <th style="width: 5%">

                                        <div class="coder-checkbox">
                                            <input type="checkbox"  id="bulkBuy"  />
                                            <span class="co-check-ui"></span>
                                        </div>

                                    </th>
                                    <th style="width: 10%">{{language_data('SL',Auth::guard('client')->user()->lan_id)}}</th>
                                    <th style="width: 35%">{{language_data('List name',Auth::guard('client')->user()->lan_id)}}</th>
                                    <th style="width: 55%">{{language_data('Action',Auth::guard('client')->user()->lan_id)}}</th>
                                </tr>
                                </thead>
                                <tbody>

                    


                                @foreach($local as $number)
                                <tr>
                                    
                                    <td>
                                        {{-- <p>{{ $loop->iteration }}</p> --}}
                                        <div class='coder-checkbox'>
                                            <input type='checkbox'  class='buyRow' value='{{$number->phoneNumber}}'  />
                                            <span class='co-check-ui'></span>
                                        </div>
                                    </td>
                                    <td><p>{{ $loop->iteration }}</p></td>
                                    <td>
                                        <p>{{$number->friendlyName}} </p>
                                    </td>
                                    <td>


                                        <a href="#" class="btn btn-success btn-xs cbuy" id="{{$number->phoneNumber}}"><i class="fa fa-plus"></i> Get This Number</a>
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

            $("#bulkBuy").on('click',function() { // bulk checked
                var status = this.checked;
                $(".buyRow").each( function() {
                    $(this).prop("checked",status);
                });
            });

            var buyTriger =  $('#buyTriger');
            buyTriger.hide();

            $( ".panel" ).delegate( ".buyRow, #bulkBuy", "change",function (e) {
                    $('#buyTriger').toggle($('.buyRow:checked').length > 0);
            });



          $('.data-table').DataTable({
            language: {
              url: '{!! url("assets/libs/data-table/i18n/".get_language_code(Auth::guard('client')->user()->lan_id)->language.".lang") !!}'
            },
            responsive: true,
            "order": [[ 1, 'asc' ]],
            "columnDefs": [
            { "orderable": false, "targets": 0 }
            ]
          })

          buyTriger.on("click", function(event){ // triggering delete one by one
                if( $('.buyRow:checked').length > 0 ){  // at-least one checkbox checked
                    var ids = [];
                    $('.buyRow').each(function(){
                        if($(this).is(':checked')) {
                            ids.push($(this).val());
                        }
                    });
                    var ids_string = ids.toString();  // array to string conversion

                    console.log(ids_string);

                    bootbox.confirm("{!! language_data('Are you sure',Auth::guard('client')->user()->lan_id) !!} ?", function (result) {
                        if (result) {
                            console.log(result)
                            $("<input />").attr("type", "hidden").attr("name", "numbers").attr("value", ids_string).appendTo("#number_form");
                            $("#number_form").submit();

                        }
                    });
                    
                }
            });

            /*For Delete Group*/
            $( "body" ).delegate( ".cbuy", "click",function (e) {
                e.preventDefault();
                var number = this.id;
                bootbox.confirm("{!! language_data('Are you sure',Auth::guard('client')->user()->lan_id) !!} ?", function (result) {
                    if (result) {
                        var _url = $("#_url").val();
                            window.location.href = _url + "/user/sms/get-phone-number/" + number;
                        }
                    });
                });

        });
    </script>
@endsection
