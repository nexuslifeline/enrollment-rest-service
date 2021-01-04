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
        DB::beginTransaction();
        try {
            $documentType = DocumentType::create($data);
            DB::commit();
            return $documentType;
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during DocumentTypeService store method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function get(int $id)
    {
        try {
            $documentType = DocumentType::find($id);
            return $documentType;
        } catch (Exception $e) {
            Log::info('Error occured during DocumentTypeService get method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function update(array $data, int $id)
    {
        DB::beginTransaction();
        try {
            $documentType = DocumentType::find($id);
            $documentType->update($data);
            DB::commit();
            return $documentType;
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during DocumentTypeService update method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function delete(int $id)
    {
        try {
            $documentType = DocumentType::find($id);
            $documentType->delete();
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during DocumentTypeService delete method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }
}