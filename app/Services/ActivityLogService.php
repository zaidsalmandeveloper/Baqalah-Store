<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class ActivityLogService
{
    public function log(
        string $module,
        string $action,
        string $title,
        ?string $description = null,
        ?string $link = null,
        ?int $userId = null
    ): ActivityLog {
        return ActivityLog::create([
            'user_id' => $userId ?? Auth::id(),
            'module' => $module,
            'action' => $action,
            'title' => $title,
            'description' => $description,
            'link' => $link,
        ]);
    }

    public function getRecent(int $limit = 8)
    {
        return ActivityLog::query()
            ->with('user:id,name,avatar')
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function getUnreadCount(): int
    {
        $user = Auth::user();

        if (! $user) {
            return 0;
        }

        $query = ActivityLog::query();

        if ($user->last_notification_read_at) {
            $query->where('created_at', '>', $user->last_notification_read_at);
        }

        return $query->count();
    }

    public function markAllAsRead(): void
    {
        $user = Auth::user();

        if ($user) {
            $user->update(['last_notification_read_at' => now()]);
        }
    }

    public function getDataTable(): JsonResponse
    {
        return DataTables::of(
            ActivityLog::query()->with('user:id,name')->select('activity_logs.*')
        )
            ->addColumn('user_name', function (ActivityLog $log) {
                return $log->user?->name ?? 'System';
            })
            ->addColumn('module_badge', function (ActivityLog $log) {
                return '<span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium text-white '.$log->module_color.'">'.$log->module_label.'</span>';
            })
            ->addColumn('action_badge', function (ActivityLog $log) {
                return '<span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-700 dark:bg-white/10 dark:text-gray-300">'.$log->action_label.'</span>';
            })
            ->addColumn('details', function (ActivityLog $log) {
                if ($log->link) {
                    return '<a href="'.$log->link.'" class="font-medium text-brand-500 hover:text-brand-600">'.$log->title.'</a>'
                        .($log->description ? '<p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">'.$log->description.'</p>' : '');
                }

                return '<span class="font-medium text-gray-800 dark:text-white/90">'.$log->title.'</span>'
                    .($log->description ? '<p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">'.$log->description.'</p>' : '');
            })
            ->addColumn('logged_at', function (ActivityLog $log) {
                return '<span class="text-gray-600 dark:text-gray-400">'.$log->created_at->format('d M Y, h:i A').'</span>'
                    .'<p class="text-xs text-gray-400">'.$log->created_at->diffForHumans().'</p>';
            })
            ->rawColumns(['module_badge', 'action_badge', 'details', 'logged_at'])
            ->make(true);
    }
}
