<?php

namespace App\Http\Controllers;

use App\Models\Catalog;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $query = Catalog::with('genres');

        if ($request->filled('search')) {
            $searchTerms = explode(' ', $request->search);

            $query->where(function($q) use ($searchTerms) {
                foreach ($searchTerms as $term) {
                    $q->where('name', 'like', "%{$term}%");
                }
            });
        }

        $library = $query->paginate(12);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('partials.books', ['library' => $library])->render(),
                'has_more' => $library->hasMorePages()
            ]);
        }

        return view('library.index', compact('library'));
    }

    public function get_book($id)
    {
        $book = Catalog::with('genres')->findOrFail($id);
        return view('library.book', compact('book'));
    }
}
