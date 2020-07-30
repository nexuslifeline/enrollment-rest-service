<!DOCTYPE html>
<html>
    <head>
        <title>Assessment Form</title>
        
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
                font-weight: 900;
            }
            .header__details {
                font-size: 11pt;
                font-weight: 900;
            }
            .table__subjects {
                width: 100%;
                font-size: 9pt;
                margin-bottom: 10px;
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
                margin-bottom: 10px;
                border: 0.5px solid gray;
                border-collapse: collapse;
            }
            .table__fees tr td {
                border: 0.5px solid gray;
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
                font-weight: 900;
            }
            .table__name {
                margin-bottom: 5px;
                font-size: 10pt;
                font-weight: 900;
            }
            .title {
                font-size: 12pt;
                font-weight: 900;
                text-align: center;
                margin-bottom: 15px;
            }
        </style>
    </head>
    <body>
        <table class="table__header">
            <tr>
                <td class="td__logo">
                    <img src="{{url('storage/stc_logo.png')}}" style="height: 90px; width: 90px;">
                </td>
                <td class="td__info">
                    <div class="header__name">SAINT THERESA COLLEGE OF TANDAG, INC.</div>
                    <div class="header__details">Tandag City, Surigao del Sur</div>
                    <div class="header__details">Telefax No. 086-2113046, 2114499</div>
                    <div class="header__details">Email: stctandag@yahoo.com</div>
                </td>
                <td></td>
            </tr>
        </table>
        <div class="title">Assessment Form</div>
        <table width="100%">
            <tr>
                <td width="12%">Student No. : </td>
                <td width="28%">{{ $transcript->student->student_no ? $transcript->student->student_no : 'Awaiting Confirmation' }}</td>
                <td width="15%">Level : </td>
                <td width="45%">{{ $transcript->level->name }}</td>
            </tr>
            <tr>
                <td>Name : </td>
                <td>{{ $transcript->student->last_name .', '. $transcript->student->first_name .' '. $transcript->student->middle_name }}</td>
                @if($transcript->course_id)
                <td>Course : </td>
                <td>{{ $transcript->course->description }}</td>
                @endif
            </tr>
            <tr>
                <td>Address : </td>
                <td>{{ $transcript->student->address->current_complete_address }}</td>
                <td>School Year : </td>
                <td>{{ $transcript->semester_id ? $transcript->semester->name.' /' : '' }} {{ $transcript->schoolYear->name }}</td>
            </tr>
        </table>
        <hr>
        <div class="table__name">Subjects</div>
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
            @foreach ($subjects as $subject)
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
                <td width="500px" class="float-right total" >TOTAL</td>
                <td width="100px" class="float-right total">{{ number_format(array_sum(array_column(iterator_to_array($subjects), 'total_amount')), 2) }}</td>
            </tr>
        </table>
        @php
        $array = []
        @endphp
        @foreach($fees as $category)
        @if(!(in_array($category->school_fee_category_id, $array)))
        <div class="table__name">{{$category->schoolFeeCategory->name}}</div>
        <table class="table__fees">
            <thead>
                <tr>
                    <td width="200px">Fee</td>
                    <td width="300px">Notes</td>
                    <td width="100px" class="float-right">Amount</td>
                </tr>
            </thead>
            @foreach ($fees as $fee)
            @if($fee->school_fee_category_id === $category->school_fee_category_id)
            <tr>
                <td>{{ $fee->name }}</td>
                <td>{{ $fee->pivot->notes }}</td>
                <td class="float-right">{{ number_format($fee->pivot->amount, 2) }}</td>
            </tr>
            @endif
            @endforeach
        </table>
        <table width="100%">
            <tr>
                <td width="500px" class="float-right total" >TOTAL</td>
                <td width="100px" class="float-right total">{{ number_format($student_fee->total_amount, 2) }}</td>
            </tr>
        </table>
        @php
        array_push($array, $category->school_fee_category_id)
        @endphp
        @endif
        @endforeach
        <br>
    </body>
</html>