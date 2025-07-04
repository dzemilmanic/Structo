<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function index()
    {
        // Uzimamo sve korisnike sa rolom "profi"
        $users = User::where('role', 'profi')
                    ->orderBy('created_at', 'desc')
                    ->paginate(12);

        return view('users.index', compact('users'));
    }
    public function show(User $user)
{
    return view('users.show', compact('user'));
}
}
