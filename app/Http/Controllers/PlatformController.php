<?php

namespace App\Http\Controllers;

use App\Models\Platform;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PlatformController extends Controller
{
    /**
     * Az összes platformot adja vissza
     */
    public function index(Request $request): JsonResponse
    {
        $query = Platform::query();

        // Keresés név alapján
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%{$search}%");
        }

        $platforms = $query->orderBy('name')->paginate($request->input('per_page', 50));

        return response()->json($platforms);
    }

    /**
     * Egy konkrét platform részletei
     */
    public function show(int $id): JsonResponse
    {
        $platform = Platform::findOrFail($id);

        return response()->json($platform);
    }

    /**
     * Új platform létrehozása
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:platforms,name',
            'description' => 'nullable|string',
        ]);

        $platform = Platform::create($validated);

        return response()->json($platform, 201);
    }

    /**
     * Platform módosítása
     */
    public function update(int $id, Request $request): JsonResponse
    {
        $platform = Platform::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255|unique:platforms,name,' . $id,
            'description' => 'nullable|string',
        ]);

        $platform->update($validated);

        return response()->json($platform);
    }

    /**
     * Platform törlése
     */
    public function destroy(int $id): JsonResponse
    {
        $platform = Platform::findOrFail($id);
        $platform->delete();

        return response()->json(['message' => 'Platform sikeresen törölve'], 204);
    }
}
