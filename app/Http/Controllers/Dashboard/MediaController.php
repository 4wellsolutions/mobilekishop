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
        $dir = public_path('blogs');
        if (!file_exists($dir)) {
            return collect();
        }

        $files = collect(scandir($dir))->filter(fn($f) => !in_array($f, ['.', '..']));

        return $files->map(function ($filename) use ($dir) {
            $fullPath = $dir . DIRECTORY_SEPARATOR . $filename;
            return [
                'name' => $filename,
                'path' => 'blogs/' . $filename,
                'url' => '/blogs/' . $filename,
                'size' => filesize($fullPath),
                'modified' => filemtime($fullPath),
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
            $destinationPath = public_path('blogs');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            foreach ($request->file('files') as $file) {
                $filename = Str::random(20) . '.' . $file->getClientOriginalExtension();
                $file->move($destinationPath, $filename);
                $uploaded[] = [
                    'name' => $filename,
                    'url' => '/blogs/' . $filename,
                    'path' => 'blogs/' . $filename,
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

        $fullPath = public_path($request->path);
        if (file_exists($fullPath)) {
            unlink($fullPath);
            return redirect()->route('dashboard.media.index')
                ->with('success', 'File deleted successfully.');
        }

        return redirect()->route('dashboard.media.index')
            ->with('error', 'File not found.');
    }
}
