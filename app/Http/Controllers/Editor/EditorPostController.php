<?php

namespace App\Http\Controllers\Editor;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMultimediaPostRequest;
use App\Http\Requests\UpdateMultimediaPostRequest;
use App\Models\MultimediaPost;
use App\Services\ImageCompressionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class EditorPostController extends Controller
{
    protected $imageCompressionService;

    public function __construct(ImageCompressionService $imageCompressionService)
    {
        $this->imageCompressionService = $imageCompressionService;
    }

    public function index()
    {
        $mediaPosts = MultimediaPost::with('medias')
            ->orderBy('created_at', 'desc')
            ->get();
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

            $mediaPost = MultimediaPost::create([
                'created_by' => auth()->id(),
                'name' => $request->name,
                'description' => $request->description,
            ]);

            if ($request->hasFile('media')) {
                foreach ($request->file('media') as $file) {
                    $filePath = $file->store('mediapost_media', 'public');
                    $this->imageCompressionService->compressImage(storage_path("app/public/$filePath"));
                    $mediaPost->medias()->create([
                        'file' => $filePath,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('editor.mediapost_index')->with('success', trans('messages.mediapost_created'));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['media' => 'Ошибка при создании поста: ' . $e->getMessage()]);
        }
    }

    public function update(UpdateMultimediaPostRequest $request, MultimediaPost $mediaPost)
    {
        try {
            DB::beginTransaction();

            $mediaPost->update([
                'name' => $request->name,
                'description' => $request->description,
            ]);

            if ($request->hasFile('media')) {
                foreach ($mediaPost->medias as $media) {
                    Storage::disk('public')->delete($media->file);
                    $media->delete();
                }

                foreach ($request->file('media') as $file) {
                    $filePath = $file->store('mediapost_media', 'public');
                    $this->imageCompressionService->compressImage(storage_path("app/public/$filePath"));
                    $mediaPost->medias()->create([
                        'file' => $filePath,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('editor.mediapost_index')->with('success', trans('messages.mediapost_updated'));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['media' => 'Ошибка при обновлении поста: ' . $e->getMessage()]);
        }
    }

    public function destroy(MultimediaPost $mediaPost)
    {
        try {
            DB::beginTransaction();

            foreach ($mediaPost->medias as $media) {
                Storage::disk('public')->delete($media->file);
                $media->delete();
            }

            $mediaPost->delete();

            DB::commit();

            return redirect()->route('editor.mediapost_index')->with('success', trans('messages.mediapost_deleted'));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Ошибка при удалении поста: ' . $e->getMessage()]);
        }
    }
}