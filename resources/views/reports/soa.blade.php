<!DOCTYPE html>
<html>
    <head>
        <title>Statement of Account</title>

        <style type="text/css">
            @page { sheet-size: A4; }
            body{
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
                border-bottom: 1px solid black
            }
            .table__fees {
                width: 100%;
                padding-top: 15px;
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
        <div class="title">Statement of Account</div>
        <table class="table__student-info">
          <tr>
            <td style="width: 150px">{{$studentFee->student->student_no}}</td>
            <td style="width: 300px">{{$studentFee->student->last_name.', '.$studentFee->student->first_name.' '.$studentFee->student->middle_name}}</td>
            <td style="width: 100px">{{$studentFee->academicRecord->section->name}}</td>
            <td style="width: 150px">{{$studentFee->academicRecord->semester->name}} {{$studentFee->academicRecord->schoolYear->name}}</td>
          </tr>
        </table>
        <table class="table__fees">
          <tr>
            <td style="width: 50%; text-align: center;">
              <h4>School Fees</h4>
            </td>
            <td style="width: 50%; text-align: center;">
              <h4>Miscellaneous Fees</h4>
            </td>
          </tr>
          <tr>
            <td>
              <table style="width: 100%">
                <tr>
                  <td colspan="3"><b>Fees:</b></td>
                </tr>
                <tr>
                  <td style="padding-left: 15px; width: 65%;">Registration</td>
                  <td style="width: 5%;">P</td>
                  <td style="width: 30%; text-align: right">340.00</td>
                </tr>
                <tr>
                  <td style="padding-left: 15px; width: 65%;">Tuitio</td>
                  <td style="width: 5%;">P</td>
                  <td style="width: 30%; text-align: right">340.00</td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
      </div>
    </body>
</html>