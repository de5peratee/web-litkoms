<?php

namespace App\Http\Controllers\Editor;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMultimediaPostRequest;
use App\Models\Media;
use App\Models\MultimediaPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EditorPostController extends Controller
{
    public function index()
    {
        $mediaPosts = MultimediaPost::with('medias')->get();
//        dd($mediaPosts->toArray());
        return view('editor.mediaposts.list', compact('mediaPosts'));
    }

    public function create()
    {
        return view('editor.mediaposts.create');
    }

    public function store(StoreMultimediaPostRequest $request)
    {
        try {
            DB::beginTransaction();

            $multimediaPost = MultimediaPost::create([
                'created_by' => auth()->id(),
                'name' => $request->name,
                'description' => $request->description,
            ]);

            if ($request->hasFile('media')) {
                foreach ($request->file('media') as $file) {
                    $filePath = $file->store('media', 'public');

                    $media = Media::create([
                        'file' => $filePath,
                    ]);

                    $multimediaPost->medias()->attach($media->id);
                }
            }

            DB::commit();

            return redirect()->route('editor.mediapost_index')->with('success', trans('messages.multimedia_post_created'));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['media' => 'Ошибка при создании мультимедийного поста: ' . $e->getMessage()]);
        }
    }
}
