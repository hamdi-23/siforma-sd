<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $teachers = Teacher::with('user')->latest()->get();
        return view('teachers.index', compact('teachers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('teachers.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'nip' => 'nullable|string|max:50|unique:teachers',
            'subject' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'hire_date' => 'nullable|date',
            'status' => 'required|in:active,inactive',
        ]);

        try {
            DB::transaction(function () use ($validated) {
                $user = User::create([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'password' => Hash::make('password'), // Default password
                    'role' => 'teacher',
                ]);

                Teacher::create([
                    'user_id' => $user->id,
                    'nip' => $validated['nip'],
                    'subject' => $validated['subject'],
                    'phone' => $validated['phone'],
                    'address' => $validated['address'],
                    'hire_date' => $validated['hire_date'],
                    'status' => $validated['status'],
                ]);
            });

            return redirect()->route('teachers.index')->with('success', 'Data Guru berhasil ditambahkan dengan kata sandi default "password".');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal menambahkan guru: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Teacher $teacher)
    {
        return view('teachers.show', compact('teacher'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Teacher $teacher)
    {
        $teacher->load('user');
        return view('teachers.form', compact('teacher'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Teacher $teacher)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($teacher->user_id)],
            'nip' => ['nullable', 'string', 'max:50', Rule::unique('teachers')->ignore($teacher->id)],
            'subject' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'hire_date' => 'nullable|date',
            'status' => 'required|in:active,inactive',
        ]);

        try {
            DB::transaction(function () use ($validated, $teacher) {
                $teacher->user->update([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                ]);

                $teacher->update([
                    'nip' => $validated['nip'],
                    'subject' => $validated['subject'],
                    'phone' => $validated['phone'],
                    'address' => $validated['address'],
                    'hire_date' => $validated['hire_date'],
                    'status' => $validated['status'],
                ]);
            });

            return redirect()->route('teachers.index')->with('success', 'Data Guru berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal memperbarui guru: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Teacher $teacher)
    {
        try {
            DB::transaction(function () use ($teacher) {
                $user = $teacher->user;
                $teacher->delete();
                if ($user) {
                    $user->delete();
                }
            });

            return redirect()->route('teachers.index')->with('success', 'Data Guru berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus guru: ' . $e->getMessage());
        }
    }
}
