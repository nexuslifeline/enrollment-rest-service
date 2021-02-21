<style>
    .report-headers {
        width: 100%;
        text-align: left;
    }

    small {
        color: gray;
        font-size: 12px;
    }
</style>
<div class="report-headers">
    <div><b>{{$organization->name}}</b></div>
    <div><small>{{$organization->address}}</small></div>
    <div><small>{{$organization->telephone_no}}</small></div>
    <div><small>{{$organization->email_address}}<small></div>
</div>