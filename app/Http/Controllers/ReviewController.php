<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VisitorReview;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Exception as SpreadsheetException;
use Illuminate\Support\Facades\Log;

class ReviewController extends Controller
{
    /**
     * Import reviews from an Excel spreadsheet.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xls,xlsx'
        ]);
 
        try {
            $path = $request->file('file')->getRealPath();
            $spreadsheet = IOFactory::load($path);
            $worksheet = $spreadsheet->getActiveSheet();
 
            $importedCount = 0;
            $maxReviewLength = 255; // Adjust this to match your column's max length
            foreach ($worksheet->getRowIterator(2) as $row) {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false);
 
                $cells = [];
                foreach ($cellIterator as $cell) {
                    $cells[] = $cell->getValue();
                }
 
                if (empty($cells[0])) {
                    Log::info('Skipped a row due to empty review', $cells);
                    continue;
                }
 
                $reviewText = substr($cells[0], 0, $maxReviewLength); // Truncate the text
 
                $review = new VisitorReview([
                    'review' => $reviewText,
                ]);
 
                $review->save();
                $importedCount++;
            }
 
            if ($importedCount > 0) {
                Log::info("Imported {$importedCount} reviews successfully.");
                return redirect()->route('reviews.index')->with('success', "Ulasan telah diimpor! Total: {$importedCount}");
            } else {
                return redirect()->route('reviews.index')->with('warning', "Tidak ada ulasan yang diimpor. Pastikan file Excel tidak kosong.");
            }
        } catch (SpreadsheetException $e) {
            Log::error('Spreadsheet error: ' . $e->getMessage());
            return back()->withErrors('Spreadsheet error: ' . $e->getMessage())->withInput();
        } catch (\Exception $e) {
            Log::error('Error during import: ' . $e->getMessage());
            return back()->withErrors('Error during import: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the form for creating a new review.
     */
    public function create()
    {
        return view('review_form');
    }

    /**
     * Store a newly created review in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'review' => 'required|string',
        ]);

        $review = VisitorReview::create(['review' => $request->input('review')]);
        Log::info('Review saved successfully', ['review' => $review]);

        return redirect()->back()->with('success', 'Ulasan berhasil disimpan.');
    }

    /**
     * Display the list of reviews.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('show', 25); // Default is 25 if not provided

        $reviews = VisitorReview::when($search, function ($query) use ($search) {
            return $query->where('review', 'LIKE', "%{$search}%");
        })->paginate($perPage);

        return view('review_form', compact('reviews', 'perPage', 'search'));
    }

    /**
     * Remove the specified review from storage.
     */
    public function destroy($id)
    {
        $review = VisitorReview::findOrFail($id);
        $review->delete();

        Log::info('Review deleted successfully', ['id' => $id]);

        return redirect()->route('reviews.index')
                         ->with('success', 'Ulasan berhasil dihapus.');
    }
}
