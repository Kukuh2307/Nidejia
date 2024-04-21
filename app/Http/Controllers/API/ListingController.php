<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Model\Listing;
use App\Models\Listing as ModelsListing;

class ListingController extends Controller
{
    public function index(): JsonResponse
    {
        $listings = ModelsListing::withCount('transaction')->orderBy('transaction_count', 'desc')->paginate();

        return response()->json([
            'data' => $listings,
            'success' => true,
            'message' => 'List of listings'
        ]);
    }
}
