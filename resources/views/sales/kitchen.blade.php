<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" type="image/png" href="#" />
    <title>Kitchen Invoice</title>
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
            <h2>Scooby</h2>
            @else
                <img src="{{url('public/logo', $setting->logo)}}" height="42" width="50" style="margin:10px 0;">
            @endif

            <h2>{{ $setting->name ?? 'Data not found' }}</h2>
            <p>Address : {{ $setting->address ?? 'Data not found' }}
            <br>Phone : {{ $setting->phone ?? 'Data not found' }}
            </p>
        </div>
        <p> Date : {{ Carbon\Carbon::parse($sale->date)->format('d F, Y') ?? '--' }}<br>
            Order No : {{ $sale->order_no ?? '--' }}<br>
        </p>
        <table class="table-data">
            <tbody>
            @foreach($saleDetails as $key => $saleDetail)
                <tr>
                    <td colspan="2">
                        {{ $saleDetail->products->name }}
                    </td>
                    <td style="text-align:right;vertical-align:bottom">{{ $saleDetail->quantity }}</td>
                </tr>
            @endforeach

                <tr>
                    <th colspan="2" style="text-align:left">Total Quantity</th>
                    <th style="text-align:right">{{ $sale->total_quantity}}</th>
                </tr>

            </tbody>

        </table>
        <br>
        <div class="centered">Support By : <a style="text-decoration: none" href="https://wardan.tech/" target="_blank"><b>Wardan Tech Ltd</b></a></div>

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
