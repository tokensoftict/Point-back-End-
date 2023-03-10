<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Print Invoice #{{ $invoice->id }}</title>
    <style>
        html, body {
            margin: 0;
            padding: 0;
            font-size: 9pt;
            background-color: #fff;
        }

        #products {
            width: 90%;
        }
        #products th, #products td {
            padding-top:5px;
            padding-bottom:5px;
            border: 1px solid black;
        }
        #products tr td {
            font-size: 8pt;
        }

        #printbox {
            width: 98%;
            margin: 5pt;
            padding: 5px;
            margin: 0px auto;
            text-align: justify;
        }

        .inv_info tr td {
            padding-right: 10pt;
        }

        .product_row {
            margin: 15pt;
        }

        .stamp {
            margin: 5pt;
            padding: 3pt;
            border: 3pt solid #111;
            text-align: center;
            font-size: 20pt;
            color:#000;
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>
<div id="printbox">
    <table width="65%">
        <tr><td valign="top">
                <h2  class="text-center">{{ $store->name}}</h2>
                <p align="center">
                    {{ $store->address_1 }}
                    @if(!empty($store->address_2))
                        <br/>
                        {{ $store->address_2 }}
                    @endif
                    @if(!empty($store->contact_number))
                        <br/>
                        {{ $store->contact_number }}
                    @endif
                </p>
            </td>
        </tr>
        <td valign="top" width="35%">
            <img style="max-height:100px;float: right;" src="{{ public_path("img/". $store->logo) }}" alt='Logo'>
        </td>
    </table>


    <table  class="inv_info">

        <tr>
            <td>Invoice Number</td>
            <td>{{ $invoice->invoice_number }}</td>
        </tr>

        <tr>
            <td>Invoice Date</td>
            <td>{{ convert_date($invoice->invoice_date)  }}</td>
        </tr>
        <tr>
            <td>Time</td>
            <td>{{ date("h:i a",strtotime($invoice->sales_time)) }}</td>
        </tr>
        <tr>
            <td>Customer</td>
            <td>{{ $invoice->customer->firstname }} {{ $invoice->customer->lastname }}</td>
        </tr>
        <tr>
            <td>Status</td>
            <td>{{ $invoice->status->name }}</td>
        </tr>
        <tr>
        @if($invoice->status->name === "Complete")
        <tr>
            <td>Mode of Payment</td>
            <td>
                @if($invoice->paymentMethodTable->count() > 1)
                    @php
                        $methods = [];
                    @endphp

                    @foreach($invoice->paymentMethodTable->get() as $meth)
                        @php
                            echo  $meth->payment_method->name." : ". number_format( $meth->amount,2)."<br/>";
                        @endphp
                    @endforeach


                @else
                    {{ $invoice->paymentMethodTable->first()->payment_method->name  }} : {{ number_format($invoice->paymentMethodTable->first()->amount,2) }}
                @endif
            </td>
        </tr>
        @endif
        <tr>
            <td>Credit Balance</td>
            <td>{{ number_format($invoice->customer->credit_balance,2) }}</td>
        </tr>
    </table>


    <h2 style="margin-top:0" class="text-center">Sales Invoice / Receipt</h2>

    <table id="products">
        <tr class="product_row">
            <td>#</td>
            <td align="left"><b>Name</b></td>
            <td align="center"><b>Quantity</b></td>
            <td align="center"><b>Price</b></td>
            <td align="right"><b>Total</b></td>
        </tr>
        <tbody id="appender">
        @foreach($invoice->invoice_items as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td align="left" class="text-left">{{ $item->stock->name }}</td>
                <td align="center" class="text-center">{{ $item->quantity }}</td>
                <td align="center" class="text-center">{{ number_format($item->selling_price,2) }}</td>
                <td align="right" class="text-right">{{ number_format(($item->total_selling_price),2) }}</td>
            </tr>
        @endforeach
        </tbody>
        <tfoot>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td  align="right" class="text-right">Sub Total</td>
            <td  align="right" class="text-right">{{ number_format($invoice->sub_total,2) }}</td>
        </tr>
        @if($invoice->status->name === "Complete" && isset( $invoice->payment))
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td  align="right" class="text-right">Discount</td>
                <td  align="right" class="text-right"><b>{{ number_format(($invoice->payment->discount),2) }}</b></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td  align="right" class="text-right">Total Paid</td>
                <td  align="right" class="text-right"><b>{{ number_format(($invoice->payment->total_paid),2) }}</b></td>
            </tr>
        @else
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td  align="right" class="text-right">Total</td>
                <td  align="right" class="text-right"><b>{{ number_format(($invoice->sub_total),2) }}</b></td>
            </tr>
        @endif
        </tfoot>
    </table>

    <div class="text-center">  {{ $store->receipt_notes }}</div>
    <br/>
    <div align="center">

    </div>
    <br/>
    <div class="text-center"> {!! softwareStampWithDate() !!}</div>
</div>
</body>
</html>

