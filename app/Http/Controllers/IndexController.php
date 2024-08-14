<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Profile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class IndexController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function admin()
    {

        $users = User::all();
        return view('admin.index', compact('users'));
    }



    public function editProfile($id)
    {
        $user = User::find($id);
        return view('editProfile', compact('user'));
    }

    public function updateProfile(Request $request, $id)
    {
        // Validasi input
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'nip' => 'required|string|max:20',
            'role' => 'required|string|max:255',
        ]);

        $user = User::find($id);

        if ($user) {
            $user->name = $validatedData['name'];
            $user->nip = $validatedData['nip'];
            $user->role = $validatedData['role'];

            $user->save(); // Pastikan model User memiliki metode ini

            return redirect()->route('admin.index')->with('success', 'Berhasil Melakukan Update Data');
        } else {
            return redirect()->route('admin.index')->with('error', 'User not found');
        }
    }
    public function deleteProfile($id)
    {
        $user = User::find($id);
        $user->delete();
        return redirect()->route('admin')->with('success', 'User deleted successfully');
    }
}
