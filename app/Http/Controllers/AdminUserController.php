<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminUserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!Auth::user() || !Auth::user()->isAdmin()) {
                return redirect()->route('jobs.index')->with('error', 'Unauthorized access.');
            }
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $search = $request->get('search');
        $roleFilter = $request->get('role');
        
        // Base query
        $query = User::query();
        
        // Apply search filter
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('specialization', 'LIKE', "%{$search}%")
                  ->orWhere('location', 'LIKE', "%{$search}%");
            });
        }
        
        // Apply role filter
        if ($roleFilter && in_array($roleFilter, ['admin', 'user', 'profi'])) {
            $query->where('role', $roleFilter);
        }
        
        // Get users with pagination
        $users = $query->orderBy('created_at', 'desc')->paginate(12);
        
        // Get counts for each role
        $adminCount = User::where('role', 'admin')->count();
        $userCount = User::where('role', 'user')->count();
        $profiCount = User::where('role', 'profi')->count();
        $totalCount = User::count();
        
        return view('admin.users.index', compact(
            'users', 
            'search', 
            'roleFilter',
            'adminCount',
            'userCount', 
            'profiCount',
            'totalCount'
        ));
    }

    public function destroy(User $user)
    {
        try {
            // Prevent admin from deleting themselves
            if ($user->id === Auth::id()) {
                return redirect()->back()->with('error', 'You cannot delete your own account.');
            }
            
            // Prevent deleting the last admin
            if ($user->isAdmin() && User::where('role', 'admin')->count() <= 1) {
                return redirect()->back()->with('error', 'Cannot delete the last admin user.');
            }
            
            DB::beginTransaction();
            
            $userName = $user->name;
            $user->delete();
            
            DB::commit();
            
            Log::info('User deleted by admin', [
                'deleted_user_id' => $user->id,
                'deleted_user_name' => $userName,
                'admin_id' => Auth::id()
            ]);
            
            return redirect()->back()->with('success', "User '{$userName}' has been deleted successfully.");
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting user: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'admin_id' => Auth::id()
            ]);
            
            return redirect()->back()->with('error', 'Failed to delete user. Please try again.');
        }
    }

    public function demoteToUser(User $user)
    {
        try {
            // Check if user is professional
            if (!$user->isProfi()) {
                return redirect()->back()->with('error', 'User is not a professional.');
            }
            
            DB::beginTransaction();
            
            $user->update([
                'role' => 'user',
                'specialization' => null
            ]);
            
            DB::commit();
            
            Log::info('Professional demoted to user by admin', [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'admin_id' => Auth::id()
            ]);
            
            return redirect()->back()->with('success', "'{$user->name}' has been demoted from professional to regular user.");
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error demoting user: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'admin_id' => Auth::id()
            ]);
            
            return redirect()->back()->with('error', 'Failed to demote user. Please try again.');
        }
    }
}