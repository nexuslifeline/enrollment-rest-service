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
    <!-- move this to helper -->
    <div>
        <small>
        {{join(' / ', array_filter([$organization->mobile_no, $organization->telephone_no],
        function ($v) {
            return $v;
        }))
        }}
        </small>
    </div>
    <div><small>{{$organization->email_address}}<small></div>
</div>