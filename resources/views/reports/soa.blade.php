<!DOCTYPE html>
<html>
    <head>
        <title>Statement of Account</title>

        <style type="text/css">
            @page { sheet-size: A4; }
            body{
                font-family: 'Calibri';
                font-size: 9pt;
            }
            @page {
                margin: 0.4in;
            }
            .table__header {
                width: 100%;
                margin-bottom: 10px;
            }
            .table__header tr .td__logo {
                width: 20%;
                text-align: right
            }
            .table__header tr .td__info {
                text-align: center;
                width: 60%
            }
            .header__name {
                font-size: 13pt;
                font-weight: bold;
            }
            .header__details {
                font-size: 11pt;
                font-weight: bold;
            }
            .title {
                font-size: 12pt;
                font-weight: bold;
                text-align: center;
                margin-bottom: 15px;
            }
            .table__student-info {
                width: 100%;
                border-bottom: 1px solid black;
                font-size: 8.5pt;
            }
            .table__previous-billing-info {
                width: 100%;
                /* border-bottom: 1px solid black; */
                border-collapse: collapse;
                font-size: 8.5pt;
            }
            .table__previous-billing-info td {
              padding-top: 3px;
              padding-bottom: 3px;
            }
            .table__billing-info {
                width: 100%;
                /* border-bottom: 1px solid black; */
                border-collapse: collapse;
                font-size: 8.5pt;
            }
            .table__billing-info td {
              padding-top: 5px;
              padding-bottom: 5px;
            }
            hr {
                margin: 2px 0;
            }
        </style>
    </head>
    <body>
        <table class="table__header">
            <tr>
                <td class="td__logo">
                    <img src="{{url('storage/organization-logo/'.$organization->organizationLogo->hash_name)}}" style="height: 90px; width: 90px;">
                </td>
                <td class="td__info">
                    <div class="header__name">{{$organization->name}}</div>
                    <div class="header__details">{{$organization->address}}</div>
                    <div class="header__details">{{$organization->telephone_no}}</div>
                    <div class="header__details">{{$organization->email_address}}</div>
                </td>
                <td></td>
            </tr>
        </table>
        <div class="title">STATEMENT OF ACCOUNT</div>
        <table class="table__student-info">
          <tr>
            <td style="width: 75px">Student No. :</td>
            <td>{{$student->student_no}}</td>
            <td colspan="2"></td>
            <td>{{$academicRecord->course ? "Course :" : ""}}</td>
            <td>{{$academicRecord->course ? $academicRecord->course->name : ""}}</td>
            <td>Term :</td>
            <td>{{$billing->term->name}}</td>
          </tr>
          <tr>
            <td>Student :</td>
            <td style="width: 150px;">{{$student->last_name.', '.$student->first_name.' '.$student->middle_name}}</td>
            <td colspan="2"></td>
            <td>{{$academicRecord->semester ? "Semester :" : ""}}</td>
            <td>{{$academicRecord->semester ? $academicRecord->semester->name : ""}}</td>
            <td style="width: 100px;">Generate Date :</td>
            <td>{{date('F j, Y', strtotime($billing->created_at))}}</td>
          </tr>
          <tr>
            <td>Level :</td>
            <td>{{$academicRecord->level->name}}</td>
            <td style="width: 50px;">Section :</td>
            <td>{{$academicRecord->section->name}}</td>
            <td style="width: 75px;">School Year :</td>
            <td>{{$academicRecord->schoolYear->name}}</td>
            <td>Due Date :</td>
            <td>{{date('F j, Y', strtotime($billing->created_at))}}</td>
          </tr>
        </table>
        @if ($previousBilling)
        <br>
        <div style="font-size: 10pt; font-weight: bold;">PREVIOUS BILLING</div>
        <table class="table__previous-billing-info">
          <tr>
            <td style="width: 50%; padding-left: 15px;">{{$previousBilling->billing_no}} - {{$previousBilling->term->name}}</td>
            <td style="width: 50%; text-align: right;">{{number_format($previousBilling->total_amount + $previousBilling->previous_balance,2)}}</td>
          </tr>
          @if (count($previousBilling->payments))
          <tr>
            <td style="padding-left: 15px;">Payments</td>
          </tr>
          @foreach ($previousBilling->payments as $payment)
          <tr>
            <td style="padding-left: 30px;">{{$payment->transaction_no}} - {{date('F j, Y', strtotime($payment->date_paid))}}</td>
            <td style="text-align: right;">({{number_format($payment->amount,2)}})</td>
          </tr>
          @endforeach
          @endif
          <tr>
            <td style="border-top: 1px solid black; font-size: 9pt; font-weight: bold;">REMAINING BALANCE</td>
            <td style="text-align: right; border-top: 1px solid black; font-size: 9pt; font-weight: bold;">{{number_format($billing->previous_balance,2)}}</td>
          </tr>
        </table>
        @endif
        <br>
        <div style="font-size: 10pt; font-weight: bold;">CURRENT BILLING</div>
        <table class="table__billing-info">
          <tr>
            <td style="width: 50%; padding-left: 15px;">{{$billing->billing_no}} - {{$billing->term->name}}</td>
            <td style="width: 50%; text-align: right;">{{number_format($billing->total_amount,2)}}</td>
          </tr>
          <tr>
            <td style="border-top: 1px solid black; font-size: 10pt; font-weight: bold;">TOTAL AMOUNT DUE</td>
            <td style="text-align: right; border-top: 1px solid black; font-size: 10pt; font-weight: bold;">{{number_format($billing->previous_balance + $billing->total_amount,2)}}</td>
          </tr>
        </table>
      </div>
    </body>
</html>