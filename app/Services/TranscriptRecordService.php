<?php

namespace App\Services;

use App\TranscriptRecord;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class TranscriptRecordService
{
  public function get(int $id)
  {
    try {
      $transcriptRecord = TranscriptRecord::find($id);
      $transcriptRecord->load([
        'subjects' => function ($query) {
          $query->with('prerequisites');
        },
      ]);
      return $transcriptRecord;
    } catch (Exception $e) {
      Log::info('Error occured during TranscriptRecordService get method call: ');
      Log::info($e->getMessage());
      throw $e;
    }
  }
}
