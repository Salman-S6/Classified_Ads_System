<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Review\StoreReviewRequest;
use App\Http\Requests\Review\UpdateReviewRequest;
use App\Models\Review;
use App\Services\ReviewService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReviewController extends Controller
{
    protected ReviewService $reviewService;

    public function __construct(ReviewService $reviewService)
    {
        $this->reviewService = $reviewService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reviews = $this->reviewService->getAllReviews();

        return response()->json([
            'status' => 'success',
            'data' => $reviews,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReviewRequest $request)
    {
        $this->authorize('create', Review::class);

        $data = $request->validated();

        $review = $this->reviewService->storeReview($request, $data);

        return response()->json([
            'status' => 'success',
            'message' => 'review created successfully.',
            'data' => $review,
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReviewRequest $request, Review $review)
    {
        $this->authorize('update', $review);

        $data = $request->validated();
        $review = $this->reviewService->updateReview($review, $data);

        return response()->json([
            'status' => 'success',
            'message' => 'Review updated successfully.',
            'data' => $review,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Review $review)
    {
        $this->authorize('delete', $review);

        $this->reviewService->deleteReview($review);

        return response()->json([
            'status' => 'success',
            'message' => 'Review deleted successfully.',
        ]);
    }
}
