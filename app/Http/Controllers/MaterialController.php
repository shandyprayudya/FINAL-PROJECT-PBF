<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Material;
use Illuminate\Support\Facades\Validator;

class MaterialController extends Controller
{
    public function index()
    {
        // Mengambil semua materials
        $materials = Material::all();

        return response()->json($materials, 200);
    }

    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'lesson_id' => 'required|exists:lessons,id',
            'title' => 'required|string|max:255',
            'type' => 'required|in:video,document',
            'content' => 'nullable|string',
            'content_url' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Buat material baru
        $material = Material::create($validator->validated());

        return response()->json(['message' => 'Material created successfully', 'data' => $material], 201);
    }

    public function show($id)
    {
        try {
            // Ambil material berdasarkan ID
            $material = Material::findOrFail($id);

            return response()->json($material, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Material not found'], 404);
        }
    }

    public function update(Request $request, $id)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'lesson_id' => 'sometimes|required|exists:lessons,id',
            'title' => 'sometimes|required|string|max:255',
            'type' => 'sometimes|required|in:video,document',
            'content' => 'nullable|string',
            'content_url' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        try {
            // Temukan material berdasarkan ID
            $material = Material::findOrFail($id);

            // Update material
            $material->fill($validator->validated());
            $material->save();

            return response()->json(['message' => 'Material updated successfully', 'data' => $material], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Material not found'], 404);
        }
    }

    public function destroy($id)
    {
        try {
            // Temukan material berdasarkan ID dan hapus
            $material = Material::findOrFail($id);
            $material->delete();

            return response()->json(['message' => 'Material deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Material not found'], 404);
        }
    }
}
