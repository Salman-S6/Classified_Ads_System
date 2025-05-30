<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\Ad;

class AdService
{
    public function getAllAds()
    {
        $ads = Ad::where('status', '!=', 'rejected')->with(['mainImage', 'category', 'user'])->withCount('reviews')
            ->get();

        return $ads;
    }

    /**
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getActiveAds()
    {
        return Ad::activeAds()->with(['mainImage', 'category', 'user', 'reviews'])
            ->orderBy('visits', 'asc')
            ->withCount('reviews')->get();
    }

    /**
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getRejectedAds()
    {
        return Ad::rejectedAds()->with(['mainImage', 'category', 'user', 'reviews'])->get();
    }

    /**
     * Add an image to the specified ad.
     *
     * @param \App\Models\Ad $ad
     * @param array $data
     * @return \App\Models\Image
     */
    public function addImage(Ad $ad, array $data)
    {
        return $ad->images()->create($data);
    }

    /**
     * Create a new ad.
     *
     * @param  array  $data
     * @return \App\Models\Ad
     */
    public function storeAd(Request $request, array $data)
    {
        $data['user_id'] = $request->user()->id;
        $data['status'] = 'pending';

        $ad = Ad::create($data);

        return $ad;
    }

    /**
     * Update an existing ad.
     *
     * @param  \App\Models\Ad  $ad
     * @param  array  $data
     * @return \App\Models\Ad
     */
    public function updateAd(Ad $ad, array $data)
    {
        $ad->update($data);

        return $ad;
    }

    /**
     * Delete ad.
     *
     * @param  \App\Models\Ad  $ad
     * @return bool|null
     */
    public function deleteAd(Ad $ad)
    {
        return $ad->delete();
    }
}
