<?php

namespace App\Http\Controllers;

use App\Models\Catalog;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) {
            session(['catalog_url' => $request->fullUrl()]);
        }

        $query = Catalog::with('genres')->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where('name', 'like', "%{$search}%");
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

        $backUrl = session('catalog_url', route('library.index'));

        return view('library.book', [
            'book' => $book,
            'backUrl' => $backUrl
        ]);
    }
}
