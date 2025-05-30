<?php

namespace App\Services;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewService
{
    /**
     * Store a new Review.
     *
     * @param  array  $data
     * @return \App\Models\Review
     */
    public function storeReview(Request $request, array $data)
    {
        $data['user_id'] = $request->user()->id;

        return Review::create($data);
    }

    /**
     * get all reviews.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllReviews()
    {
        return Review::with(['ad', 'user'])->get();
    }

    /**
     * update Review.
     *
     * @param  \App\Models\Review  $review
     * @param  array  $data
     * @return \App\Models\Review
     */
    public function updateReview(Review $review, array $data)
    {
        $review->update($data);
        return $review;
    }

    /**
     * delete Review.
     *
     * @param  \App\Models\Review  $review
     * @return bool|null
     */
    public function deleteReview(Review $review)
    {
        return $review->delete();
    }
}
