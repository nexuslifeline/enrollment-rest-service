<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\OrganizationLogoService;
use App\Http\Resources\OrganizationLogoResource;
use App\Http\Requests\OrganizationLogoStoreRequest;

class OrganizationLogoController extends Controller
{
    public function store(OrganizationLogoStoreRequest $request, $organizationSettingId)
    {
        try {
            $file = $request->file('photo');
            $organizationLogoService = new OrganizationLogoService();
            $organizationLogo = $organizationLogoService->store($organizationSettingId, $file);
            return (new OrganizationLogoResource($organizationLogo))
                ->response()
                ->setStatusCode(201);
        } catch (Throwable $e) {
            Log::error('Message occured => ' . $e->getMessage());
            return response()->json([], 400);
        }
    }

    public function destroy($organizationSettingId)
    {
        try {
            $organizationLogoService = new OrganizationLogoService();
            if ($organizationLogoService->delete($organizationSettingId)) {
                return response()->json([], 204);
            }
        } catch (Throwable $e) {
            Log::error('Message occured => ' . $e->getMessage());
            return response()->json([], 400);
        }
    }
}
