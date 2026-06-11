<?php

namespace App\View\Components\header;

use App\Services\ActivityLogService;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class NotificationDropdown extends Component
{
    public $notifications;

    public $unreadCount;

    public function __construct(protected ActivityLogService $activityLogService)
    {
        $this->notifications = $this->activityLogService->getRecent(8);
        $this->unreadCount = $this->activityLogService->getUnreadCount();
    }

    public function render(): View|Closure|string
    {
        return view('components.header.notification-dropdown');
    }
}
