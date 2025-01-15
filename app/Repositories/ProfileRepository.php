<?php
// filepath: /c:/Users/minhl/laravel1/app/Repositories/ProfileRepository.php
namespace App\Repositories;

use App\Repositories\ProfileRepositoryInterface;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class ProfileRepository implements ProfileRepositoryInterface {

    public function getProfile($user) {
        return $user;
    }

    public function updateProfile($id, array $data) {
        $user = User::findOrFail($id);

        // Kiểm tra nếu có giá trị mới cho password
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

        // Kiểm tra nếu có avatar mới
        if (isset($data['avatar']) && $data['avatar'] instanceof \Illuminate\Http\UploadedFile && $data['avatar']->isValid()) {
            // Lưu avatar mới
            $path = $data['avatar']->store('avatars', 'public');
            $data['avatar'] = $path;

            // Xóa avatar cũ nếu có
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
        } else {
            unset($data['avatar']);
        }

        $user->update($data);
        return $user->fresh();
    }

    public function deleteProfile($id) {
        return User::destroy($id);
    }
}