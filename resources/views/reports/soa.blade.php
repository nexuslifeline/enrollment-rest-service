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
      font-size: 10pt;
      text-transform: capitalize;
    }

    .table__student-info  td{
      font-size: 8.5pt;
      text-transform: capitalize;
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
      text-transform: capitalize;
    }

    .table__billing-details tr td {
      padding: 5px 0;
      text-transform: capitalize;
    }

    .table__payment-history tr td {
      padding: 5px 5px;
      text-transform: capitalize;
    }

    .table__current-billing-details tr td {
      border: 1px solid lightgray;
      border-collapse: collapse;
    }

    .table__current-billing-details tr td {
      padding: 5px 5px;
      text-transform: capitalize;
    }

    .table__previous-billing {
      width: 100%;
      border-collapse: collapse;
      border-right: 1px solid gray;
      margin: 0 15px;
      /* border-top: 1px solid gray; */
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
      <td style="width: 30%;" class="student-label">
        STUDENT NO :
      </td>
      <td style="width: 50%;" class="student-label">
        COURSE :
      </td>
      <td style="width: 20%;" class="student-label">
        TERM :
      </td>
    </tr>
    <tr>
      <td style="width: 30%;">
        {{$student->student_no}}
      </td>
      <td style="width: 50%; text-transform: none;">
        {{$academicRecord->course ? $academicRecord->course->description : ""}}
      </td>
      <td style="width: 20%;">
        {{$billing->term->name}}
      </td>
    </tr>

    <tr>
      <td style="width: 30%;" class="student-label">
        NAME :
      </td>
      <td style="width: 50%;" class="student-label">
        SEMESTER :
      </td>
      <td style="width: 20%;" class="student-label">
        DATE GENERATED :
      </td>
    </tr>

    <tr>
      <td style="width: 30%;">
        {{$student->last_name.', '.$student->first_name.' '.$student->middle_name}}
      </td>
      <td style="width: 50%; text-transform: none;">
        {{$academicRecord->semester ? $academicRecord->semester->name : ""}}
      </td>
      <td style="width: 20%;">
        {{date('F j, Y', strtotime($billing->created_at))}}
      </td>
    </tr>

    <tr>
      <td style="width: 100%" colspan=3>
        <table style="width: 100%; padding:-2px; margin: 0px;" >
            <tr>
              <td style="width: 30%;" class="student-label">LEVEL</td>
              <td style="width: 25%; padding-left:14px;" class="student-label">SCHOOL YEAR : </td>
              <td style="width: 25%" class="student-label">SECTION : </td>
              <td style="width: 20%; padding-left:18px;" class="student-label">DUE DATE</td>
            </tr>
            <tr>
              <td style="width: 30%;">{{$academicRecord->level->name}}</td>
              <td style="width: 25%; padding-left:14px; text-transform: none;"> {{$academicRecord->schoolYear->name}} </td>
              <td style="width: 25%;"> {{$academicRecord->section->name}} </td>
              <td style="width: 20%; padding-left:18px;" >{{date('F j, Y', strtotime($billing->due_date))}}</td>
            </tr>
        </table>
      </td>
    </tr>
  </table>
  <br>
  <table style="width: 100%;">
    <tr>
      <td style="width: 60%; vertical-align: top;">
        <table class="table__payment-history" style="width: 100%; border-collapse: collapse; margin: 0 15px; border-right: 1px solid gray;">
          <tr>
            <td colspan="4" style="text-align: center;">
              <h3>PAYMENT HISTORY</h3>
            </td>
          </tr>
          <tr>
            <td style="border-bottom: 1px solid gray; width: 20%;"></td>
            <td style="border-bottom: 1px solid gray; width: 25%; font-weight: bold;">DATE PAID</td>
            <td style="border-bottom: 1px solid gray; width: 25%; font-weight: bold; text-transform: none;">O.R. Number</td>
            <td style="border-bottom: 1px solid gray; width: 30%; font-weight: bold; text-align: right;">AMOUNT PAID</td>
          </tr>
          @if($previousBilling && count($previousBilling->payments) != 0)
          @foreach ($previousBilling->payments as $key=>$payment)
          <tr>
            <td style="text-align: left; font-weight: bold;">{{$key === 0 ? $previousBilling->term->name : ''}}</td>
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
        </table>
        @if ($previousBilling)
        <table class="table__previous-billing">
          <tr>
            <td colspan="4" style="text-align: center; padding-top: 15px;">
              <h3>Previous Billing</h3>
            </td>
          </tr>
          <tr>
            <td style="border-bottom: 1px solid gray; width: 20%; font-weight: bold;">Term</td>
            <td style="border-bottom: 1px solid gray; width: 34%; font-weight: bold;">Billing No.</td>
            <td style="border-bottom: 1px solid gray; width: 22%; font-weight: bold;">Due Date</td>
            <td style="border-bottom: 1px solid gray; width: 22%; font-weight: bold; text-align: right;">Balance</td>
          </tr>
          <tr>
            <td style="text-align: left; font-weight: bold;">{{ $previousBilling->term->name }}</td>
            <td>{{$previousBilling->billing_no}}</td>
            <td>{{date('M j, Y', strtotime($previousBilling->due_date))}}</td>
            <td style="text-align: right;">{{number_format($previousBilling->total_amount + $previousBilling->previous_balance,2)}}</td>
          </tr>
        </table>
        @endif
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
            <td style="text-align: right;">{{number_format($previousBilling ? $previousBilling->total_amount + $previousBilling->previous_balance : $billing->previous_balance, 2)}}</td>
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
      <td style="width: 75%; font-weight:bold;" colspan=2>FEES</td>
      <td style="width: 25%; text-align: right; font-weight:bold;">AMOUNT</td>
    </tr>
    @foreach ($billing->billingItems as $item)
    <tr>
      <td colspan=2>{{$item->term ? $item->term->name : $item->schoolFee->name}}</td>
      <td style="text-align: right;">{{number_format($item->amount,2)}}</td>
    </tr>
    @endforeach
    <!-- <tr><td colspan=2 style="border: none"></td></tr> -->
    <tr>
      <td style="border: none"></td>
      <td style="text-align: right; font-weight: bold; width: 20%;">
        TOTAL :
      </td>
      <td style="text-align: right; font-weight: bold;">
        {{number_format($billing->total_amount, 2)}}
      </td>
    </tr>
  </table>

  </div>
</body>

</html>