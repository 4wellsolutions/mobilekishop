<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\ErrorLog;
use Illuminate\Http\Request;

class ErrorLogController extends Controller
{
    // Show all error logs
    public function index()
    {
        $errorLogs = ErrorLog::latest()->paginate(100); // Get all error logs
        return view('dashboard.error_logs.index', compact('errorLogs'));
    }

    // Delete an error log
    public function destroy($id)
    {
        $errorLog = ErrorLog::findOrFail($id);
        $errorLog->delete();

        return redirect()->route('dashboard.error_logs.index')->with('success', 'Error log deleted successfully.');
    }
}
