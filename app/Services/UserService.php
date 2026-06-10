<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class UserService
{
    protected string $avatarDirectory = 'uploads/users';

    public function getDataTable(): JsonResponse
    {
        return DataTables::of(User::query()->select('users.*'))
            ->addColumn('status_badge', function (User $user) {
                if ($user->status) {
                    return '<span class="inline-flex items-center rounded-full bg-success-50 px-2.5 py-0.5 text-xs font-medium text-success-600 dark:bg-success-500/15 dark:text-success-500">Active</span>';
                }

                return '<span class="inline-flex items-center rounded-full bg-error-50 px-2.5 py-0.5 text-xs font-medium text-error-600 dark:bg-error-500/15 dark:text-error-500">Inactive</span>';
            })
            ->addColumn('action', function (User $user) {
                return view('pages.users.partials.actions', compact('user'))->render();
            })
            ->rawColumns(['status_badge', 'action'])
            ->make(true);
    }

    public function create(array $data): User
    {
        $data['password'] = Hash::make($data['password']);

        return User::create($data);
    }

    public function update(User $user, array $data, ?UploadedFile $avatar = null, bool $removeAvatar = false): User
    {
        unset($data['password'], $data['password_confirmation'], $data['avatar'], $data['remove_avatar']);

        if ($removeAvatar && $user->avatar) {
            $this->deleteAvatarFile($user->avatar);
            $data['avatar'] = null;
        }

        if ($avatar) {
            if ($user->avatar) {
                $this->deleteAvatarFile($user->avatar);
            }
            $data['avatar'] = $this->storeAvatar($avatar);
        }

        $user->update($data);

        return $user->fresh();
    }

    public function updatePassword(User $user, string $password): User
    {
        $user->update(['password' => Hash::make($password)]);

        return $user->fresh();
    }

    public function delete(User $user): void
    {
        if ($user->avatar) {
            $this->deleteAvatarFile($user->avatar);
        }

        $user->delete();
    }

    public function storeAvatar(UploadedFile $file): string
    {
        $directory = public_path($this->avatarDirectory);

        if (! File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $filename = time().'_'.preg_replace('/[^a-zA-Z0-9._-]/', '_', $file->getClientOriginalName());
        $file->move($directory, $filename);

        return $this->avatarDirectory.'/'.$filename;
    }

    protected function deleteAvatarFile(string $path): void
    {
        $fullPath = public_path($path);

        if (File::exists($fullPath)) {
            File::delete($fullPath);
        }
    }
}
