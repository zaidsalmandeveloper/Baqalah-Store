<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateSettingRequest;
use App\Services\ActivityLogService;
use App\Services\SettingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function __construct(
        protected SettingService $settingService,
        protected ActivityLogService $activityLogService
    ) {}

    public function edit(): View
    {
        return view('pages.settings.edit', [
            'title' => 'Add Details',
            'setting' => $this->settingService->get(),
        ]);
    }

    public function update(UpdateSettingRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $removeLogo = (bool) ($data['remove_logo'] ?? false);

        $this->settingService->save(
            $data,
            $request->file('logo'),
            $removeLogo
        );

        $this->activityLogService->log(
            'settings',
            'updated',
            'Company settings updated',
            null,
            route('settings.edit')
        );

        return redirect()
            ->route('settings.edit')
            ->with('success', 'Settings saved successfully.');
    }
}
