<div class="flex items-center gap-2">
    <a href="{{ route('users.edit', $user) }}"
        class="inline-flex items-center justify-center rounded-lg bg-brand-500 px-3 py-1.5 text-xs font-medium text-white hover:bg-brand-600">
        Edit
    </a>
    @if ($user->id !== auth()->id())
        <form action="{{ route('users.destroy', $user) }}" method="POST"
            onsubmit="return confirm('Are you sure you want to delete this user?');">
            @csrf
            @method('DELETE')
            <button type="submit"
                class="inline-flex items-center justify-center rounded-lg bg-error-500 px-3 py-1.5 text-xs font-medium text-white hover:bg-error-600">
                Delete
            </button>
        </form>
    @endif
</div>
