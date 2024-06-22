<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CourseCategory;

class CourseCategoryController extends Controller
{
    // Menampilkan daftar semua kategori kursus
    public function index()
    {
        $categories = CourseCategory::all();
        return response()->json($categories);
    }

    // Menyimpan kategori baru ke dalam database
    public function store(Request $request)
    {
        $request->validate([
            'category_name' => 'required|string|max:255',
            'category_status' => 'required|boolean',
            'category_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $category = new CourseCategory();
        $category->category_name = $request->category_name;
        $category->category_status = $request->category_status;

        if ($request->hasFile('category_image')) {
            $imageName = time().'.'.$request->category_image->extension();
            $request->category_image->move(public_path('uploads/courseCategories'), $imageName);
            $category->category_image = $imageName;
        }

        $category->save();

        return response()->json(['message' => 'Category created successfully', 'category' => $category], 201);
    }

    // Menampilkan satu kategori kursus
    public function show($id)
    {
        $category = CourseCategory::find($id);
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }
        return response()->json($category);
    }

    // Memperbarui kategori di dalam database
    public function update(Request $request, $id)
    {
        $request->validate([
            'category_name' => 'required|string|max:255',
            'category_status' => 'required|boolean',
            'category_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $category = CourseCategory::find($id);
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        $category->category_name = $request->category_name;
        $category->category_status = $request->category_status;

        if ($request->hasFile('category_image')) {
            $imageName = time().'.'.$request->category_image->extension();
            $request->category_image->move(public_path('uploads/courseCategories'), $imageName);
            $category->category_image = $imageName;
        }

        $category->save();

        return response()->json(['message' => 'Category updated successfully', 'category' => $category]);
    }

    // Menghapus kategori dari database
    public function destroy($id)
    {
        $category = CourseCategory::find($id);
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        $category->delete();
        return response()->json(['message' => 'Category deleted successfully']);
    }
}
