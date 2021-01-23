<!DOCTYPE html>
<html lang="en">
<head>
    <title>List of Enrolled Student</title>

    <style type="text/css" media="print">
        @page {
            /* sheet-size: A4; */
            margin: 0.4in;
            size: landscape;
        }
        body{
            font-family: Calibri;
            font-size: 9pt;
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

        .student-list {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }

        .student-list th {
            border: solid 1px gray;
            padding: 5px;
            background-color: whitesmoke;
        }

        .student-list td {
            border: solid 1px gray;
            padding: 10px;
        }

        .text-left {
            text-align: left;
        }
        .text-center {
            text-align: center;
        }

        .filters {
            width: 100%
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
    <div class="title">List of Enrolled Student</div>
    <div class="filters text-center">
        <span><strong>School Year</strong> : {{$schoolYear->name}}</span>
    </div>
    <div class="filters text-center">
        <span><strong>School Category</strong> : {{$schoolCategory ? $schoolCategory->name : ''}}</span>
        <span> <strong> | Level</strong> : {{is_string($level) ? $level : $level->name}}</span>
    </div>
    <div class="filters text-center">
        @if($course)<span><strong>Course</strong> : {{is_string($course) ? $course : ($course ? $course->description : '') }}</span>@endif
    </div>
    <div class="filters text-center">
        @if($semester)<span><strong>Semester</strong> : {{is_string($semester) ? $semester : ($semester ? $semester->name : '') }}</span>@endif
    </div>
    <table class="student-list">
        <tr>
            <th class="text-left" style="width: 40%;"> Student </th>
            <th class="text-left" style="width: 40%;"> Academic Record </th>
            <th class="text-left" style="width: 20%;"> Contact </th>
        </tr>
        @foreach ($academicRecords as $academicRecord)
            <tr>
                <td>
                    <div> <strong>{{ $academicRecord->student->name }}</strong></div>
                    <div> {{ $academicRecord->student->student_no }} </div>
                    <div> {{ $academicRecord->student->current_address }} </div>
                </td>
                <td>
                    <div> {{ $academicRecord->level ? $academicRecord->level->name : '' }} </div>
                    <div> {{ $academicRecord->course ? $academicRecord->course->name : '' }}  {{ $academicRecord->semester ? $academicRecord->semester->name : '' }}</div>
                    <div> {{ $academicRecord->schoolYear ? $academicRecord->schoolYear->name : '' }}
                </td>
                <td>
                    <div> {{ $academicRecord->student->email }}
                    <div> {{ $academicRecord->student->mobile_no }}
                </td>
            </tr>
        @endforeach
    </table>
</body>
</html>