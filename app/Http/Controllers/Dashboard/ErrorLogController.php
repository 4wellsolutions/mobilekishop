<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ErrorLog;
use Illuminate\Http\Request;

class ErrorLogController extends Controller
{
    // Show all error logs with optional filtering
    public function index(Request $request)
    {
        $query = ErrorLog::query();

        // Filter by error code
        if ($request->filled('error_code')) {
            $query->where('error_code', $request->error_code);
        }

        // Search by URL with advanced types
        if ($request->filled('search')) {
            $search = $request->search;
            $type = $request->get('search_type', 'contains');

            match ($type) {
                'not_contains' => $query->where('url', 'not like', '%' . $search . '%'),
                'starts_with' => $query->where('url', 'like', $search . '%'),
                'ends_with' => $query->where('url', 'like', '%' . $search),
                'exact' => $query->where('url', $search),
                default => $query->where('url', 'like', '%' . $search . '%'),
            };
        }

        // Sort
        $sortBy = $request->get('sort', 'updated_at');
        $sortDir = $request->get('dir', 'desc');
        $allowedSorts = ['updated_at', 'created_at', 'hit_count', 'url', 'error_code'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortDir === 'asc' ? 'asc' : 'desc');
        } else {
            $query->latest('updated_at');
        }

        $errorLogs = $query->paginate(50)->withQueryString();

        // Get distinct error codes for filter dropdown
        $errorCodes = ErrorLog::select('error_code')->distinct()->orderBy('error_code')->pluck('error_code');

        return view('dashboard.error_logs.index', compact('errorLogs', 'errorCodes'));
    }

    // Delete an error log
    public function destroy($id)
    {
        $errorLog = ErrorLog::findOrFail($id);
        $errorLog->delete();

        return redirect()->route('dashboard.error_logs.index')->with('success', 'Error log deleted successfully.');
    }

    // Check the HTTP status of a logged URL
    public function checkStatus(Request $request, $id)
    {
        $errorLog = ErrorLog::findOrFail($id);

        try {
            $url = $errorLog->url;
            if (!str_starts_with($url, 'http')) {
                $url = url($url);
            }

            $response = \Illuminate\Support\Facades\Http::timeout(5)
                ->withHeaders(['User-Agent' => 'MobileKiShop-StatusChecker/1.0'])
                ->get($url);

            $status = $response->status();
        } catch (\Exception $e) {
            $status = 0; // Connection failed
        }

        $errorLog->update([
            'last_checked_status' => $status,
            'last_checked_at' => now(),
        ]);

        $errorLog->refresh();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'status' => $status,
                'checked_at' => $errorLog->last_checked_at->diffForHumans(),
                'message' => "URL Status Checked: " . ($status ?: 'Failed to connect')
            ]);
        }

        return redirect()->back()->with('success', "URL Status Checked: " . ($status ?: 'Failed to connect'));
    }

    // Clear all error logs (optionally only 404s)
    public function clearAll(Request $request)
    {
        $code = $request->input('code');

        if ($code == '404') {
            $count = ErrorLog::where('error_code', 404)->count();
            ErrorLog::where('error_code', 404)->delete();
            $message = "Cleared {$count} 404 error logs.";
        } else {
            $count = ErrorLog::count();
            ErrorLog::truncate();
            $message = "Cleared all {$count} error logs.";
        }

        return redirect()->route('dashboard.error_logs.index')->with('success', $message);
    }
}
