<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    private function getMediaFiles()
    {
        $disk = Storage::disk('public');
        $allFiles = $disk->allFiles('blogs');

        return collect($allFiles)->map(function ($path) use ($disk) {
            return [
                'name' => basename($path),
                'path' => $path,
                'url' => Storage::url($path),
                'size' => $disk->size($path),
                'modified' => $disk->lastModified($path),
            ];
        })->sortByDesc('modified')->values();
    }

    public function index()
    {
        $media = $this->getMediaFiles();
        return view('dashboard.media.index', compact('media'));
    }

    public function apiIndex()
    {
        $media = $this->getMediaFiles();
        return response()->json(['files' => $media]);
    }

    public function upload(Request $request)
    {
        $request->validate([
            'files.*' => 'required|file|mimes:jpeg,png,jpg,gif,webp,svg,mp4,pdf|max:10240',
        ]);

        $uploaded = [];

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $filename = Str::random(20) . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('blogs', $filename, 'public');
                $uploaded[] = [
                    'name' => $filename,
                    'url' => Storage::url($path),
                    'path' => $path,
                ];
            }
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'files' => $uploaded,
                'count' => count($uploaded),
            ]);
        }

        return redirect()->route('dashboard.media.index')
            ->with('success', count($uploaded) . ' file(s) uploaded successfully.');
    }

    public function destroy(Request $request)
    {
        $request->validate(['path' => 'required|string']);

        $disk = Storage::disk('public');
        if ($disk->exists($request->path)) {
            $disk->delete($request->path);
            return redirect()->route('dashboard.media.index')
                ->with('success', 'File deleted successfully.');
        }

        return redirect()->route('dashboard.media.index')
            ->with('error', 'File not found.');
    }
}
