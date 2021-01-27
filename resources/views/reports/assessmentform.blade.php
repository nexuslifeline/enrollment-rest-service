<!DOCTYPE html>
<html>
    <head>
        <title>Assessment</title>

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
                width: 80%;
                font-size: 9pt;
                margin: auto;
                /* margin-bottom: 10px; */
                /* border: 0.5px solid gray; */
                border-collapse: collapse;
            }
            .table__terms {
                width: 80%;
                font-size: 9pt;
                border: 1px solid gray;
                border-collapse: collapse;
            }
            .table__terms td {
                padding: 3px;
                border: 1px solid gray;
                text-transform: capitalize;
            }
            .table__categories {
                width: 80%;
                font-size: 9pt;s
                margin: auto;
                /* margin-bottom: 10px; */
                /* border: 0.5px solid gray; */
                border-collapse: collapse;
            }
            .table__categories td {
                padding: 3px;
                text-transform: capitalize;
            }
            .table__fees tr td {
                /* border: 0.5px solid gray; */
            }
            .table__fees td {
                padding: 3px;
                text-transform: capitalize;
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
        <div class="title">ASSESSMENT</div>
        <table class="student__info" style="width: 100%">
            <tr>
                <td style="width: 50%; font-weight: bold;">Student No. : </td>
                <td style="width: 50%; font-weight: bold;" >Level : </td>
            </tr>
            <tr>
                <td style="width: 28%">{{ $academicRecord->student->student_no ? $academicRecord->student->student_no : 'Awaiting Confirmation' }}</td>
                <td style="width: 40%; text-transform: none;">{{ $academicRecord->level->name }}</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">Name : </td>
                @if($academicRecord->course_id)
                <td style="font-weight: bold;">Course : </td>
                @endif
            </tr>

            <tr>
                <td>{{ $academicRecord->student->last_name .', '. $academicRecord->student->first_name .' '. $academicRecord->student->middle_name }}</td>
                @if($academicRecord->course_id)
                <td style="text-transform: none;">{{ $academicRecord->course->description }}</td>
                @endif
            </tr>

            <tr>
                <td style="font-weight: bold;">Address : </td>
                <td style="font-weight: bold;">School Year : </td>
            </tr>

            <tr>
                <td>{{ $academicRecord->student->address ? $academicRecord->student->address->current_complete_address : '' }}</td>
                <td style="text-transform: none;">{{ $academicRecord->semester_id ? $academicRecord->semester->name.' /' : '' }} {{ $academicRecord->schoolYear->name }}</td>
            </tr>

        </table>
        <hr>
        <br>
        {{-- @if($academicRecord->studentFee->is_computed_by_units)
        <div class="table__name-subjects">Subjects</div>
        <table class="table__subjects">
            <thead>
                <tr>
                    <td style="width: 100px">Code</td>
                    <td style="width: 250px">Description</td>
                    <td style="width: 70px" class="float-center">Lec Units</td>
                    <td style="width: 100px" class="float-right">Amt Per Lec Unit</td>
                    <td style="width: 70px" class="float-center">Lab Units</td>
                    <td style="width: 100px" class="float-right">Amt Per Lab Unit</td>
                    <td style="width: 100px" class="float-right">Total Amount</td>
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
        <table style="width: 100%">
            <tr>
                <td style="width: 500px" class="float-right total" >TOTAL TUITION FEE</td>
                <td style="width: 100px" class="float-right total">{{ number_format($academicRecord->subjects->sum('total_amount'), 2) }}</td>
            </tr>
        </table>
        @endif --}}
        @php
        $fees = $academicRecord->studentFee->studentFeeItems;
        @endphp
        <br>
        <table style="width: 100%;">
            <tr>
                <td style="width: 50%">
                    <table class="table__categories">
                        <tr>
                            <td colspan="2" style="text-align: center; font-weight: bold; font-size: 11pt;">SUMMARY</td>
                        </tr>
                        @foreach ($schoolFeeCategories as $schoolFeeCategory)
                        @if (count($fees->where('school_fee_category_id', $schoolFeeCategory->id)))
                        <tr>
                            <td>{{$schoolFeeCategory->name}}</td>
                            <td class="float-right">{{number_format($fees->where('school_fee_category_id', $schoolFeeCategory->id)->sum('pivot.amount'), 2)}}</td>
                        </tr>
                        @endif
                        @endforeach
                        <tr>
                            <td>Uncategorized Fees</td>
                            <td class="float-right">{{number_format($fees->whereNull('school_fee_category_id')->sum('pivot.amount'), 2)}}</td>
                        </tr>
                        <tr>
                            <td style="border-top: 1px solid gray; font-weight: bold;" colspan="2">SUB TOTAL :</td>
                        </tr>
                        <tr>
                            <td style="padding-left: 30px;">Total Fees</td>
                            <td style="font-weight: bold;" class="float-right">{{number_format($academicRecord->studentFee->total_amount, 2)}}</td>
                        </tr>
                        <tr>
                            <td style="padding-left: 30px;">Previous Balance</td>
                            <td style="font-weight: bold;" class="float-right">{{number_format($academicRecord->studentFee->billings->where('billing_type_id', 1)->first()->previous_balance, 2)}}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold;" colspan="2">LESS :</td>
                        </tr>
                        <tr>
                            <td style="padding-left: 30px;">Total Payment</td>
                            <td class="float-right" style="font-weight: bold;">
                            @php
                            $payment = $academicRecord->studentFee->billings
                                    ->where('billing_type_id', 1)->first()->payments()->where('payment_status_id', 2)->sum('amount');
                            @endphp
                            ({{
                                (number_format($payment,2))
                            }})
                            </td>
                        </tr>
                        <tr>
                            <td class="total">TOTAL BALANCE :</td>
                            <td style="border-top: 1px solid gray" class="float-right total">
                            {{
                                number_format(($academicRecord->studentFee->total_amount + $academicRecord->studentFee->billings->where('billing_type_id', 1)->first()->previous_balance) - $payment ,2)
                            }}
                            </td>
                        </tr>
                    </table>
                    <br>
                    <table class="table__terms">
                        <tr>
                            <td colspan="2" style="text-align: center; font-weight: bold; font-size: 11pt;">PAYMENT SCHEDULE</td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold;">TERMS</td>
                            <td style="text-align: right; font-weight: bold;">AMOUNT</td>
                        </tr>
                        @foreach ($academicRecord->studentFee->terms as $term)
                        <tr>
                            <td>{{$term->name}}</td>
                            <td style="text-align: right">{{number_format($term->pivot->amount, 2)}}</td>
                        </tr>
                        @endforeach
                    </table>
                </td>
                <td style="width: 50%; vertical-align: top">
                    <table class="table__fees">
                        <tr>
                            <td colspan="2" style="text-align: center; font-weight: bold; font-size: 11pt;">{{ strtoupper($general->miscellaneousFeeCategory->name) }}</td>
                        </tr>
                        @foreach($fees->where('school_fee_category_id', $general->miscellaneous_fee_category_id) as $fee)
                        <tr>
                            <td>{{ $fee->name }}</td>
                            <td class="float-right">{{ number_format($fee->pivot->amount, 2) }}</td>
                        </tr>
                        @endforeach
                        <tr>
                            <td style="border-top: 1px solid gray; width: 70%;" class="float-right total">TOTAL</td>
                            <td style="border-top: 1px solid gray; width: 30%;" class="float-right total">{{ number_format($fees->where('school_fee_category_id', $general->miscellaneous_fee_category_id)->sum('pivot.amount'), 2) }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <br>
        <br>
    </body>
</html>