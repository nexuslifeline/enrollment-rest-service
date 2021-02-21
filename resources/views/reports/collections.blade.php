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
            font-family: Arial;
            text-align: left;
        }

        .table-container {
            margin-top: 10px;
            width: 100%;
            text-align: left;
        }

        .no-records {
            padding: 20px;
            background-color: white;
            border: 1px solid black;
        }

        table {
            font-size: 12px;
            width: 100%;
            border-collapse: collapse;

            thead {
                th {
                    padding: 5px;
                    border: 1px solid black;
                    font-size: 11px;
                    background-color: #9cf0bb;
                }
            }

           tbody {
                td {
                    padding: 5px;
                    height: 15px;
                    border: 1px solid black;
                }
           }

           tfoot {
                td {
                    padding: 5px;
                    height: 15px;
                    border: 1px solid black;
                }
           }
        }

    </style>
</head>
<body>
    @include('partials.header')
    @include('partials.title', ['title' => 'Collection Report', 'subtitle' => $date_from . ' to ' . $date_to])

    <div class="table-container">
        <table>
           <thead>
                <tr>
                    <th width="20%" align="left">STUDENT</th>
                    <th width="12%" align="left">TRANSACTION NO</th>
                    <th width="12%" align="left">REFERENCE NO</th>
                    <th width="12%" align="left">PAYMENT MODE</th>
                    <th width="11%" align="left">BILLING NO</th>
                    <th width="10%" align="left">DATE PAID</th>
                    <!-- <th style="width: 12%;">Posted By</th> -->
                    <th width="10%" align="right">AMOUNT</th>
                </tr>
           </thead>
           <tbody>
                @if (count($payments) > 0)
                    @foreach ($payments as $payment)
                    <tr>
                        <td align="left">
                            <div style="margin-bottom: 10px;">{{ $payment->student->student_no }}</div>
                            <div >{{ $payment->student->name }}</div>
                        </td>
                        <td align="left">{{ $payment->transaction_no }}</td>
                        <td align="left">{{ $payment->reference_no }}</td>
                        <td align="left">{{ $payment->paymentMode->name }}</td>
                        <td align="left">{{ $payment->billing->billing_no }}</td>
                        <td align="left">{{ date_format(date_create($payment->date_paid),'m/d/Y')  }}</td>
                        <!-- <td></td> -->
                        <td align="right">{{ number_format($payment->amount, 2) }}</td>
                    </tr>
                    @endforeach
                @else
                <tr>
                    <td colspan="7" align="center" class="no-records">
                        No record(s) found.
                    </td>
                </tr>
                @endif
           </tbody>
           <tfoot>
                <tr>
                    <td colspan="6" align="right">
                        <b>TOTAL:</b>
                    </td>
                    <td align="right">
                        <b>{{ number_format(array_sum(array_column(iterator_to_array($payments), 'amount')), 2) }}</b>
                    </td>
                </tr>
           </tfoot>
        </table>
    </div>
</body>
</html>