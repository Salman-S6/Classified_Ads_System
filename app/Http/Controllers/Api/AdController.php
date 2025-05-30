<?php

namespace App\Http\Controllers\Api;

use App\Jobs\SendAdConfirmationEmail;
use App\Models\Ad;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Ad\StoreAdRequest;
use App\Http\Requests\Ad\UpdateAdRequest;
use App\Http\Requests\Images\StoreImageRequest;
use App\Services\AdService;
use Illuminate\Support\Facades\Cache;

class AdController extends Controller
{
    protected $adService;

    public function __construct(AdService $adService)
    {
        $this->adService = $adService;
    }

    /**
     * show all ads.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $ads = Cache::remember('active_ads', 3600, function () {
            return $this->adService->getActiveAds();
        });

        return response()->json([
            'status' => 'success',
            'data' => $ads
        ], 200);
    }

    public function showAllAds()
    {

        $ads = $this->adService->getAllAds();

        return response()->json([
            'status' => 'success',
            'data' => $ads
        ], 200);
    }

    public function rejectedAds()
    {

        $ads = $this->adService->getRejectedAds();

        return response()->json([
            'status' => 'success',
            'data' => $ads
        ], 200);
    }

    /**
     * Add an image to the specified ad.
     *
     * @param  \App\Http\Requests\Images\StoreImageRequest  $request
     * @param  \App\Models\Ad  $ad
     * @return \Illuminate\Http\JsonResponse
     */
    public function addImage(StoreImageRequest $request, Ad $ad)
    {
        $this->authorize('addImage', Ad::class);

        $data = $request->validated();
        $image = $this->adService->addImage($ad, $data);

        return response()->json([
            'status' => 'success',
            'message' => 'Image added to ad successfully.',
            'data' => $image,
        ], 201);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAdRequest $request)
    {
        $this->authorize('create', Ad::class);

        $validatedData = $request->validated();
        $ad = $this->adService->storeAd($request, $validatedData);

        Cache::forget('active_ads');

        SendAdConfirmationEmail::dispatch($ad);

        return response()->json([
            'status' => 'success',
            'message' => 'The Ad was created successfully.',
            'data' => $ad
        ], 201);
    }

    /**
     * show one ad with its details.
     *
     * @param  \App\Models\Ad  $ad
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Ad $ad)
    {
        $this->authorize('view', $ad);

        $ad->with(['mainImage', 'category', 'user', 'reviews'])->withCount('reviews')
            ->get();

        $ad->visits += 1;
        $ad->save();

        return response()->json([
            'status' => 'success',
            'data' => $ad
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAdRequest $request, Ad $ad)
    {
        $this->authorize('update', $ad);

        $validatedData = $request->validated();
        $ad = $this->adService->updateAd($ad, $validatedData);

        Cache::forget('active_ads');


        return response()->json([
            'status' => 'success',
            'message' => 'ad was updated successfully',
            'data' => $ad
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ad $ad)
    {
        $this->authorize('delete', $ad);

        $this->adService->deleteAd($ad);

        return response()->json([
            'status' => 'success',
            'message' => 'ad was deleted successfully'
        ]);
    }
}
