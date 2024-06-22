<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class StudentController extends Controller
{
    public function index()
    {
        // Mengambil semua data students
        $students = Student::all();

        return response()->json($students, 200);
    }

    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'contact' => 'nullable|string',
            'email' => 'required|email|unique:students,email',
            'image' => 'nullable|string',
            'status' => 'nullable|boolean',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Buat student baru
        $student = new Student();
        $student->name = $request->name;
        $student->contact = $request->contact;
        $student->email = $request->email;
        $student->image = $request->image;
        $student->status = $request->status ?? true; // default to true if not provided
        $student->password = Hash::make($request->password);
        $student->save();

        return response()->json(['message' => 'Student created successfully', 'data' => $student], 201);
    }

    public function show($id)
    {
        try {
            // Ambil student berdasarkan ID
            $student = Student::findOrFail($id);

            return response()->json($student, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Student not found'], 404);
        }
    }

    public function update(Request $request, $id)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'contact' => 'nullable|string',
            'email' => 'sometimes|required|email|unique:students,email,' . $id,
            'image' => 'nullable|string',
            'status' => 'nullable|boolean',
            'password' => 'nullable|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        try {
            $student = Student::findOrFail($id);

            // Update student
            $student->name = $request->name ?? $student->name;
            $student->contact = $request->contact ?? $student->contact;
            $student->email = $request->email ?? $student->email;
            $student->image = $request->image ?? $student->image;
            $student->status = $request->status ?? $student->status;

            if ($request->has('password')) {
                $student->password = Hash::make($request->password);
            }

            $student->save();

            return response()->json(['message' => 'Student updated successfully', 'data' => $student], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Student not found'], 404);
        }
    }

    public function destroy($id)
    {
        try {
            // Temukan student berdasarkan ID dan hapus
            $student = Student::findOrFail($id);
            $student->delete();

            return response()->json(['message' => 'Student deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Student not found'], 404);
        }
    }
}
