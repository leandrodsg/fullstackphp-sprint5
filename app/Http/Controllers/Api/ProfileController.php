<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    private function json($success, $message, $data = null, $status = 200)
    {
        return response()->json([
            'success' => $success,
            'message' => $message,
            'data' => $data
        ], $status);
    }

    public function profile(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return $this->json(false, 'Not authenticated', null, 401);
        }
        return $this->json(true, 'User profile', [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email
            ]
        ], 200);
    }

    public function changePassword(Request $request)
    {
        $data = $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);
        $user = $request->user();
        if (!$user || !Hash::check($data['current_password'], $user->password)) {
            return $this->json(false, 'Current password is incorrect', null, 400);
        }
        $user->password = Hash::make($data['new_password']);
        $user->save();
        return $this->json(true, 'Password changed', null, 200);
    }
}
