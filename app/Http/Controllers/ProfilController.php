<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class ProfilController extends Controller
{
    public function updateFoto(Request $request)
    {
        $request->validate([
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($user->foto) {
                Storage::disk('public')->delete($user->foto);
            }
            $path = $request->file('foto')->store('profile', 'public');
            $user->foto = $path;
            $user->save();
        }
        return redirect()->route('profil')->with('success', 'Foto profil berhasil diubah!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'new_password' => ['required', 'min:8', 'confirmed', 'different:current_password'],
        ], [
            'current_password.current_password' => 'Password lama tidak sesuai.',
            'new_password.different' => 'Password baru tidak boleh sama dengan password lama.',
            'new_password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        $user = Auth::user();

        // Optional: double check, though current_password rule already checks
        if (Hash::check($request->new_password, $user->password)) {
            return redirect()->back()->withErrors(['new_password' => 'Password baru tidak boleh sama dengan password lama.']);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->route('profil')->with('success', 'Password berhasil diubah!');
    }
}
