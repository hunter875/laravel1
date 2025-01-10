<?php

namespace App\Http\Controllers;

use App\Models\User;  // Add this line to import the User model
use App\Services\UserService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(Request $request)
    {
        // Get the search term from the query string
        $search = $request->get('search');
        
        if ($search) {
            // Apply search and pagination
            $users = User::where('name', 'like', "%$search%")
                         ->orWhere('email', 'like', "%$search%")
                         ->paginate(10);
        } else {
            // If no search, just return all users paginated
            $users = User::paginate(10);
        }

        return view('users.index', compact('users'));
    }

    public function show($id)
    {
        $user = $this->userService->getUserById($id);
        return view('users.show', compact('user'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        // Validate input fields including password confirmation
        $data = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed', // Ensure confirmation of password
        ]);

        // Hash the password before saving
        $data['password'] = bcrypt($data['password']);

        // Create user using UserService
        $this->userService->createUser($data);

        return redirect()->route('users.index')->with('success', 'User has been created successfully.');
    }

    public function edit($id)
    {
        $user = $this->userService->getUserById($id);
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        // Validate input fields
        $data = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email,' . $id, // Ensure the email is unique except for the current user
        ]);

        // Update the user using UserService
        $this->userService->updateUser($id, $data);
        return redirect()->route('users.index')->with('success', 'User information has been updated.');
    }

    public function destroy($id)
    {
        // Delete the user using UserService
        $this->userService->deleteUser($id);
        return redirect()->route('users.index')->with('success', 'User has been deleted.');
        
    }
   
}

