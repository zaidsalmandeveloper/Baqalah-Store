@props([
    'variant' => 'full', // full | icon
    'textOnly' => false,
])

@php
    use App\Helpers\SettingsHelper;
    $logoUrl = $textOnly ? null : SettingsHelper::logoUrl();
    $brandName = SettingsHelper::brandName();
    $brandSubtitle = SettingsHelper::brandSubtitle();
    $brandInitial = SettingsHelper::brandInitial();
@endphp

<a href="/" {{ $attributes->merge(['class' => 'inline-flex items-center']) }}>
    @if ($variant === 'icon')
        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-brand-500 text-sm font-bold text-white">
            {{ $brandInitial }}
        </div>
    @elseif ($logoUrl)
        <img src="{{ $logoUrl }}" alt="{{ $brandName }}" class="h-10 max-w-[150px] object-contain dark:brightness-95" />
    @else
        <div class="flex items-center gap-2.5">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-brand-500 text-base font-bold text-white shadow-theme-xs">
                {{ $brandInitial }}
            </div>
            <div class="leading-tight">
                <span class="block text-base font-bold text-gray-900 dark:text-white/90">{{ $brandName }}</span>
                <span class="block text-[11px] font-medium text-gray-500 dark:text-gray-400">{{ $brandSubtitle }}</span>
            </div>
        </div>
    @endif
</a>
