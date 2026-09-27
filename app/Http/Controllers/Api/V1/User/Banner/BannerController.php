<?php

namespace App\Http\Controllers\Api\V1\User\Banner;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\JsonResponse;

class BannerController extends Controller
{
    public function index(): JsonResponse
    {
        $sliders = Slider::where('status', 1)
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Banners fetched successfully.',
            'data' => $sliders,
        ], 200);
    }
}
