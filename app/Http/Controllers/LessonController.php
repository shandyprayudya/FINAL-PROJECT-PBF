<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lesson;
use Illuminate\Support\Facades\Validator;

class LessonController extends Controller
{
    public function index()
    {
        // Mengambil semua data lessons
        $lessons = Lesson::all();

        return response()->json($lessons, 200);
    }

    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'course_id' => 'required|exists:courses,id',
            'description' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Buat lesson baru
        $lesson = new Lesson();
        $lesson->title = $request->title;
        $lesson->course_id = $request->course_id;
        $lesson->description = $request->description;
        $lesson->notes = $request->notes;
        $lesson->save();

        return response()->json(['message' => 'Lesson created successfully', 'data' => $lesson], 201);
    }

    public function show($id)
    {
        try {
            // Ambil lesson berdasarkan ID
            $lesson = Lesson::findOrFail($id);

            return response()->json($lesson, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Lesson not found'], 404);
        }
    }

    public function update(Request $request, $id)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'course_id' => 'sometimes|required|exists:courses,id',
            'description' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        try {
            // Temukan lesson berdasarkan ID
            $lesson = Lesson::findOrFail($id);

            // Update lesson
            $lesson->fill($request->all());
            $lesson->save();

            return response()->json(['message' => 'Lesson updated successfully', 'data' => $lesson], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Lesson not found'], 404);
        }
    }

    public function destroy($id)
    {
        try {
            // Temukan lesson berdasarkan ID dan hapus
            $lesson = Lesson::findOrFail($id);
            $lesson->delete();

            return response()->json(['message' => 'Lesson deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Lesson not found'], 404);
        }
    }
}
