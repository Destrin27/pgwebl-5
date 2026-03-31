<?php

namespace App\Http\Controllers;

use App\Models\polygonsModel;
use Illuminate\Http\Request;

class PolygonsController extends Controller
{
    protected $polygons;

   public function __construct()
    {
        $this->polygons = new polygonsModel();
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
      //Validasi input
    $request->validate(
        [
        'geometry_polygon' => 'required',
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        ],
        [
            'geometry_polygon.required' => 'Geometry polygon is required.',
            'name.required' => 'Name is required.',
            'name.string' => 'Name must be a string.',
            'name.max' => 'Name must be at most 255 characters.',
            'description.required' => 'Description is required.',
            'description.string' => 'Description must be a string.',
        ]
    );
        $data = [
            'geom' => $request->geometry_polygon,
            'name' => $request->name,
            'description' => $request->description,
        ];

        // simpan data ke database
        if (!$this->polygons->create($data)) {
              // kembali ke halaman map
        return redirect()->route('map')->with('error', 'Failed to add polygon.');
    }

    // kembali ke halaman map dengan pesan sukses
    return redirect()->route('map')->with('success', 'Polygon added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
