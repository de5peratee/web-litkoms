<?php

namespace App\Http\Controllers;

use App\Models\Catalog;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index()
    {
        $catalogs = Catalog::with('genres')->paginate(20);
        return view('catalog.index', compact('catalogs'));
    }

    public function show($id)
    {
        $catalog = Catalog::with('genres')->findOrFail($id);
        return view('catalog.show', compact('catalog'));
    }


}
