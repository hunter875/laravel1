<?php
// filepath: /c:/Users/minhl/laravel1/app/Http/Controllers/ProfileController.php
namespace App\Http\Controllers;

use App\Services\ProfileService;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller {
    protected $profileService;

    public function __construct(ProfileService $profileService) {
        $this->profileService = $profileService;
    }

    // Display user profile
    public function index() {
        $user = $this->profileService->getProfile();
        return view('profile.index', ['user' => $user]);
    }

    // Show the form for editing the user profile
    public function edit(User $profile) {
        return view('profile.edit', ['user' => $profile]);
    }

    // Update the user profile
    public function update(Request $request, $id) {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar) {
                Storage::delete('public/' . $user->avatar);
            }

            // Save the new avatar
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $avatarPath;
        }

        $user->save();

        return redirect()->route('profile.index')->with('success', 'Profile updated successfully.');
        log::info('Profile updated successfully.');
    }

    // Store a new user profile
    public function store(ProfileRequest $request) {
        $validated = $request->validated();

        $avatar = null;
        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar')->store('avatars', 'public');
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'avatar' => $avatar,
        ]);

        return redirect()->route('profile.index')->with('success', 'Profile created successfully.');
    }
}
