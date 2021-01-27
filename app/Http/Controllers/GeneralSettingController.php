<?php

namespace App\Http\Controllers;

use App\Http\Requests\GeneralSettingUpdateRequest;
use App\Http\Resources\GeneralSettingResource;
use App\Services\GeneralSettingService;

class GeneralSettingController extends Controller
{
    public function show(int $id)
    {
        $organizationSettingService = new GeneralSettingService();
        $organizationSetting = $organizationSettingService->get($id);
        return new GeneralSettingResource($organizationSetting);
    }

    public function update(GeneralSettingUpdateRequest $request, int $id)
    {
        $organizationSettingService = new GeneralSettingService();
        $data = $request->all();
        $organizationSetting = $organizationSettingService->update($data, $id);
        return (new GeneralSettingResource($organizationSetting))
            ->response()
            ->setStatusCode(200);
    }
}
