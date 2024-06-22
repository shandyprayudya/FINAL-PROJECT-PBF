<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;

class CourseController extends Controller
{
    // Menampilkan daftar semua kursus
    public function index()
    {
        $courses = Course::all();
        return response()->json($courses);
    }

    // Menyimpan kursus baru ke dalam database
    public function store(Request $request)
    {
       try{
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'course_category_id' => 'required|exists:course_categories,id',
            'teacher_id' => 'required|exists:teachers,id',
        ]);

        $course = new Course();
        $course->title = $request->title;
        $course->course_category_id = $request->course_category_id;
        $course->teacher_id = $request->teacher_id;

        if ($request->hasFile('image')) {
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('uploads/courses'), $imageName);
            $course->image = $imageName;
        }

        $course->save();

        return response()->json(['message' => 'Course created successfully', 'course' => $course], 201);
       } catch (\Exception $e) {
        return response()->json($e->getMessage());
       }
    }

    // Menampilkan detail satu kursus
    public function show($id)
    {
        $course = Course::find($id);
        if (!$course) {
            return response()->json(['message' => 'Course not found'], 404);
        }
        return response()->json($course);
    }

    // Memperbarui data kursus di dalam database
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'course_category_id' => 'required|exists:course_categories,id',
            'teacher_id' => 'required|exists:teachers,id',
        ]);

        $course = Course::find($id);
        if (!$course) {
            return response()->json(['message' => 'Course not found'], 404);
        }

        $course->title = $request->title;
        $course->course_category_id = $request->course_category_id;
        $course->teacher_id = $request->teacher_id;

        if ($request->hasFile('image')) {
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('uploads/courses'), $imageName);
            $course->image = $imageName;
        }

        $course->save();

        return response()->json(['message' => 'Course updated successfully', 'course' => $course]);
    }

    // Menghapus kursus dari database
    public function destroy($id)
    {
        $course = Course::find($id);
        if (!$course) {
            return response()->json(['message' => 'Course not found'], 404);
        }

        $course->delete();
        return response()->json(['message' => 'Course deleted successfully']);
    }
}

