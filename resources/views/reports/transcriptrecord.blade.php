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
      font-size: 8pt;
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

    .table__student {
      width: 100%;
      font-size: 10pt;
    }

    .student__info {
      font-weight: bold;
    }

    .table__subjects {
      border-collapse: collapse;
      margin-top: 15px;
      width: 100%;
      font-size: 9pt;
    }

    .subjects__header {
      background-color: lightgray;
      border-top: 1px solid gray;
      border-bottom: 1px solid gray;
      padding: 3px 0;
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
  <table class="table__student">
    <tr>
      <td style="width: 40%;">NAME : <span class="student__info">{{strtoupper($transcriptRecord->student->name)}}</span></td>
      @if ($transcriptRecord->course_id)
      <td style="width: 30%;">COURSE : <span class="student__info">{{strtoupper($transcriptRecord->course->name)}}</span></td>
      @else
      <td style="width: 30%;">LEVEL : <span class="student__info">{{strtoupper($transcriptRecord->level->name)}}</span></td>
      @endif
      <td style="width: 30%">STUDENT NO. : <span class="student__info">{{strtoupper($transcriptRecord->student->student_no)}}</span></td>
    </tr>
  </table>
  <table class="table__subjects">
    <tr>
      <td class="subjects__header" style="width: 12%;">COURSE</td>
      <td class="subjects__header" style="width: 60%;">DESCRIPTIVE TITLE</td>
      <td class="subjects__header" style="width: 9%; text-align: center;">UNITS</td>
      <td class="subjects__header" style="width: 9%; text-align: center;">FINAL</td>
      <td class="subjects__header" style="width: 9%; text-align: center;">REMARKS</td>
    </tr>
    {{-- for school category with sem --}}
    @if (in_array($transcriptRecord->school_category_id, [4,5,6]))
    {{-- @foreach ($semesters as $semester) --}}
    @php
    $groupedSubjects = $transcriptRecord->subjects->groupBy(['schoolYear.name', 'level.name', 'semester.name']);
    @endphp
    @foreach ($groupedSubjects as $schoolYearName => $level)
    @foreach ($level as $levelName => $semester)
    @foreach ($semester as $semesterName => $subjects)
    <tr>
      <td colspan="5" style="text-align: center; font-weight: bold; padding-top: 10px;">{{$levelName}} {{$semesterName}} {{$schoolYearName}}</td>
    </tr>
    @foreach ($subjects as $subject)
    <tr>
      <td>{{ $subject->name }}</td>
      <td>{{ $subject->description }}</td>
      <td style="text-align: center;">{{ $subject->units }}</td>
      <td style="text-align: center;">{{ $subject->pivot->grade }}</td>
      <td style="text-align: center;">{{ $subject->pivot->notes }}</td>
    </tr>
    @endforeach
    @endforeach
    @endforeach
    @endforeach
    {{-- @endforeach --}}
    {{-- for school category without sem --}}
    @else
    @foreach ($transcriptRecord->subjects as $subject)
    <tr>
      <td>{{ $subject->name }}</td>
      <td>{{ $subject->description }}</td>
      <td style="text-align: center;">{{ $subject->units }}</td>
      <td style="text-align: center;">{{ $subject->pivot->grade }}</td>
      <td style="text-align: center;">{{ $subject->pivot->notes }}</td>
    </tr>
    @endforeach
    @endif
  </table>
  <br>
  </div>
</body>

</html>