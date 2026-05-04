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
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ],
        [
            'geometry_polygon.required' => 'Geometry polygon is required.',
            'name.required' => 'Name is required.',
            'name.string' => 'Name must be a string.',
            'name.max' => 'Name must be at most 255 characters.',
            'description.required' => 'Description is required.',
            'description.string' => 'Description must be a string.',
            'image.image' => 'The image must be a valid image file.',
            'image.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif.',
            'image.max' => 'The image may not be greater than 2048 kilobytes.',
        ]
    );

    if (!is_dir('storage/images')) {
            mkdir('./storage/images', 0777);
        }

        // Get Uploaded Image
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name_image = time() . "_polygon." . strtolower($image->getClientOriginalExtension());
            $image->move('storage/images', $name_image);
        } else {
            $name_image = null;
        }
        // simpan data ke database
        $data = [
            'geom' => $request->geometry_polygon,
            'name' => $request->name,
            'description' => $request->description,
            'image' => $name_image,
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
        //Mencari nama file gambar berdasarkan id polygon
        $image = $this->polygons->find($id)->image;

        // hapus data dari database
        if (!$this->polygons->destroy($id)) {
            // kembali ke halaman map
            return redirect()->route('map')->with('error', 'Failed to delete polygon.');
        }

        // hapus file gambar jika ada
        if ($image != null) {
            // cek apakah file gambar ada di direktori penyimpanan
            if (file_exists('storage/images/' . $image)) {

                // hapus file gambar dari direktori penyimpanan
                unlink('storage/images/' . $image);
            }
        }


        // kembali ke halaman map dengan pesan sukses
        return redirect()->route('map')->with('success', 'Polygon deleted successfully.');
    }
}
