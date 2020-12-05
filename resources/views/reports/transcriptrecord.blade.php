<!DOCTYPE html>
<html>

<head>
  <title>TRANSCRIPT OF RECORDS</title>

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
      width: 15%;
      text-align: right
    }

    .table__header tr .td__info {
      text-align: center;
      width: 70%
    }

    .header__name {
      font-size: 13pt;
      font-weight: bold;
    }

    .header__details {
      font-size: 11pt;
      font-weight: bold;
    }

    .header__details-other {
      font-size: 8pt;
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
        <div class="header__details">Member: Philippine Association on Colleges and Universities Commission on Accreditation Inc. (PACUCOA)</div>
      </td>
      <td></td>
    </tr>
  </table>
  <div class="title">OFFICIAL TRANSCRIPT OF RECORD</div>
  {{-- <table class="table__student-info">
    <!-- <tr>
      <td class="student-label" style="width: 85px">Student No. :</td>
      <td>{{$student->student_no}}</td>
      <td colspan="2"></td>
      <td class="student-label">{{$academicRecord->course ? "Course :" : ""}}</td>
      <td>{{$academicRecord->course ? $academicRecord->course->name : ""}}</td>
      <td class="student-label">Term :</td>
      <td>{{$billing->term->name}}</td>
    </tr> -->
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

    <!-- <tr>
      <td style="width: 30%;">
        {{$academicRecord->level->name}}
      </td>
      <td>
       
      </td>
      <td style="width: 20%;" >
        {{date('F j, Y', strtotime($billing->created_at))}}
      </td>
    </tr> -->

    <!-- <tr>
      <td></td>
      <td colspan=2 class="student-label"> SECTION : </td>
    </tr>
    <tr>
      <td></td>
      <td colspan=2 >{{$academicRecord->section->name}}</td>
    </tr> -->

    <!-- <tr>
      <td class="student-label">Name :</td>
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
    </tr> -->
  </table> --}}
  <br>
  </div>
</body>

</html>