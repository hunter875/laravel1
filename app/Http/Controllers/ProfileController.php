<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash; 
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use App\Models\Profile;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit($id)
    {
        $profile = Profile::where('user_id', $id)->firstOrFail();
        return view('profiles.edit', compact('profile'));
    }   
    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        // Lấy thông tin user hiện tại
        $user = $request->user();
    
        // Cập nhật thông tin từ ProfileUpdateRequest
        $user->fill($request->validated());
    
        // Kiểm tra nếu email được cập nhật, đặt lại trạng thái xác minh
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }
    
        // Lưu thông tin user
        $user->save();
    
        // Lấy hoặc tạo profile cho user
        $profile = Profile::firstOrNew(['user_id' => $user->id]);
    
        // Kiểm tra nếu có avatar mới được tải lên
        
        $profile = Profile::where('user_id', $id)->firstOrFail();

        // Kiểm tra nếu có avatar mới được tải lên
        if ($request->hasFile('avatar')) {
            // Xóa avatar cũ nếu có
            if ($profile->avatar) {
                Storage::delete('public/' . $profile->avatar);
            }

            // Lưu avatar mới
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $profile->avatar = $avatarPath;
        }

        // Cập nhật các trường còn lại
        $profile->update($request->except('avatar'));

        return redirect()->route('profiles.show', $profile->user_id)->with('success', 'Profile updated successfully.');
    }

    

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current-password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
    public function create(): View
    {
        return view('users.create');
    }

    /**
     * Store the newly created user in the database.
     */
    public function store(Request $request): RedirectResponse
    {
        // Validate the request input
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed', 
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',  // Ensure password is confirmed
        ]);

        // Hash the password before storing
        $validatedData['password'] = Hash::make($validatedData['password']);

        // Create the user
        User::create($validatedData);

        return redirect()->route('users.index')->with('success', 'User has been created successfully.');
    }
}

