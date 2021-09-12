<!DOCTYPE html>
<html>

<head>
  <title>Billing</title>

  <style type="text/css">
    @page {
      sheet-size: A4;
    }

    body {
      font-family: Calibri;
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
      text-align: right;
    }

    .table__header tr .td__info {
      text-align: center;
      width: 60%;
    }

    .header__name {
      font-size: 13pt;
      font-weight: bold;
    }

    .header__details {
      font-size: 11pt;
      font-weight: bold;
    }

    .float-right {
      text-align: right
    }

    .float-center {
      text-align: center
    }

    .total {
      font-size: 10pt;
      font-weight: bold;
    }

    .table__name-subjects {
      font-size: 10pt;
      font-weight: bold;
      margin: 10px 0;
    }

    .table__name-fees {
      font-size: 10pt;
      font-weight: bold;
    }

    .title {
      font-size: 12pt;
      font-weight: bold;
      text-align: center;
      margin-bottom: 15px;
    }

    hr {
      margin: 2px 0;
    }

    .student__info td {
      text-transform: capitalize;
    }

    .items {
      width: 100%;
      /* border: 1px solid black; */
      border-collapse: collapse;
    }

    .items thead tr {
      background-color: lightgray;
    }

    .items tr td {
      padding: 3px 5px;
      border: 1px solid black;
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
        <div class="header__name">{{ $organization->name }}</div>
        <div class="header__details">{{ $organization->address }}</div>
        <div class="header__details">{{ $organization->telephone_no }}</div>
        <div class="header__details">{{ $organization->email_address }}</div>
      </td>
      <td></td>
    </tr>
  </table>
  <div class="title">BILLING</div>
  <table class="student__info" style="width: 100%">
    <tr>
      <td style="width: 50%; font-weight: bold;">Student No. : </td>
      <td style="width: 50%; font-weight: bold;">Level : </td>
    </tr>
    <tr>
      <td style="width: 28%">{{ $billing->student->student_no ? $billing->student->student_no : 'Awaiting Confirmation' }}</td>
      <td style="width: 40%; text-transform: none;">{{ $billing->academicRecord->level->name }}</td>
    </tr>
    <tr>
      <td style="font-weight: bold;">Name : </td>
      @if($billing->academicRecord->course_id)
      <td style="font-weight: bold;">Course : </td>
      @endif
    </tr>

    <tr>
      <td>{{ $billing->student->last_name .', '. $billing->student->first_name .' '. $billing->student->middle_name }}</td>
      @if($billing->academicRecord->course_id)
      <td style="text-transform: none;">{{ $billing->academicRecord->course->description }}</td>
      @endif
    </tr>

    <tr>
      <td style="font-weight: bold;">Address : </td>
      <td style="font-weight: bold;">School Year : </td>
    </tr>

    <tr>
      <td>{{ $billing->student->current_address ? $billing->student->current_address : $billing->student->permanent_address }}</td>
      <td style="text-transform: none;">{{ $billing->academicRecord->semester_id ? $billing->academicRecord->semester->name.' /' : '' }} {{ $billing->academicRecord->schoolYear->name }}</td>
    </tr>
    <tr>
      <td style="width: 50%; font-weight: bold;">Billing No. : </td>
      <td style="width: 50%; font-weight: bold;">Due Date : </td>
    </tr>
    <tr>
      <td style="width: 28%">{{ $billing->billing_no }}</td>
      <td style="width: 40%; text-transform: none;">{{ $billing->due_date }}</td>
    </tr>
  </table>
  <br><br>
  <table class="items">
    <thead>
      <tr>
        <td style="font-weight: bold;">FEE</td>
        <td class="float-right" style="font-weight: bold;">AMOUNT</td>
      </tr>
    </thead>
    @foreach ($billing->billingItems as $item)
    <tr>
      <td>{{ $item->schoolFee->name }}</td>
      <td class="float-right">{{ number_format($item->amount, 2) }}</td>
    </tr>
    @endforeach
    <tr>
      <td class="float-right total" style="border: none">TOTAL</td>
      <td class="float-right total" style="border: none">{{ number_format($billing->total_amount, 2) }}</td>
    </tr>
  </table>
</body>

</html>