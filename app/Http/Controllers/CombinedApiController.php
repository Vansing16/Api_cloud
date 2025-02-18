<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Service;
use App\Models\ContactUs;
use App\Models\Message;
use App\Models\User;
use App\Models\Freelancer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Exception;

class CombinedApiController extends Controller
{
    public function adminSignUp(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $admin = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Account created successfully. Please sign in.',
            'data' => $admin
        ], 201);
    }

    public function adminSignIn(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::guard('admin')->attempt($credentials)) {
            $admin = Auth::guard('admin')->user();
            return response()->json([
                'success' => true,
                'message' => 'Sign in successful.',
                'data' => $admin
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'The provided credentials do not match our records.',
        ], 401);
    }

    public function adminSignOut()
    {
        Auth::guard('admin')->logout();

        return response()->json([
            'success' => true,
            'message' => 'Sign out successful.',
        ], 200);
    }

    public function approvalIndex()
    {
        $services = Service::all();
        $totalServices = $services->count();

        return response()->json([
            'success' => true,
            'data' => [
                'services' => $services,
                'totalServices' => $totalServices
            ]
        ], 200);
    }

    public function approvalStore(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'thumbnail' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'cost' => 'required|numeric',
            'rate_hour' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        try {
            $service = Service::create([
                'title' => $request['title'],
                'thumbnail' => $request['thumbnail'],
                'category' => $request['category'],
                'cost' => $request['cost'],
                'rate_hour' => $request['rate_hour'],
                'description' => $request['description'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Service created successfully!',
                'data' => $service
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create service: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function contactSubmit(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string',
        ]);

        try {
            ContactUs::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Message sent successfully!',
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'There was an error sending your message. Please try again.',
            ], 500);
        }
    }

    public function messageStore(Request $request, $freelancerId)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string|max:255',
        ]);

        try {
            $message = Message::create([
                'freelancer_id' => $freelancerId,
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'message' => $request->input('message'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Message sent successfully!',
                'data' => $message
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send message',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function viewMessages()
    {
        try {
            $messages = Message::all(); // Retrieve all messages

            return response()->json([
                'success' => true,
                'data' => $messages
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve messages',
                'error' => $e->getMessage(),
            ], 500);
        }
    }



    public function reviewIndex()
    {
        $messages = ContactUs::all();
        $totalMessages = $messages->count();

        return response()->json([
            'success' => true,
            'data' => [
                'messages' => $messages,
                'totalMessages' => $totalMessages
            ]
        ], 200);
    }

    public function reviewStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string|max:255',
        ]);

        try {
            $review = ContactUs::create([
                'name' => $request->name,
                'email' => $request->email,
                'message' => $request->message,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Message sent successfully!',
                'data' => $review
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send message: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function addService()
    {
        return response()->json([
            'success' => true,
            'message' => 'Add service view.',
        ], 200);
    }

    public function serviceStore(Request $request)
    {
        // Validate input (note that 'service-thumbnail' is now nullable)
        $validated = $request->validate([
            'service-title' => 'required|string|max:255',
            'service-thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
            'service-category' => 'required|string|max:255',
            'service-cost' => 'required|numeric',
            'rate-hour' => 'required|string|max:255',
            'service-description' => 'required|string|max:255',
        ]);

        try {
            // Store thumbnail if uploaded, otherwise set to null
            $path = $request->hasFile('service-thumbnail')
                ? $request->file('service-thumbnail')->store('thumbnails', 'public')
                : null;

            // Create a new Service record
            $service = Service::create([
                'title' => $validated['service-title'],
                'thumbnail' => $path,
                'category' => $validated['service-category'],
                'cost' => $validated['service-cost'],
                'rate_hour' => $validated['rate-hour'],
                'description' => $validated['service-description'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Service posted successfully!',
                'data' => $service
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to store the service: ' . $e->getMessage(),
            ], 500);
        }
    }


    public function viewService()
    {
        try {
            $services = Service::all();

            return response()->json([
                'success' => true,
                'data' => $services
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve services: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function userIndex()
    {
        $users = User::all();
        $admins = Admin::all();
        $freelancers = Freelancer::all();

        $totalUsers = $users->count();
        $totalAdmins = $admins->count();
        $totalFreelancers = $freelancers->count();

        return response()->json([
            'success' => true,
            'data' => [
                'users' => $users,
                'admins' => $admins,
                'freelancers' => $freelancers,
                'totalUsers' => $totalUsers,
                'totalAdmins' => $totalAdmins,
                'totalFreelancers' => $totalFreelancers,
            ]
        ], 200);
    }

    public function userStore(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:signups,email|unique:admins,email|unique:freelancers,email',
            'password' => 'required|string|min:8|max:255',
            'user_type' => 'required|string|in:admin,freelancer,customer',
        ]);

        try {
            if ($request->user_type == 'admin') {
                $admin = Admin::create([
                    'name' => $request->first_name . ' ' . $request->last_name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                ]);
                return response()->json([
                    'success' => true,
                    'message' => 'Admin created successfully!',
                    'data' => $admin
                ], 201);
            } elseif ($request->user_type == 'freelancer') {
                $freelancer = Freelancer::create([
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                ]);
                return response()->json([
                    'success' => true,
                    'message' => 'Freelancer created successfully!',
                    'data' => $freelancer
                ], 201);
            } else {
                $user = User::create([
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'role_type' => $request->user_type,
                ]);
                return response()->json([
                    'success' => true,
                    'message' => 'Customer created successfully!',
                    'data' => $user
                ], 201);
            }
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create user: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function userEdit($id)
    {
        try {
            $user = User::find($id);
            if ($user) {
                return response()->json([
                    'success' => true,
                    'data' => $user
                ], 200);
            }

            $admin = Admin::find($id);
            if ($admin) {
                return response()->json([
                    'success' => true,
                    'data' => $admin
                ], 200);
            }

            $freelancer = Freelancer::find($id);
            if ($freelancer) {
                return response()->json([
                    'success' => true,
                    'data' => $freelancer
                ], 200);
            }

            return response()->json([
                'success' => false,
                'message' => 'User not found',
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve user: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function userUpdate(Request $request, $id)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:signups,email,' . $id . '|unique:admins,email,' . $id . '|unique:freelancers,email,' . $id,
            'password' => 'nullable|string|min:8|max:255',
            'user_type' => 'required|string|in:admin,freelancer,customer',
        ]);

        try {
            if ($request->user_type == 'admin') {
                $admin = Admin::find($id);
                if ($admin) {
                    $admin->name = $request->first_name . ' ' . $request->last_name;
                    $admin->email = $request->email;
                    if ($request->filled('password')) {
                        $admin->password = Hash::make($request->password);
                    }
                    $admin->save();
                    return response()->json([
                        'success' => true,
                        'message' => 'Admin updated successfully!',
                        'data' => $admin
                    ], 200);
                }
            } elseif ($request->user_type == 'freelancer') {
                $freelancer = Freelancer::find($id);
                if ($freelancer) {
                    $freelancer->first_name = $request->first_name;
                    $freelancer->last_name = $request->last_name;
                    $freelancer->email = $request->email;
                    if ($request->filled('password')) {
                        $freelancer->password = Hash::make($request->password);
                    }
                    $freelancer->save();
                    return response()->json([
                        'success' => true,
                        'message' => 'Freelancer updated successfully!',
                        'data' => $freelancer
                    ], 200);
                }
            } else {
                $user = User::find($id);
                if ($user) {
                    $user->first_name = $request->first_name;
                    $user->last_name = $request->last_name;
                    $user->email = $request->email;
                    if ($request->filled('password')) {
                        $user->password = Hash::make($request->password);
                    }
                    $user->role_type = $request->user_type;
                    $user->save();
                    return response()->json([
                        'success' => true,
                        'message' => 'Customer updated successfully!',
                        'data' => $user
                    ], 200);
                }
            }

            return response()->json([
                'success' => false,
                'message' => 'User not found',
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update user: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function userDestroy($id)
    {
        try {
            $user = User::find($id);
            if ($user) {
                $user->delete();
                return response()->json([
                    'success' => true,
                    'message' => 'User deleted successfully!',
                ], 200);
            }

            $admin = Admin::find($id);
            if ($admin) {
                $admin->delete();
                return response()->json([
                    'success' => true,
                    'message' => 'Admin deleted successfully!',
                ], 200);
            }

            $freelancer = Freelancer::find($id);
            if ($freelancer) {
                $freelancer->delete();
                return response()->json([
                    'success' => true,
                    'message' => 'Freelancer deleted successfully!',
                ], 200);
            }

            return response()->json([
                'success' => false,
                'message' => 'User not found',
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete user: ' . $e->getMessage(),
            ], 500);
        }
    }
}
