<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Teacher;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class TeacherController extends Controller
{
    public function index()
    {
        $teachers = Teacher::all();
        return response()->json($teachers, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'contact' => 'required|string|unique:teachers,contact',
            'email' => 'required|email|unique:teachers,email',
            'role_id' => 'required|exists:roles,id',
            'image' => 'nullable|string',
            'status' => 'nullable|boolean',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $teacher = new Teacher();
        $teacher->name = $request->name;
        $teacher->contact = $request->contact;
        $teacher->email = $request->email;
        $teacher->role_id = $request->role_id;
        $teacher->image = $request->image;
        $teacher->status = $request->status ?? 1; // Default to active if not provided
        $teacher->password = Hash::make($request->password);
        $teacher->save();

        return response()->json(['message' => 'Teacher created successfully', 'data' => $teacher], 201);
    }

    public function show($id)
    {
        try {
            $teacher = Teacher::findOrFail($id);
            return response()->json($teacher, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Teacher not found'], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'contact' => 'nullable|string',
            'email' => 'sometimes|required|email|unique:teachers,email,' . $id,
            'image' => 'nullable|string',
            'status' => 'nullable|boolean',
            'password' => 'nullable|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        try {
            $teacher = Teacher::findOrFail($id);

            $teacher->name = $request->name ?? $teacher->name;
            $teacher->contact = $request->contact ?? $teacher->contact;
            $teacher->email = $request->email ?? $teacher->email;
            $teacher->image = $request->image ?? $teacher->image;
            $teacher->status = $request->status ?? $teacher->status;

            if ($request->has('password')) {
                $teacher->password = Hash::make($request->password);
            }

            $teacher->save();

            return response()->json(['message' => 'Teacher updated successfully', 'data' => $teacher], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Teacher not found'], 404);
        }
    }

    public function destroy($id)
    {
        try {
            $teacher = Teacher::findOrFail($id);
            $teacher->delete();
            return response()->json(['message' => 'Teacher deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Teacher not found'], 404);
        }
    }
}
