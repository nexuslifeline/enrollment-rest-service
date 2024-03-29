<!DOCTYPE html>
<html>
    <head>
        <title>Registration Form</title>

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
            .table__subjects {
                width: 100%;
                font-size: 9pt;
                margin: 10px 0;
                border: 0.5px solid gray;
                border-collapse: collapse;
            }
            .table__subjects tr td {
                border: 0.5px solid gray;
            }
            .table__subjects td {
                padding: 3px;
            }
            .table__fees {
                width: 100%;
                font-size: 9pt;
                /* margin-bottom: 10px; */
                /* border: 0.5px solid gray; */
                border-collapse: collapse;
            }
            .table__fees tr td {
                /* border: 0.5px solid gray; */
            }
            .table__fees td {
                padding: 3px;
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
        <div class="title">Certificate of Registration</div>
        <table width="100%">
            <tr>
                <td width="12%">Student No. : </td>
                <td width="28%">{{ $academicRecord->student->student_no ? $academicRecord->student->student_no : 'Awaiting Confirmation' }}</td>
                <td width="15%">Level : </td>
                <td width="45%">{{ $academicRecord->level->name }}</td>
            </tr>
            <tr>
                <td>Name : </td>
                <td>{{ $academicRecord->student->last_name .', '. $academicRecord->student->first_name .' '. $academicRecord->student->middle_name }}</td>
                @if($academicRecord->course_id)
                <td>Course : </td>
                <td>{{ $academicRecord->course->description }}</td>
                @endif
            </tr>
            <tr>
                <td>Address : </td>
                <td>{{ $academicRecord->student->address->current_complete_address }}</td>
                <td>School Year : </td>
                <td>{{ $academicRecord->semester_id ? $academicRecord->semester->name.' /' : '' }} {{ $academicRecord->schoolYear->name }}</td>
            </tr>
        </table>
        <hr>
        <br>
        <div class="table__name-subjects">Subjects</div>
        <table class="table__subjects">
            <thead>
                <tr>
                    <td width="100px">Code</td>
                    <td width="250px">Description</td>
                    <td width="70px" class="float-center">Lec Units</td>
                    <td width="100px" class="float-right">Amt Per Lec Unit</td>
                    <td width="70px" class="float-center">Lab Units</td>
                    <td width="100px" class="float-right">Amt Per Lab Unit</td>
                    <td width="100px" class="float-right">Total Amount</td>
                </tr>
            </thead>
            @foreach ($academicRecord->subjects as $subject)
            <tr>
                <td>{{ $subject->name }}</td>
                <td>{{ $subject->description }}</td>
                <td class="float-center">{{ $subject->units }}</td>
                <td class="float-right">{{ number_format($subject->amount_per_unit, 2) }}</td>
                <td class="float-center">{{ $subject->labs }}</td>
                <td class="float-right">{{ number_format($subject->amount_per_lab, 2) }}</td>
                <td class="float-right">{{ number_format($subject->total_amount, 2) }}</td>
            </tr>
            @endforeach
        </table>
        <table width="100%">
            <tr>
                <td width="500px" class="float-right total" >TOTAL TUITION FEE</td>
                <td width="100px" class="float-right total">{{ number_format(array_sum(array_column(iterator_to_array($academicRecord->subjects), 'total_amount')), 2) }}</td>
            </tr>
        </table>
        <br>
    </body>
</html>