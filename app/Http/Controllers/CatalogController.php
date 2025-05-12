<?php

namespace App\Http\Controllers;

use App\Models\Catalog;
use App\Models\Genre;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) {
            session(['catalog_url' => $request->fullUrl()]);
        }

        $query = Catalog::with('genres');

        // Поиск по названию
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where('name', 'like', "%{$search}%");
        }

        // Фильтрация по жанрам
        $genres = $request->input('genres', []);

        if (!empty($genres)) {
            if (!is_array($genres)) {
                $genres = [$genres];
            }
            foreach ($genres as $genre) {
                $query->whereHas('genres', function ($q) use ($genre) {
                    $q->where('genres.name', $genre);
                });
            }
        }

        // Сортировка по дате
        $sort = $request->input('sort', 'desc') === 'asc' ? 'asc' : 'desc';
        $query->orderBy('release_year', $sort);

        $library = $query->paginate(12);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('partials.books', ['library' => $library])->render(),
                'has_more' => $library->hasMorePages()
            ]);
        }

        return view('library.index', [
            'library' => $library,
            'genres' => Genre::all()
        ]);
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
