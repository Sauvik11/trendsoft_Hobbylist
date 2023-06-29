<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Hobby;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::with('hobbies')->paginate(4);
        $hobbies = Hobby::all();
        if ($request->ajax()) {
           
            return view('pagination', compact('users', 'hobbies'))->render();
        }
        // dd($users);
        return view('adminHome', compact('users','hobbies' ));
    }
    public function store(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'hobbies' => 'required|array',
           
        ]);
        
        
        // Create a new user
        $user = new User();
        $user->first_name = $validatedData['first_name'];
        $user->last_name = $validatedData['last_name'];
       
    
        // Set a default password
        $user->password = bcrypt('default_password');
    
        // Save the user
        $user->save();
       
        // Attach the selected hobbies to the user
        $user->hobbies()->attach($validatedData['hobbies']);

        $users = User::with('hobbies')->paginate(4);
        $hobbies = Hobby::all();

        return view('adminHome', compact('users','hobbies' ));
        // Return a response
        // return response()->json(['message' => 'User created successfully'], 201);
    }
    public function show($id)
{
    $user = User::with('hobbies')->findOrFail($id);
    
    return response()->json([
        'user' => $user, 'id'=> $id
    ]);
}
public function update(Request $request, User $user)
{  
    // Validate the incoming request data
    
    $validatedData = $request->validate([
        'edit_first_name' => 'required',
        'edit_last_name' => 'required',
        'edit_hobbies' => 'nullable|array',

    ]);
    $editUserId = $request->input('edit_user_id');
    $user = User::findOrFail($editUserId);
    // Update the user's first name and last name
    $user->first_name = $validatedData['edit_first_name'];
    $user->last_name = $validatedData['edit_last_name'];
    $user->save();
    
    // Update the user's hobbies
    $user->hobbies()->sync($validatedData['edit_hobbies'] ?? []);

    // Return a success response
    $users = User::with('hobbies')->paginate(4);
    $hobbies = Hobby::all();
    return response()->json([
        'user' => $user
    ]);

    
}
public function destroy(User $user)
    {
        $user->delete();

      
        return response()->json(['message' => 'User deleted successfully']);
    }

}
