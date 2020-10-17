<!DOCTYPE html>
<html lang="en">
<head>
    <title>STUDENT LEDGER</title>
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
            height: 45px;
        }
        td {
            padding: 5px;
            height: 15px;
            text-align: left;
            font-size: 8pt;
            border: 1px solid lightgray;
            height: 45px;
        }

        .report-footer  {
            width: 100%;
            margin-top: 10px;
            font-size: 9pt;
            text-align: right;
            padding-right: 4px;
            font-weight: bold;
        }

        .student {
            margin: 10px 0;
            font-size: 9pt;
        }

    </style>
</head>
<body>
    <div class="report-headers">
        <div class="header__name">{{$organization->name}}</div>
        <div class="header__details">{{$organization->address}}</div>
        <div class="header__details">{{$organization->telephone_no}}</div>
        <div class="header__details">{{$organization->email_address}}</div>
        <div class="report__title">STUDENT LEDGER</div>
        <div class="report__sub-title">AS OF DATE: {{ $as_of_date }}</div>
    </div>
    <div class="student">
        <div>Student No : <br><strong>{{ $student->student_no }}</strong></div>
        <div>Name       : <br><strong>{{ $student->name }}</strong></div>
    </div>
    <div class="table-container">
        <table class="table-details">
            <tr>
                <th style="width: 10%; text-align: left;">DATE</th>
                <th style="width: 20%; text-align: left;">DETAILS</th>
                <th style="width: 15%; text-align: left;">REFERENCE NO</th>
                <th style="width: 18%; text-align: right;">DEBIT</th>
                <th style="width: 18%; text-align: right;">CREDIT</th>
                <th style="width: 18%; text-align: right;">BALANCE</th>
            </tr>
            @foreach ($ledgers as $ledger)
            <tr>
                <td>{{ date_format(date_create($ledger->txn_date),'m/d/Y')  }}</td>
                <td>{{ $ledger->txn_type }}</td>
                <td>{{ $ledger->reference }}</td>
                <td style="text-align: right;">{{ number_format($ledger->debit, 2) }}</td>
                <td style="text-align: right;">{{ number_format($ledger->credit, 2) }}</td>
                <td style="text-align: right;">{{ number_format($ledger->balance, 2) }}</td>
            </tr>
            @endforeach
        </table>
    </div>
</body>
</html>