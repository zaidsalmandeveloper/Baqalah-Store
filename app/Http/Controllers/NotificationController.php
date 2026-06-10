<?php

namespace App\Http\Controllers;

use App\Services\ActivityLogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function __construct(protected ActivityLogService $activityLogService) {}

    public function index(): View
    {
        return view('pages.notifications.index', [
            'title' => 'Activity Logs',
        ]);
    }

    public function data(): JsonResponse
    {
        return $this->activityLogService->getDataTable();
    }

    public function markRead(): JsonResponse|RedirectResponse
    {
        $this->activityLogService->markAllAsRead();

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return back();
    }
}
