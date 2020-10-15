<!DOCTYPE html>
<html lang="en">
<head>
    <title>Collection Report</title>
    <style type="text/css" media="print">

        @page {
            size: landscape;
            margin: 0.3in;
        }

        body {
            font-family: Calibri;
        }

        .header__name {
            font-size: 13pt;
            font-weight: bold;
        }
        .header__details {
            font-size: 11pt;
            font-weight: bold;
        }

        .report-headers {
            width: 100%;
            text-align: center;
        }

        .report__title {
            margin-top: 20px;
            font-weight: bold;
        }

        .report__sub-title {
            font-size: 8pt;
        }

        .table-container {
            margin-top: 10px;
            width: 100%;
        }

        .table-details {
            width: 100%;
            /* border: solid 0.5px lightgray; */
            font-size: 8pt;
        }

        table {
            border-collapse: collapse;
        }

        th {
            padding: 5px;
            height: 30px;
            text-align: left;
            border: solid 1.5px black;
            font-size: 9pt;
        }
        td {
            padding: 5px;
            height: 15px;
            text-align: left;
            font-size: 8pt;
            border: 1px solid lightgray;
        }

        .report-footer  {
            width: 100%;
            margin-top: 10px;
            font-size: 9pt;
            text-align: right;
            padding-right: 4px;
            font-weight: bold;
        }

    </style>
</head>
<body>
    <div class="report-headers">
        <div class="header__name">{{$organization->name}}</div>
        <div class="header__details">{{$organization->address}}</div>
        <div class="header__details">{{$organization->telephone_no}}</div>
        <div class="header__details">{{$organization->email_address}}</div>
        <div class="report__title">COLLECTION REPORT</div>
        <div class="report__sub-title">From: 2020-10-01 To: 2020-10-30</div>
    </div>
    <div class="table-container">
        <table class="table-details">
            <tr>
                <th style="width: 20%; ">Student</th>
                <th style="width: 12%;  text-align: left;">Transaction No</th>
                <th style="width: 12%; ">Reference No</th>
                <th style="width: 12%; ">Payment Mode</th>
                <th style="width: 11%; ">Billing No</th>
                <th style="width: 10%; ">Date Paid</th>
                <th style="width: 12%;">Posted By</th>
                <th style="width: 10%; text-align: right;">Amount</th>
            </tr>
            @foreach ($payments as $payment)
            <tr>
                <td>
                    <div style="margin-bottom: 10px;">{{ $payment->student->student_no }}</div>
                    <div>{{ $payment->student->name }}</div>
                </td>
                <td>{{ $payment->transaction_no }}</td>
                <td>{{ $payment->reference_no }}</td>
                <td>{{ $payment->paymentMode->name }}</td>
                <td>{{ $payment->billing->billing_no }}</td>
                <td>{{ $payment->date_paid }}</td>
                <td></td>
                <td style="text-align: right;">{{ number_format($payment->amount, 2) }}</td>
            </tr>
            @endforeach
        </table>
    </div>
    <div class="report-footer">
        <span>TOTAL AMOUNT : </span>
        {{ number_format(array_sum(array_column(iterator_to_array($payments), 'amount')), 2) }}
    </div>
</body>
</html>