<?php

namespace App\Http\Controllers;

use App\Models\SystemLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SystemLogController extends Controller
{

    public function index()
    {
        if (!Auth::user()->can('gestionar_permisos')) {
            return redirect()->back()->with('error', 'No tienes permiso para ver Logs.');
        }



        $logs = SystemLog::all();
        return view('logs.index', compact('logs'));
    }

    /**
     * Display the details of a specific log.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!Auth::user()->can('gestionar_permisos')) {
            return redirect()->back()->with('error', 'No tienes permiso para ver Logs.');
        }

        $log = SystemLog::with('user')->findOrFail($id);
        return view('logs.show', compact('log'));
    }

    /**
     * Log a system action.
     *
     * @param string $action The action performed (create, update, delete, etc.)
     * @param string $model The model name that was affected
     * @param int|string $modelId The ID of the affected model
     * @param string|null $detail Additional details about the action
     * @param int|null $userId The ID of the user who performed the action (defaults to current user)
     * @return SystemLog
     */
    public static function log($action, $model, $modelId, $detail = null, $userId = null)
    {
        $userId = $userId ?? Auth::id();

        return SystemLog::create([
            'action' => $action,
            'model' => $model,
            'model_id' => $modelId,
            'user_id' => $userId,
            'details' => $detail,
        ]);
    }
}
