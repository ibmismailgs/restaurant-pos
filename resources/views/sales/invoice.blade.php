<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" type="image/png" href="#" />
    <title>Invoice</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="all,follow">

    <style type="text/css">
        * {
            font-size: 14px;
            line-height: 24px;
            font-family: 'Ubuntu', sans-serif;
            text-transform: capitalize;
        }
        .btn {
            padding: 7px 10px;
            text-decoration: none;
            border: none;
            display: block;
            text-align: center;
            margin: 7px;
            cursor:pointer;
        }

        .btn-info {
            background-color: #999;
            color: #FFF;
        }

        .btn-primary {
            background-color: #6449e7;
            color: #FFF;
            width: 100%;
        }
        td,
        th,
        tr,
        table {
            border-collapse: collapse;
        }
        tr {border-bottom: 1px dotted #ddd;}
        td,th {padding: 7px 0;width: 50%;}

        table {width: 100%;}
        tfoot tr th:first-child {text-align: left;}

        .centered {
            text-align: center;
            align-content: center;
        }
        small{font-size:11px;}

        @media print {
            * {
                font-size:12px;
                line-height: 20px;
            }
            td,th {padding: 5px 0;}
            .hidden-print {
                display: none !important;
            }
            @page { margin: 0; } body { margin: 0.5cm; margin-bottom:1.6cm; }
            tbody::after {
                content: '';
                display: block;
                page-break-after: always;
                page-break-inside: always;
                page-break-before: avoid;
            }
        }
    </style>
  </head>
<body>

<div style="max-width:400px;margin:0 auto">

    <div class="hidden-print">
        <table>
            <tr>
                <td><a href="{{ URL::previous() }}" class="btn btn-info"><i class="ik ik-arrow-left"></i>Back</a> </td>
                <td><button onclick="window.print();" class="btn btn-primary"><i class="dripicons-print"></i> Print</button></td>
            </tr>
        </table>
        <br>
    </div>

    <div id="receipt-data">
        <div class="centered">
            @if (empty($setting->logo))
            @else
                <img src="{{url('public/logo', $setting->logo)}}" height="42" width="50" style="margin:10px 0;">
            @endif

            <h2>{{ $setting->name ?? 'Data not found' }}</h2>
            <p>Address : {{ $setting->address ?? 'Data not found' }}
            <br>Phone : {{ $setting->phone ?? 'Data not found' }}
            </p>
        </div>
        <p>Date : {{ Carbon\Carbon::parse($sale->date)->format('d F, Y') ?? '--' }}<br>
            Order No : {{ $sale->order_no ?? 'Data not found' }}<br>
            Customer Name : {{ $sale->customer_name ?? 'Data not found' }}<br>
            Customer Mobile : {{ $sale->customer_mobile ?? 'Data not found' }}
        </p>
        <table class="table-data">
            <tbody>
            @foreach($saleDetails as $key => $saleDetail)
                <tr>
                    <td colspan="2">
                        {{ $saleDetail->products->name }}
                        <br>{{ $saleDetail->quantity }} x {{number_format((float)($saleDetail->amount / $saleDetail->quantity), 2, '.', '')}}
                    </td>

                    <td style="text-align:right;vertical-align:bottom">{{number_format((float)$saleDetail->amount, 2, '.', '')}}</td>
                </tr>
            @endforeach
                <tr>
                    <th colspan="2" style="text-align:left">Total</th>
                    <th style="text-align:right">{{number_format((float)$totalAmount, 2, '.', '')}}</th>
                </tr>

                @if($sale->discount != null)
                <tr>
                    <th colspan="2" style="text-align:left">Order Discount</th>
                    <th style="text-align:right">{{number_format((float)$sale->discount, 2, '.', '')}}</th>
                </tr>
                @endif

                @if($sale->tax_amount != null)
                <tr>
                    <th colspan="2" style="text-align:left">Vat</th>
                    <th style="text-align:right">{{number_format((float)$sale->tax_amount)}} %</th>
                </tr>
                @endif

                <tr>
                    <th colspan="2" style="text-align:left">Grand Total</th>
                    <th style="text-align:right">{{number_format((float)$sale->grand_total, 2, '.', '')}}</th>
                </tr>
                <tr>

                    @php
                        $formatter = new NumberFormatter('en', NumberFormatter::SPELLOUT);
                        $words = $formatter->format($sale->grand_total);
                    @endphp

                    <th class="centered" colspan="3">In Words: <span>{{ $words }} Only</span>

                    </th>
                </tr>


                <tr style="background-color:#ddd;">

                    <td style="padding: 5px;width:30%">Paid By :
                        @if ($sale->payment_type == 1)
                            Cash
                        @else
                            Card
                        @endif
                    </td>

                    <td style="padding: 5px;width:40%"> Amount : {{number_format((float)$sale->grand_total, 2, '.', '')}}</td>

                    <td style="padding: 2px;width:30%"> Change : {{number_format((float)$sale->change_amount, 2, '.', '')}}</td>
                </tr>

                <tr style="border: none"><td class="centered" colspan="3">Thank you for shopping with us. Please come again</td></tr>
            <tr style="border: none">
            <td colspan="3" class="centered">Support By : <a style="text-decoration: none" href="https://wardan.tech/" target="_blank"><b>Wardan Tech Ltd</b></a></td>
            </tr>
            </tbody>

    </table>

    </div>
</div>

<script type="text/javascript">
    localStorage.clear();
    function auto_print() {
        window.print()
    }
    setTimeout(auto_print, 1000);
</script>

</body>
</html>
