<?php

namespace App\Services;

use Image;
use App\OrganizationLogo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class OrganizationLogoService
{

    public function store($organizationSettingId, $file)
    {
        try {
            if (!$organizationSettingId) {
                throw new \Exception('Organization Setting id not found!');
            }

            if (!$file) {
                throw new \Exception('File not found!');
            }

            $image = Image::make($file)->resize(null, 350, function ($constraint) {
                $constraint->aspectRatio();
            });

            $path = 'public/organization-logo/' . $file->hashName();
            Storage::put($path, $image->stream());

            $organizationLogo = OrganizationLogo::updateOrCreate(
                ['organization_setting_id' => $organizationSettingId],
                [
                    'path' => $path,
                    'name' => $file->getClientOriginalName(),
                    'hash_name' => $file->hashName()
                ]
            );

            return $organizationLogo;
        } catch (Exception $e) {
            Log::info('Error occured during OrganizationLogoService store method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function delete($organizationSettingId)
    {
        try {
            if (!$organizationSettingId) {
                throw new \Exception('Organization Setting id not found!');
            }

            $query = OrganizationLogo::where('organization_setting_id', $organizationSettingId);
            $photo = $query->first();
            if ($photo) {
                Storage::delete($photo->path);
                $query->delete();
                return true;
            }
            return false;
        } catch (Exception $e) {
            Log::info('Error occured during OrganizationLogoService delete method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }
}