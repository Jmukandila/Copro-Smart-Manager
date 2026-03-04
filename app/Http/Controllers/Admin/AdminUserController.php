<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminUserController extends Controller
{
    public function index()
    {
    
        $users = User::where('id', '!=', Auth::id())->latest()->get();
        return view('admin.users.index', compact('users'));
    }

    public function updateRole(Request $request, User $user)
    {
        $request->validate(['role' => 'required|in:user,admin']);

        $role = $request->role;
        $user->update([
            'role' => $role,
            'is_admin' => $role === 'admin',
        ]);

        return back()->with('success', "Le rôle de {$user->name} a été mis à jour.");
    }

    public function destroy(User $user)
    {
        $currentUser = Auth::user();

        if ($user->id === Auth::id()) {
            return back()->with('error', "Vous ne pouvez pas vous supprimer vous-même.");
        }

        // Un admin "nommé" (non propriétaire) ne peut pas supprimer un autre admin.
        if ($currentUser->occupant_status !== 'proprietaire' && $user->isAdmin()) {
            return back()->with('error', "Seul un propriétaire peut supprimer un administrateur.");
        }

        $user->delete();
        return back()->with('success', "L'utilisateur a été supprimé.");
    }
}
