<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VisitorReview;
use Illuminate\Support\Facades\Log;

class SentimenGrafikController extends Controller
{
    public function index()
    {
        // Count the total number of reviews
        $totalReviews = VisitorReview::count();
        Log::info("Total number of reviews: {$totalReviews}");

        if ($totalReviews == 0) {
            // If no reviews are found, return default values to avoid division by zero
            return view('grafik', [
                'sentiments' => ['positive' => 0, 'negative' => 0, 'neutral' => 0],
                'percentages' => ['positive' => 0, 'negative' => 0, 'neutral' => 0],
                'summary' => ['total_reviews' => 0, 'percent_positive' => 0, 'percent_negative' => 0, 'percent_neutral' => 0]
            ]);
        }

        // Retrieve the count of each sentiment in one query
        $sentimentsCounts = VisitorReview::selectRaw('sentiment, COUNT(*) as count')
                                         ->groupBy('sentiment')
                                         ->get()
                                         ->pluck('count', 'sentiment');

        // Log the counts for debugging
        Log::info('Counts of each sentiment: ', $sentimentsCounts->toArray());

        $sentiments = [
            'positive' => $sentimentsCounts->get('Positif', 0),
            'negative' => $sentimentsCounts->get('Negatif', 0),
            'neutral'  => $sentimentsCounts->get('Netral', 0)
        ];

        // Calculate the percentage of each sentiment
        $percentages = [
            'positive' => ($sentiments['positive'] / $totalReviews) * 100,
            'negative' => ($sentiments['negative'] / $totalReviews) * 100,
            'neutral'  => ($sentiments['neutral'] / $totalReviews) * 100
        ];

        // Ensure the total percentage is 100% after rounding
        $totalPercentage = array_sum($percentages);
        if (round($totalPercentage) != 100) {
            $diff = 100 - round($totalPercentage);
            $max_key = array_keys($percentages, max($percentages))[0];
            $percentages[$max_key] += $diff;
        }

        // Prepare the summary data
        $summary = [
            'total_reviews' => $totalReviews,
            'percent_positive' => $percentages['positive'],
            'percent_negative' => $percentages['negative'],
            'percent_neutral' => $percentages['neutral']
        ];

        // Log the final data to be sent to the view
        Log::info('Final data sent to view: ', [
            'Sentiments' => $sentiments,
            'Percentages' => $percentages,
            'Summary' => $summary
        ]);

        // Send the data to the view
        return view('grafik', compact('sentiments', 'percentages', 'summary'));
    }
}
