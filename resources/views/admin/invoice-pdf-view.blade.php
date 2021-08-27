<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{language_data('Invoice No')}}: {{$inv->id}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{--Global StyleSheet Start--}}
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,300,500,700' rel='stylesheet' type='text/css'>

    <link href="{{public_path(ltrim('assets/libs/bootstrap/css/bootstrap.min.css'), '/')}}"  rel='stylesheet' type='text/css'>
    <link href="{{public_path(ltrim('assets/css/style.css'), '/')}}"  rel='stylesheet' type='text/css'>
    <link href="{{public_path(ltrim('assets/css/admin.css'), '/')}}"  rel='stylesheet' type='text/css'>
    <link href="{{public_path(ltrim('assets/css/responsive.css'), '/')}}"  rel='stylesheet' type='text/css'>


    <style>
        .print-button{
            display: none;
        }

        .count-title {
            font-weight: normal !important;
            font-size: 12px !important;
        }

        .inv-block{
            font-size: 12px !important;
            font-weight: normal !important;
        }

        table.table tr td{
            display: table-cell !important;
        }

    </style>

</head>

<body class="m-t-20">
<section class="wrapper-bottom-sec">
    <div class="p-30 p-t-none p-b-none">
        <div class="panel">
            <div class="panel-body p-none">
                <div class="p-20">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="col-xs-6 p-t-20 invoice-status">
                                <div class="m-b-5 app-logo-inner">
                                        @php
                                            $path = public_path(app_config('AppLogo'));
                                            $type = pathinfo($path, PATHINFO_EXTENSION);
                                            $data = file_get_contents($path);
                                            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                                        @endphp

                                        <img src="{{$base64}}"><br>
                                </div>
                                <address>
                                    {!!app_config('Address')!!}
                                </address>

                                <div class="m-t-20">
                                    <h3 class="panel-title">{{language_data('Invoice To')}}: </h3>
                                    <h3 class="invoice-to-client-name">{{$inv->client_name}}</h3>
                                </div>

                                <address>
                                    {{$client->address1}} <br>
                                    {{$client->address2}} <br>
                                    {{$client->state}}, {{$client->city}} - {{$client->postcode}}, {{$client->country}}
                                    <br><br> {{language_data('Phone')}}: {{$client->phone}}
                                    <br> {{language_data('Email')}}: {{$client->email}}
                                </address>

                            </div>

                            <div class="col-xs-6">


                                <div class="btn-group pull-right" aria-label="...">
                                    <button onclick="print_data()" class="btn btn-success btn-sm print-button"><i class="fa fa-print"></i> {{language_data('Printable Version')}}</button>
                                    <div class="m-t-20">
                                        <div class="bill-data">
                                            <p class="m-b-5">
                                                <span class="bill-data-title">{{language_data('Invoice No')}}:</span>
                                                <span class="bill-data-value">#{{$inv->id}}</span>
                                            </p>
                                            <p class="m-b-5">
                                                <span class="bill-data-title">{{language_data('Invoice Status')}}:</span>
                                                @if($inv->status=='Unpaid')
                                                    <span class="bill-data-value"><span class="bill-data-status">{{language_data('Unpaid')}}</span></span>
                                                @elseif($inv->status=='Paid')
                                                    <span class="bill-data-value"><span class="bill-data-status">{{language_data('Paid')}}</span></span>
                                                @elseif($inv->status=='Partially Paid')
                                                    <span class="bill-data-value"><span class="bill-data-status">{{language_data('Partially Paid')}}</span></span>
                                                @else
                                                    <span class="bill-data-value"><span class="bill-data-status">{{language_data('Cancelled')}}</span></span>
                                                @endif
                                            </p>
                                            <p class="m-b-5">
                                                <span class="bill-data-title">{{language_data('Invoice Date')}}:</span>
                                                <span class="bill-data-value">{{get_date_format($inv->created)}}</span>
                                            </p>
                                            <p class="m-b-5">
                                                <span class="bill-data-title">{{language_data('Due Date')}}:</span>
                                                <span class="bill-data-value">{{get_date_format($inv->duedate)}}</span>
                                            </p>
                                            @if($inv->status=='Paid')
                                                <p class="m-b-5">
                                                    <span class="bill-data-title">{{language_data('Paid Date')}}:</span>
                                                    <span class="bill-data-value">{{get_date_format($inv->datepaid)}}</span>
                                                </p>
                                            @endif

                                        </div>
                                    </div>

                                </div>


                            </div>

                        </div>


                        <div class="col-lg-12 col-xs-12">
                            <table class="table table-hover">
                                <thead>
                                <tr class="h5 text-dark">
                                    <th style="width: 5%;" >{{language_data('SL')}}</th>
                                    <th style="width: 65%;" >{{language_data('Item')}}</th>
                                    <th style="width: 10%;" >{{language_data('Price')}}</th>
                                    <th style="width: 10%;" >{{language_data('Quantity')}}</th>
                                    <th style="width: 10%;" >{{language_data('Total')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($inv_items as $it)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{$it->item}}</td>
                                        <td>{{app_config('CurrencyCode')}} {{$it->price}}</td>
                                        <td>{{$it->qty}}</td>
                                        <td>{{app_config('CurrencyCode')}} {{$it->subtotal}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="col-lg-12 col-md-3 col-sm-3 col-xs-12">
                            <div class="invoice-summary">
                                <div class="row">
                                    <div class="col-lg-2 col-md-3 col-sm-2 col-xs-3">
                                        <div class="inv-block">
                                            <h3 class="count-title">{{language_data('Subtotal')}}</h3>
                                            <p>{{app_config('CurrencyCode')}} {{$inv->subtotal}}</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-3">
                                        <div class="inv-block">
                                            <h3 class="count-title">{{language_data('Tax')}}</h3>
                                            <p>{{app_config('CurrencyCode')}} {{$tax_sum}}</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-3 col-xs-3">
                                        <div class="inv-block">
                                            <h3 class="count-title">{{language_data('Discount')}}</h3>
                                            <p>{{app_config('CurrencyCode')}} {{$dis_sum}}</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-3 col-lg-offset-2 col-md-offset-1 col-sm-offset-1 text-right">
                                        <div class="inv-block">
                                            <h3 class="count-title">{{language_data('Grand Total')}}</h3>
                                            <p>{{app_config('CurrencyCode')}} {{$inv->total}}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            @if($inv->note!='')
                                <div class="well m-t-5 col-lg-12 col-md-3 col-sm-3 col-xs-12"><b>{{language_data('Invoice Note')}}: </b>{{$inv->note}}
                                </div>
                            @endif

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<script>
    function print_data() {
        window.print();
    }
</script>

</body>

</html>
