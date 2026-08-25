<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApparatusPosition;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

final class ApparatusPositionController extends Controller
{
    /**
     * Display a listing of the positions.
     */
    public function index(): JsonResponse
    {
        $positions = ApparatusPosition::withCount('apparatuses')
            ->orderBy('order')
            ->get();

        return response()->json([
            'data' => $positions,
        ]);
    }

    /**
     * Store a newly created position in storage.
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:apparatus_positions,name'],
            'order' => ['nullable', 'integer', 'min:0'],
        ], [
            'name.required' => 'Nama jabatan wajib diisi.',
            'name.unique' => 'Nama jabatan sudah terdaftar.',
            'order.integer' => 'Nomor urutan harus berupa angka.',
        ]);

        if (! isset($validated['order']) || $validated['order'] === null) {
            $maxOrder = (int) ApparatusPosition::max('order');
            $validated['order'] = $maxOrder + 1;
        }

        $position = ApparatusPosition::create($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Jabatan berhasil ditambahkan.',
                'data' => $position,
            ], 201);
        }

        return back()->with('success', "Jabatan '{$position->name}' berhasil ditambahkan.");
    }

    /**
     * Update the specified position in storage.
     */
    public function update(Request $request, ApparatusPosition $apparatusPosition): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('apparatus_positions', 'name')->ignore($apparatusPosition->id),
            ],
            'order' => ['required', 'integer', 'min:0'],
        ], [
            'name.required' => 'Nama jabatan wajib diisi.',
            'name.unique' => 'Nama jabatan sudah digunakan oleh jabatan lain.',
            'order.required' => 'Nomor urutan wajib diisi.',
            'order.integer' => 'Nomor urutan harus berupa angka.',
        ]);

        $apparatusPosition->update($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Jabatan berhasil diperbarui.',
                'data' => $apparatusPosition,
            ]);
        }

        return back()->with('success', "Jabatan '{$apparatusPosition->name}' berhasil diperbarui.");
    }

    /**
     * Remove the specified position from storage.
     */
    public function destroy(Request $request, ApparatusPosition $apparatusPosition): RedirectResponse|JsonResponse
    {
        $count = $apparatusPosition->apparatuses()->count();

        if ($count > 0) {
            $message = "Jabatan '{$apparatusPosition->name}' tidak dapat dihapus karena sedang digunakan oleh {$count} perangkat desa. Silakan ubah jabatan perangkat terkait terlebih dahulu.";

            if ($request->wantsJson()) {
                return response()->json(['message' => $message, 'error' => $message], 422);
            }

            return back()->with('error', $message);
        }

        try {
            $name = $apparatusPosition->name;
            $apparatusPosition->delete();

            if ($request->wantsJson()) {
                return response()->json([
                    'message' => "Jabatan '{$name}' berhasil dihapus.",
                ]);
            }

            return back()->with('success', "Jabatan '{$name}' berhasil dihapus.");
        } catch (QueryException $e) {
            $message = "Jabatan '{$apparatusPosition->name}' tidak dapat dihapus karena masih terikat dengan data perangkat desa.";

            if ($request->wantsJson()) {
                return response()->json(['message' => $message, 'error' => $message], 422);
            }

            return back()->with('error', $message);
        }
    }
}
