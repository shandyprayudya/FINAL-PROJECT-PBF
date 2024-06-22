<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Enrollment;
use Illuminate\Support\Facades\Validator;

class EnrollmentController extends Controller
{
    public function index()
    {
        // Mengambil semua data enrollments
        $enrollments = Enrollment::all();

        return response()->json($enrollments, 200);
    }

    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|exists:students,id',
            'course_id' => 'required|exists:courses,id',
            'enrollment_date' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Buat enrollment baru
        $enrollment = new Enrollment();
        $enrollment->student_id = $request->student_id;
        $enrollment->course_id = $request->course_id;
        $enrollment->enrollment_date = $request->enrollment_date ?? now(); // Default to current timestamp if not provided
        $enrollment->save();

        return response()->json(['message' => 'Enrollment created successfully', 'data' => $enrollment], 201);
    }

    public function show($id)
    {
        try {
            // Ambil enrollment berdasarkan ID
            $enrollment = Enrollment::findOrFail($id);

            return response()->json($enrollment, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Enrollment not found'], 404);
        }
    }

    public function update(Request $request, $id)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'student_id' => 'sometimes|required|exists:students,id',
            'course_id' => 'sometimes|required|exists:courses,id',
            'enrollment_date' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        try {
            // Temukan enrollment berdasarkan ID
            $enrollment = Enrollment::findOrFail($id);

            // Update enrollment
            $enrollment->fill($request->all());
            $enrollment->save();

            return response()->json(['message' => 'Enrollment updated successfully', 'data' => $enrollment], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Enrollment not found'], 404);
        }
    }

    public function destroy($id)
    {
        try {
            // Temukan enrollment berdasarkan ID dan hapus
            $enrollment = Enrollment::findOrFail($id);
            $enrollment->delete();

            return response()->json(['message' => 'Enrollment deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Enrollment not found'], 404);
        }
    }
}
