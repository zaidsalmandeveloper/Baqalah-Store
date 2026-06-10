<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateSettingRequest;
use App\Services\SettingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function __construct(
        protected SettingService $settingService
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

        return redirect()
            ->route('settings.edit')
            ->with('success', 'Settings saved successfully.');
    }
}
