<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Userdetail;
use Illuminate\Support\Facades\Validator; // Add this line to import the Validator class
use Illuminate\Support\Facades\Auth;
use App\Models\Userinfo;
use Illuminate\Support\Facades\File;

class AuthController extends Controller
{                              
    // public function registerUser(Request $request)
    // {

    //     $validator = Validator::make($request->all(),[
    //         'fullname' => 'required|max:255',
    //         'lastname' => 'required|max:255',
    //         'email' => 'required|email|unique:userdetails,email',
    //         'password' => 'required',
    //     ]);

    //     if($validator->fails()){
    //         return response()->json([
    //             'status' => 422,
    //             'errors' => $validator->messages(), // Use messages() instead of message()
    //         ], 422); 
    //     }
    //     else{
    //         $user = Userdetail::create([
    //             'fullname' => $request->fullname,
    //             'lastname' => $request->lastname,
    //             'email' => $request->email,
    //             'password' => bcrypt($request->password),
    //         ]);
    //     }   
    //     // Return success response
    //     return response()->json(['message' => 'User registered successfully', 'user' => $user], 201);
    // }

    public function registerUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fullname' => 'required|regex:/^[a-zA-Z]+$/|max:255',
            'lastname' => 'required|regex:/^[a-zA-Z]+$/|max:255',
            'email' => 'required|email|unique:userdetails,email|ends_with:gmail.com,yahoo.com,outlook.com,outrich.com',
            'password' => 'required|min:8|max:12|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/',
            'dob'=>'required',
        ], [
            'fullname.regex' => 'The full name should contain only letters.',
            'lastname.regex' => 'The last name should contain only letters.',
            'password.regex' => 'The password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.',
            'email.in' => 'The email domain is not supported. Please use an email address with one of the following domains: @gmail.com, @yahoo.com, @outlook.com, @outrich.com.'
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages(),
            ], 422);
        }
    
        $user = Userdetail::create([
            'fullname' => $request->fullname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'dob'=> $request->dob,
        ]);
    
        // Return success response
        return response()->json(['message' => 'User registered successfully', 'user' => $user], 201);
    }
    

    
    public function loginUser(Request $request)
    {
        // // Validate the incoming request data
        // $validator = Validator::make($request->all(), [
        //     'email' => 'required|email',
        //     'password' => 'required',
        // ]);

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|ends_with:gmail.com,yahoo.com,outlook.com,outrich.com',
            'password' => 'required|min:8|max:12|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/',
        ], [
            'password.regex' => 'The password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.',
            'email.in' => 'The email domain is not supported. Please use an email address with one of the following domains: gmail.com, yahoo.com, outlook.com, outrich.com.'
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages(),
            ], 422);
        }

        // Attempt to log in the user
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            // Authentication successful
            $user = Auth::user();
            return response()->json(['message' => 'Login successful', 'user' => $user], 200);
        } else {
            // Authentication failed
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
    }
    public function getUser()
    {
        if (Auth::check()) {
            $user = Auth::user();
            return response()->json(['user' => $user]);
        } else {
            return response()->json(['error' => 'User not authenticated'], 401);
        }
    }
// public function fetchUserList()
//     {
//         try {
//             // Retrieve all users with their associated images
//             $users = Userdetail::with('userinfo')->get();
//             // dd($users);
//             // Check if users exist
//             if ($users->isEmpty()) {
//                 return response()->json(['message' => 'No users found'], 404);
//             }
            
//             // Return the list of users as JSON response
//             return response()->json(['users' => $users], 200);
//         } catch (\Exception $e) {
//             // Error occurred while fetching users
//             return response()->json(['message' => 'Failed to fetch users', 'error' => $e->getMessage()], 500);
//         }
//     }


public function fetchUserList(Request $request)
{
    try {
        $query = $request->input('query'); // Get the search query from the request
        $this->middleware('checkAge');
        // Retrieve all users with their associated images
        $queryBuilder = Userdetail::with('userinfo');

        // Apply search filter if query is provided
        if ($query) {
            $queryBuilder->where(function ($q) use ($query) {
                $q->where('fullname', 'like', '%' . $query . '%')
                  ->orWhere('lastname', 'like', '%' . $query . '%')
                  ->orWhere('email', 'like', '%' . $query . '%');
            });
        }

        $users = $queryBuilder->get();

        if ($users->isEmpty()) {
            return response()->json(['message' => 'No users found'], 404);
        }

        return response()->json(['users' => $users], 200);
    } catch (\Exception $e) {
        return response()->json(['message' => 'Failed to fetch users', 'error' => $e->getMessage()], 500);
    }
}



    public function registerInfo(Request $request)
{
    // $validator = Validator::make($request->all(), [
    //     'phoneno' => 'required|numeric',
    //     'gender' => 'required',
    //     'city' => 'required',
    //     'state' => 'required',
    //     'country' => 'required',
    //     'image' => 'required', // Validate image file type
    //     'userid' =>'required'
    // ]);

    $validator = Validator::make($request->all(), [
        'phoneno' => 'required|digits:10',
        'gender' => 'required|in:male,female,other',
        'city' => 'required',
        'state' => 'required',
        'country' => 'required',
        'image' => 'required|image|mimes:jpeg,png|max:2048', // Validate image file type, max size 2048KB
        'userid' =>'required'
    ], [
        'phoneno.digits' => 'The phone number must be a 10-digit number.',
        'gender.in' => 'The gender must be one of the following: male, female, other.',
        'image.image' => 'The uploaded file must be an image.',
        'image.mimes' => 'Only JPEG and PNG images are allowed.',
        'image.max' => 'The image size must not exceed 2048KB.',
    ]);
    

    if ($validator->fails()) {
        return response()->json([
            'status' => 422,
            'errors' => $validator->messages(),
        ], 422);
    }

    // Handle file upload
    if ($request->hasFile('image')) {
        $file = $request->file('image');
       // $fileName =  $file->getClientOriginalName();
         $fileName = time() . '_' . $file->getClientOriginalName(); 
       $destinationPath = 'images'; // Directory where the file will be stored
        if ($file->move($destinationPath, $fileName)) {
            $userDetail = Userinfo::create([
                'phoneno' => $request->phoneno,
                'gender' => $request->gender,
                'city' => $request->city,
                'state' => $request->state,
                'country' => $request->country,
                'image' => $fileName,
                'userid' => Auth::id()
            ]);

            // Return success response
            return response()->json(['message' => 'Profile saved successfully', 'userinfo' => $userDetail], 201);
        } else {
            // Failed to move the file
            return response()->json([
                'status' => 422,
                'errors' => ['image' => ['Failed to upload the image.']],
            ], 422);
        }
    } else {
        // No image file found in the request
        return response()->json([
            'status' => 422,
            'errors' => ['image' => ['No image file found in the request.']],
        ], 422);
    }
}
public function fetchUserDetails($userId)
{
    try {
        // Retrieve the user details along with associated userinfo
        $userDetails = UserDetail::with('userinfo')->where('id', $userId)->first();

        if (!$userDetails) {
            return response()->json(['error' => 'User details not found'], 404);
        }

        return response()->json($userDetails);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Failed to fetch user details: ' . $e->getMessage()], 500);
    }
}


