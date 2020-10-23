<!DOCTYPE html>
<html>

<head>
  <title>Statement of Account</title>

  <style type="text/css">
    @page {
      sheet-size: A4;
    }

    body {
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
      border-bottom: 1px solid gray;
      font-size: 8.5pt;
    }

    .student-label {
      font-weight: bold;
    }

    .table__previous-billing-info {
      width: 100%;
      /* border-bottom: 1px solid gray; */
      border-collapse: collapse;
      font-size: 8.5pt;
    }

    .table__previous-billing-info td {
      padding-top: 3px;
      padding-bottom: 3px;
    }

    .table__billing-info {
      width: 100%;
      /* border-bottom: 1px solid gray; */
      border-collapse: collapse;
      font-size: 8.5pt;
    }

    .table__billing-info td {
      padding-top: 5px;
      padding-bottom: 5px;
    }

    .table__billing-details tr td {
      padding: 5px 0;
    }

    .table__payment-history tr td {
      padding: 5px 5px;
    }

    .table__current-billing-details tr td {
      border: 1px solid lightgray;
      border-collapse: collapse;
    }

    .table__current-billing-details tr td {
      padding: 5px 5px;
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
      <td class="student-label" style="width: 85px">Student No. :</td>
      <td>{{$student->student_no}}</td>
      <td colspan="2"></td>
      <td class="student-label">{{$academicRecord->course ? "Course :" : ""}}</td>
      <td>{{$academicRecord->course ? $academicRecord->course->name : ""}}</td>
      <td class="student-label">Term :</td>
      <td>{{$billing->term->name}}</td>
    </tr>
    <tr>
      <td class="student-label">Student :</td>
      <td style="width: 150px;">{{$student->last_name.', '.$student->first_name.' '.$student->middle_name}}</td>
      <td colspan="2"></td>
      <td class="student-label">{{$academicRecord->semester ? "Semester :" : ""}}</td>
      <td>{{$academicRecord->semester ? $academicRecord->semester->name : ""}}</td>
      <td class="student-label" style="width: 100px;">Generate Date :</td>
      <td>{{date('F j, Y', strtotime($billing->created_at))}}</td>
    </tr>
    <tr>
      <td class="student-label">Level :</td>
      <td>{{$academicRecord->level->name}}</td>
      <td class="student-label" style="width: 55px;">Section :</td>
      <td>{{$academicRecord->section->name}}</td>
      <td class="student-label" style="width: 85px;">School Year :</td>
      <td>{{$academicRecord->schoolYear->name}}</td>
      <td class="student-label">Due Date :</td>
      <td>{{date('F j, Y', strtotime($billing->created_at))}}</td>
    </tr>
  </table>
  <br>
  <table style="width: 100%;">
    <tr>
      <td>
        <table class="table__payment-history" style="width: 100%; border-collapse: collapse; margin: 0 15px; border-right: 1px solid gray;">
          <tr>
            <td colspan="4" style="text-align: center;">
              <h3>PAYMENT HISTORY</h3>
            </td>
          </tr>
          <tr>
            <td style="border-bottom: 1px solid gray; width: 10%;"></td>
            <td style="border-bottom: 1px solid gray; width: 30%; font-weight: bold;">DATE PAID</td>
            <td style="border-bottom: 1px solid gray; width: 30%; font-weight: bold;">O.R. NUMBER</td>
            <td style="border-bottom: 1px solid gray; width: 30%; font-weight: bold; text-align: right;">AMOUNT PAID</td>
          </tr>
          @foreach ($terms as $term)
          <tr>
            <td colspan="4" style="text-align: left; font-weight: bold;">{{$term->name}}</td>
          </tr>
          @if ($term->billing)
          @if(count($term->billing->payments) > 0)
          @foreach ($term->billing->payments as $payment)
          <tr>
            <td></td>
            <td>{{date('M j, Y', strtotime($payment->date_paid))}}</td>
            <td>{{$payment->reference_no}}</td>
            <td style="text-align: right;">{{number_format($payment->amount,2)}}</td>
          </tr>
          @endforeach
          @else
          <tr>
            <td colspan="4" style="text-align: center">No Payment</td>
          </tr>
          @endif
          @endif
          @endforeach
        </table>
      </td>
      <td style="width: 40%; vertical-align: top;">
        <table class="table__billing-details" style="width: 100%; border-collapse: collapse; font-size: 10pt;">
          <tr>
            <td colspan="4" style="text-align: center;">
              <h3>BILLING</h3>
            </td>
          </tr>
          <tr>
            <td>Previous Billing : </td>
            <td style="text-align: right;">{{number_format($previousBilling ? $previousBilling->total_amount : 0,2)}}</td>
          </tr>
          <tr>
            <td>Less Payment : </td>
            <td style="text-align: right; border-bottom: 1px solid gray">({{number_format($previousBilling && $previousBilling->payments ? $previousBilling->payments->sum('amount') : 0, 2)}})</td>
          </tr>
          <tr>
            <td>Balance : </td>
            <td style="text-align: right;">
              {{$billing->previous_balance > 0 ? number_format($billing->previous_balance, 2) : '('.number_format(abs($billing->previous_balance), 2).')'}}
            </td>
          </tr>
          <tr>
            <td style="border-bottom: 1px solid gray">Current Billing : </td>
            <td style="text-align: right; border-bottom: 1px solid gray">{{number_format($billing->total_amount, 2)}}</td>
          </tr>
          <tr style="background-color: lightgray;">
            <td style="font-size: 11pt; font-weight: bold">TOTAL AMOUNT DUE : </td>
            <td style="text-align: right; font-size: 11pt; font-weight: bold">{{number_format($billing->total_amount + $billing->previous_balance, 2)}}</td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
  <br>
  <div style="width: 80%; text-align: center; font-size: 11pt; font-weight: bold; margin: 15px auto; padding: 5px 0 ;background-color: lightgray;">
    CURRENT BILLING DETAILS
  </div>
  <table class="table__current-billing-details" style="width: 80%; border-collapse:collapse; margin: auto;">
    <tr>
      <td style="width: 75%; font-weight:bold;">FEES</td>
      <td style="width: 25%; text-align: right; font-weight:bold;">AMOUNT</td>
    </tr>
    @foreach ($billing->billingItems as $item)
    <tr>
      <td>{{$item->term ? $item->term->name : $item->schoolFee->name}}</td>
      <td style="text-align: right;">{{number_format($item->amount,2)}}</td>
    </tr>
    @endforeach
    <tr><td colspan=2 style="border: none"></td></tr>
    <tr>
      <td style="text-align: right; font-size: 11pt; font-weight: bold; border: none;">
        TOTAL BILLING :
      </td>
      <td style="text-align: right; font-size: 11pt; font-weight: bold; border: none; background-color: lightgray">
        {{number_format($billing->total_amount, 2)}}
      </td>
    </tr>
  </table>

  </div>
</body>

</html>