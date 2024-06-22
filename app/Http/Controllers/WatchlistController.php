<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Watchlist;
use Illuminate\Support\Facades\Validator;

class WatchlistController extends Controller
{
    public function index()
    {
        // Mengambil semua data watchlists
        $watchlists = Watchlist::all();

        return response()->json($watchlists, 200);
    }

    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|exists:students,id',
            'course_id' => 'required|exists:courses,id',
            'lesson_id' => 'required|exists:lessons,id',
            'material_id' => 'required|exists:materials,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Buat watchlist baru
        $watchlist = new Watchlist();
        $watchlist->student_id = $request->student_id;
        $watchlist->course_id = $request->course_id;
        $watchlist->lesson_id = $request->lesson_id;
        $watchlist->material_id = $request->material_id;
        $watchlist->save();

        return response()->json(['message' => 'Watchlist created successfully', 'data' => $watchlist], 201);
    }

    public function show($id)
    {
        try {
            // Ambil watchlist berdasarkan ID
            $watchlist = Watchlist::findOrFail($id);

            return response()->json($watchlist, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Watchlist not found'], 404);
        }
    }

    public function update(Request $request, $id)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'student_id' => 'sometimes|required|exists:students,id',
            'course_id' => 'sometimes|required|exists:courses,id',
            'lesson_id' => 'sometimes|required|exists:lessons,id',
            'material_id' => 'sometimes|required|exists:materials,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        try {
            // Temukan watchlist berdasarkan ID
            $watchlist = Watchlist::findOrFail($id);

            // Update watchlist
            $watchlist->fill($request->all());
            $watchlist->save();

            return response()->json(['message' => 'Watchlist updated successfully', 'data' => $watchlist], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Watchlist not found'], 404);
        }
    }

    public function destroy($id)
    {
        try {
            // Temukan watchlist berdasarkan ID dan hapus
            $watchlist = Watchlist::findOrFail($id);
            $watchlist->delete();

            return response()->json(['message' => 'Watchlist deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Watchlist not found'], 404);
        }
    }
}