public function updateUser(Request $request)
{
    // Validate the incoming request data
    $validator = Validator::make($request->all(), [
        'id' => 'required|exists:userdetails,id',
        'fullname' => 'required|max:255',
        'lastname' => 'required|max:255',
        'email' => 'required|email|unique:userdetails,email,' . $request->id,
        'phoneno' => 'required',
        'gender' => 'required',
        'city' => 'required',
        'state' => 'required',
        'country' => 'required',
    ]);

    // Check if image is uploaded
    if ($request->hasFile('image')) {
        $validator->addRules(['image' => 'image|mimes:jpeg,png,jpg,gif']); // Validate image file type
    }

    if ($validator->fails()) {
        return response()->json([
            'status' => 422,
            'errors' => $validator->messages(),
        ], 422);
    }

    // Find the user by ID
    $user = Userdetail::find($request->id);

    if (!$user) {
        return response()->json(['error' => 'User not found'], 404);
    }

    // Delete the previous image if a new image is provided
    if ($request->hasFile('image')) {
        if ($user->userinfo && $user->userinfo->image) {
            $imagePath = public_path('images/' . $user->userinfo->image);
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            } 
        }
    }

    // Update the user details
    $user->update([
        'fullname' => $request->fullname,
        'lastname' => $request->lastname,
        'email' => $request->email,
        'phoneno' => $request->phoneno,
    ]);

    // Update or create userinfo record
    $user->userinfo()->updateOrCreate(
        [],
        [
            'gender' => $request->gender,
            'city' => $request->city,
            'state' => $request->state,
            'country' => $request->country,
        ]
    );

    // Handle file upload if image is provided
    if ($request->hasFile('image')) {
        $file = $request->file('image');
        // $fileName = $file->getClientOriginalName();
        $fileName = time() . '_' . $file->getClientOriginalName(); 
        $destinationPath = 'images';
        $file->move($destinationPath, $fileName);
        $user->userinfo->image = $fileName;
        $user->userinfo->save();
    }

    return response()->json(['message' => 'User updated successfully', 'user' => $user]);
}

    public function deleteUser($userId)
{
    try {
        $user = Userdetail::find($userId);
        
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

         // Delete the user's associated image file
         if ($user->userinfo && $user->userinfo->image) {
            $imagePath = public_path('images/' . $user->userinfo->image);
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
        }
        $user->delete();
        return response()->json(['message' => 'User deleted successfully']);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Failed to delete user: ' . $e->getMessage()], 500);
    }
}
// public function runRPA(Request $request)
// {
//     try {
//         $process = new Process(['python', 'C:/xampp/htdocs/rpa_download_without_cleanup/main.py']);
//         $process->run();

//         // Check if the process was successful
//         if (!$process->isSuccessful()) {
//             throw new ProcessFailedException($process);
//         }

//         return response()->json(['status' => 'success', 'message' => 'RPA script executed successfully.']);
//     } catch (\Exception $e) {
//         return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
//     }
// }

public function runRPA(Request $request)
    {
        try {
            // Start the process and store the pid
            $this->process = new Process(['python', 'C:/xampp/htdocs/rpa_download_without_cleanup/main.py']);
            $this->process->start();
            $pid = $this->process->getPid();

            // Save the PID for later use to stop the process if needed
            session(['rpa_pid' => $pid]);

            // Capture and send the output in real-time
            $this->process->wait(function ($type, $buffer) {
                echo $buffer;
            });

            if (!$this->process->isSuccessful()) {
                throw new ProcessFailedException($this->process);
            }

            return response()->json(['status' => 'success', 'message' => 'RPA script executed successfully.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function stopRPA()
    {
        try {
            // Retrieve the PID from the session
            $pid = session('rpa_pid');

            if ($pid) {
                // Kill the process using the stored PID
                exec("kill $pid");

                return response()->json(['status' => 'success', 'message' => 'RPA script stopped successfully.']);
            } else {
                return response()->json(['status' => 'error', 'message' => 'No running RPA process found.']);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
