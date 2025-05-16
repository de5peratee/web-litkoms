<?php

namespace App\Http\Controllers\Editor;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCatalogEditorRequest;
use App\Http\Requests\UpdateCatalogEditorRequest;
use App\Models\Catalog;
use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class EditorCatalogController extends Controller
{
    public function index()
    {
        $catalogs = Catalog::with('genres')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('editor.catalog.list', compact('catalogs'));
    }

    public function create()
    {
        return view('editor.catalog.create');
    }

    public function store(StoreCatalogEditorRequest $request)
    {
        try {
            DB::beginTransaction();

            $coverPath = $request->hasFile('cover')
                ? $request->file('cover')->store('catalog_covers', 'public')
                : null;

            $catalog = Catalog::create([
                'name' => $request->name,
                'author' => $request->author,
                'description' => $request->description,
                'release_year' => $request->release_year,
                'cover' => $coverPath,
            ]);

            $this->syncGenres($catalog, $request->genres ?? '');

            DB::commit();

            return redirect()->route('editor.catalogs_index')->with('success', trans('messages.catalog_created'));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['cover' => 'Ошибка при создании элемента каталога: ' . $e->getMessage()]);
        }
    }

    public function update(UpdateCatalogEditorRequest $request, Catalog $catalog)
    {
        try {
            DB::beginTransaction();

            if ($request->hasFile('cover')) {
                if ($catalog->cover) {
                    Storage::disk('public')->delete($catalog->cover);
                }
                $catalog->cover = $request->file('cover')->store('catalog_covers', 'public');
            }

            $catalog->update([
                'name' => $request->name,
                'author' => $request->author,
                'description' => $request->description,
                'release_year' => $request->release_year,
                'cover' => $catalog->cover,
            ]);

            $this->syncGenres($catalog, $request->genres ?? '');

            DB::commit();

            return redirect()->route('editor.catalogs_index')->with('success', trans('messages.catalog_updated'));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['cover' => 'Ошибка при обновлении элемента каталога: ' . $e->getMessage()]);
        }
    }

    public function destroy(Catalog $catalog)
    {
        try {
            DB::beginTransaction();

            if ($catalog->cover) {
                Storage::disk('public')->delete($catalog->cover);
            }

            $catalog->genres()->detach();
            $catalog->delete();

            Genre::doesntHave('catalogs')->delete();

            DB::commit();

            return redirect()->route('editor.catalogs_index')->with('success', trans('messages.catalog_deleted'));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Ошибка при удалении элемента каталога: ' . $e->getMessage()]);
        }
    }

    private function syncGenres(Catalog $catalog, string $genresString)
    {
        $genres = array_filter(array_map('trim', explode(',', $genresString)));
        $ids = Genre::whereIn('name', $genres)->pluck('id')->toArray();

        foreach ($genres as $name) {
            if (!Genre::where('name', $name)->exists()) {
                $genre = Genre::create(['name' => $name]);
                $ids[] = $genre->id;
            }
        }

        $catalog->genres()->sync($ids);

        Genre::doesntHave('catalogs')->delete();
    }
}