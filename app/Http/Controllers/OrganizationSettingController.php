<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\OrganizationSettingService;
use App\Http\Resources\OrganizationSettingResource;
use App\Http\Requests\OrganizationSettingUpdateRequest;

class OrganizationSettingController extends Controller
{
    public function show(int $id)
    {
        $organizationSettingService = new OrganizationSettingService();
        $organizationSetting = $organizationSettingService->get($id);
        return new OrganizationSettingResource($organizationSetting);
    }

    public function update(OrganizationSettingUpdateRequest $request, int $id)
    {
        $organizationSettingService = new OrganizationSettingService();
        $data = $request->all();
        $organizationSetting = $organizationSettingService->update($data, $id);
        return (new OrganizationSettingResource($organizationSetting))
        ->response()
        ->setStatusCode(200);
    }
}
