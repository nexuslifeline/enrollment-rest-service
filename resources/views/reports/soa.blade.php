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
        <br>
        <table style="width: 100%;">
          <tr>
            <td style="width: 60%; text-align: center;">
              <table style="width: 100%; border-collapse: collapse; margin: 0 15px; border-right: 1px solid black;">
                <tr>
                  <td colspan="4"><h3>PAYMENT HISTORY</h3></td>
                </tr>
                <tr>
                  <td style="border-bottom: 1px solid black"></td>
                  <td style="border-bottom: 1px solid black">AMOUNT PAID</td>
                  <td style="border-bottom: 1px solid black">DATE PAID</td>
                  <td style="border-bottom: 1px solid black">O.R. NUMBER</td>
                </tr>
                @foreach ($terms as $term)
                <tr>
                  <td colspan="4" style="text-align: left;">{{$term->name}}</td>
                </tr>
                @if ($term->billing)
                @foreach ($term->billing->payments as $payment)
                <tr>
                  <td></td>
                  <td>{{number_format($payment->amount,2)}}</td>
                  <td>{{date('F j, Y', strtotime($payment->date_paid))}}</td>
                  <td>{{$payment->reference_no}}</td>
                </tr>
                @endforeach
                @endif
                @endforeach
              </table>
            </td>
            <td style="width: 40%; vertical-align: top;">
              <table style="width: 100%; border-collapse: collapse;">
                <tr>
                  <td>Previous Billing : </td>
                  <td style="text-align: right;">{{number_format($previousBilling ? $previousBilling->total_amount : 0,2)}}</td>
                </tr>
                <tr>
                  <td>Less Payment : </td>
                  <td style="text-align: right; border-bottom: 1px solid black">({{number_format($previousBilling && $previousBilling->payments ? $previousBilling->payments->sum('amount') : 0, 2)}})</td>
                </tr>
                <tr>
                  <td>Balance : </td>
                  <td style="text-align: right;">{{number_format($billing->previous_balance, 2)}}</td>
                </tr>
                <tr>
                  <td style="border-bottom: 1px solid black">Current Billing : </td>
                  <td style="text-align: right; border-bottom: 1px solid black">{{number_format($billing->total_amount, 2)}}</td>
                </tr>
                <tr>
                  <td style="font-size: 10pt; font-weight: bold">TOTAL AMOUNT DUE : </td>
                  <td style="text-align: right; font-size: 10pt; font-weight: bold">{{number_format($billing->total_amount + $billing->previous_balance, 2)}}</td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
        <br>
        <table style="width: 100%; border-collapse:collapse">
          <tr>
            <td colspan="2" style="text-align: center; font-size: 10pt;"><h4>CURRENT BILLING DETAILS</h4></td>
          </tr>
          <tr>
            <td style="width: 70%; border-bottom: 1px solid black">ITEM</td>
            <td style="text-align: right; border-bottom: 1px solid black">AMOUNT</td>
          </tr>
          @foreach ($billing->billingItems as $item)
          <tr>
            <td>{{$item->term ? $item->term->name : $item->schoolFee->name}}</td>
            <td style="text-align: right;">{{number_format($item->amount,2)}}</td>
          </tr>
          @endforeach
          <tr>
            <td style="border-top: 1px solid black; font-size: 9pt; font-weight: bold;">TOTAL BILLING</td>
            <td style="text-align: right; border-top: 1px solid black; font-size: 9pt; font-weight: bold;">{{number_format($billing->total_amount,2)}}</td>
          </tr>
        </table>
      </div>
    </body>
</html>