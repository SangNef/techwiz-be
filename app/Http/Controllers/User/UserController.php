<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Jobs\ActiveAccount;
use App\Jobs\VerifyPassword;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $user = User::where('email', $request->email)->first();

        if ($user->email_verified_at == null) {
            return response()->json(['error' => 'Account not activated'], 404);
        }

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        $customClaims = [
            'name' => $user->name,
            'email' => $user->email,
            'id' => $user->id,
        ];

        try {
            $token = JWTAuth::customClaims($customClaims)->fromUser($user);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not create token'], 500);
        }

        return response()->json(['token' => $token], 200);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $data = $request->only('name', 'email', 'password');
        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);

        // Store email in cache for 1 hour
        Cache::put('user_activation_' . $user->email, true, 60 * 60);

        // Send activation link
        $activationLink = route('user.activate', ['email' => $user->email]);
        ActiveAccount::dispatch($user->email, $activationLink);

        return response()->json(['message' => 'Account created. Check your email for activation link.'], 200);
    }

    public function activateAccount(Request $request)
    {
        $email = $request->query('email'); // Get email from URL

        // Check if email is in cache
        if (!Cache::has('user_activation_' . $email)) {
            return response()->json(['error' => 'Invalid or expired activation link'], 400);
        }

        // Find user by email
        $user = User::where('email', $email)->first();

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Activate account (update email_verified_at)
        $user->email_verified_at = now();
        $user->save();

        // Remove email from cache
        Cache::forget('user_activation_' . $email);

        return redirect(env('APP_FE_URL') . '/login');
    }

    public function profile(Request $request)
    {
        $user = $request->user();

        return response()->json($user, 200);
    }

    public function updateProfile(Request $request)
    {
        $user_id = $request->input('user_id');
        $user = User::find($user_id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'email' => 'nullable|email|unique:users,email,' . $user->id,
            'name' => 'nullable|string|max:255',
            'password' => 'nullable|min:6',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'currency_code' => 'nullable|string|max:10',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        if ($request->has('email') && $request->email != $user->email) {
            $user->email = $request->email;
        }

        if ($request->has('name')) {
            $user->name = $request->name;
        }

        if ($request->has('password')) {
            $user->password = Hash::make($request->password);
        }

        if ($request->has('currency_code')) {
            $user->currency_code = $request->currency_code;
        }

        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            $name = time() . '.' . $avatar->getClientOriginalExtension();
            $user->avatar = $name;
            $avatar->move(public_path('images/avatar'), $name);
        }
        $user->save();

        return response()->json(['success' => 'Profile updated successfully', 'user' => $user], 200);
    }

    public function logout()
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
        } catch (JWTException $e) {
            return response()->json(['error' => 'Failed to logout, please try again.'], 500);
        }

        return response()->json(['message' => 'Logout successfully'], 200);
    }

    public function forgotPassword(Request $request)
    {
        // Validate email
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $user = User::where('email', $request->email)->first();
        
        if (!$user) {
            return response()->json(['error' => 'Email does not exist in the system'], 404);
        }

        if ($user->email_verified_at == null) {
            return response()->json(['error' => 'Account not activated'], 404);
        }

        $code = rand(10000, 99999);
        VerifyPassword::dispatch($code, $user->email);

        // Store reset code in cache for 10 minutes
        Cache::put('password_reset_' . $user->email, $code, 600);

        return response()->json([
            'message' => 'Check your email for the verification code',
            'email' => $user->email,  // For convenience in testing via Postman
            'reset_code' => $code,  // For convenience in testing via Postman
        ], 200);
    }

    /**
     * Verify code and reset password.
     */
    public function resetPassword(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'reset_code' => 'required|numeric',
            'password' => 'required|min:6|confirmed',  // Require password confirmation
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // Find user with email
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['error' => 'Email does not exist'], 404);
        }

        // Get reset code from cache
        $cachedCode = Cache::get('password_reset_' . $user->email);

        if (!$cachedCode || $cachedCode != $request->reset_code) {
            return response()->json(['error' => 'Invalid or expired reset code'], 400);
        }

        // Update user's password
        $user->password = Hash::make($request->password);
        $user->save();
        
        // Remove reset code from cache after use
        Cache::forget('password_reset_' . $user->email);
        return response()->json(['message' => 'Password has been successfully updated'], 200);
    }
}
