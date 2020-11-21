<?php

namespace App\Services;

use App\DocumentType;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DocumentTypeService
{
    public function list(bool $isPaginated, int $perPage)
    {
        try {
            $documentTypes = $isPaginated
                ? DocumentType::paginate($perPage)
                : DocumentType::all();
            return $documentTypes;
        } catch (Exception $e) {
            Log::info('Error occured during DocumentTypeService list method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function store(array $data)
    {

    }

    public function get(int $id)
    {

    }

    public function update(array $data, int $id)
    {

    }

    public function delete(int $id)
    {

    }
}