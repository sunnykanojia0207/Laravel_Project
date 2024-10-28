<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use App\Models\UserDetail;
use Illuminate\Support\Facades\Auth;

class CheckAgeMiddleware 
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        if (!$user) {
            // User not authenticated
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        $userDetail = UserDetail::find($user->id);
        if (!$userDetail) {
            // User details not found
            return response()->json(['error' => 'User details not found'], 404);
        }

        $dob = $userDetail->dob;

        if (!$dob) {
            // Date of birth not available
            return response()->json(['error' => 'Date of birth not available'], 400);
        }

        $age = Carbon::parse($dob)->age;

        if ($age < 18) {
            // User is below 18 years old
            return response()->json(['error' => 'User must be 18 or older'], 403);
        }

        // User is above 18, allow access
        return $next($request);
    }
}
