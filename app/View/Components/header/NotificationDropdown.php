<?php

namespace App\View\Components\header;

use App\Services\ActivityLogService;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class NotificationDropdown extends Component
{
    public function __construct(protected ActivityLogService $activityLogService) {}

    public function render(): View|Closure|string
    {
        return view('components.header.notification-dropdown', [
            'notifications' => $this->activityLogService->getRecent(8),
            'unreadCount' => $this->activityLogService->getUnreadCount(),
        ]);
    }
}
