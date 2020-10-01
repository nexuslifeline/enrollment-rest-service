<?php

namespace App\Services;

use App\OrganizationSetting;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrganizationSettingService
{

    public function get(int $id)
    {
        try {
            $organizationSetting = OrganizationSetting::find($id);
            $organizationSetting->load(['organizationLogo']);
            return $organizationSetting;
        } catch (Exception $e) {
            Log::info('Error occured during OrganizationSettingService get method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function update(array $data, int $id)
    {
        DB::beginTransaction();
        try {

            $organizationSetting = OrganizationSetting::updateorCreate(['id' => $id], $data);

            $organizationSetting->load(['organizationLogo']);
            DB::commit();
            return $organizationSetting;
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during OrganizationSettingService update method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

}
