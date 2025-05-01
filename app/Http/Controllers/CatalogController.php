<?php

namespace App\Http\Controllers;

use App\Models\Catalog;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index()
    {
        $library = Catalog::with('genres')->paginate(12);
        return view('library.index', compact('library'));
    }

    public function get_book($id)
    {
        $book = Catalog::with('genres')->findOrFail($id);
        return view('library.book', compact('book'));
    }


}
