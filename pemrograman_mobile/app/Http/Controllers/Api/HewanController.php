<?php

namespace App\Http\Controllers\Api;

// Import model
use App\Models\Hewan;

use App\Http\Controllers\Controller;

// Import resource HewanResource
use App\Http\Resources\HewanResource;

class HewanController extends Controller
{    
    /**
     * index
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get all data hewan
        $hewan = Hewan::latest()->paginate(5);

        // Return collection of hewan as a resource
        return HewanResource::collection($hewan);
    }

    public function store(Request $request)
{
    // Validasi data
    $validatedData = $request->validate([
        'nama_hewan' => 'required|string|max:255',
        'jenis'      => 'required|string|max:255',
        'pemilik'    => 'required|string|max:255',
    ]);

    // Simpan data ke database
    $hewan = Hewan::create($validatedData);

    // Return single resource
    return new HewanResource($hewan);

    // Return response
    return response()->json([
        'success' => true,
        'message' => 'Data Hewan berhasil ditambahkan!',
        'data'    => $hewan,
    ], 201);
}

}
