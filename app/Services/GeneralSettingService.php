<?php

namespace App\Services;

use App\GeneralSetting;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GeneralSettingService
{
  public function get(int $id)
  {
    try {
      $generalSetting = GeneralSetting::find($id);
      return $generalSetting;
    } catch (Exception $e) {
      Log::info('Error occured during GeneralSettingService get method call: ');
      Log::info($e->getMessage());
      throw $e;
    }
  }

  public function update(array $data, int $id)
  {
    DB::beginTransaction();
    try {
      $generalSetting = GeneralSetting::updateorCreate(['id' => $id], $data);
      DB::commit();
      return $generalSetting;
    } catch (Exception $e) {
      DB::rollback();
      Log::info('Error occured during GeneralSettingService update method call: ');
      Log::info($e->getMessage());
      throw $e;
    }
  }
}
