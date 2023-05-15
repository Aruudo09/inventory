<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Division;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function index() {
        return view('register.registerIndex', [
            'users' => User::all(),
            'divisions' => Division::all()
        ]);
    }

    public function create() {
        return view('register.registerCreate', [
            'title' => 'register',
            'divisions' => Division::all()
        ]);
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'username' => 'required',
            'division_id' => 'required',
            'password' => ['required', 'min:6', 'max:6'],
            'userLevel' => 'required'
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        $request->session()->flash('success', 'User baru berhasil dibuat!');

        return redirect('/register/create');
    }

    public function setText($user) {
        $input = User::select('username', 'division_id')->where('id', $user)->first();

        $division = Division::select('divisionName')->where('id', $input->division_id)->first();

        $output = [
            $input,
            $division
        ];

        return response()->json($input);
    }

    public function update(Request $request, User $user) {
        
        $validated = $request->validate([
            'username' => 'required',
            'division_id' => 'required',
            'password' => ['required', 'min:6', 'max:6'],
            'userLevel' => 'required'
        ]);

        $validated['password'] = Hash::make($validated['password']);

        // DD($validated);

        if (User::where('id', $user->id)->update($validated)) {
            return redirect('/register')->with('success', 'Register berhasil diubah!');
        } else {
            return redirect('/register')->with('success', 'Register gagal diubah!');
        }
    }
}
