<?php

namespace App\Http\Controllers;

use App\Models\polylinesModel;
use Illuminate\Http\Request;

class PolylinesController extends Controller
{
    protected $polylines;

    public function __construct()
    {
        $this->polylines = new polylinesModel();
    }

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
                'geometry_polyline' => 'required',
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ],
            [
                'geometry_polyline.required' => 'Geometry polyline is required.',
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
            $name_image = time() . "_polyline." . strtolower($image->getClientOriginalExtension());
            $image->move('storage/images', $name_image);
        } else {
            $name_image = null;
        }

        // simpan data ke database
        $data = [
            'geom' => $request->geometry_polyline,
            'name' => $request->name,
            'description' => $request->description,
            'image' => $name_image,
        ];

        // simpan data ke database
        if (!$this->polylines->create($data)) {
            // kembali ke halaman map
            return redirect()->route('map')->with('error', 'Failed to add polyline.');
        }

        // kembali ke halaman map dengan pesan sukses
        return redirect()->route('map')->with('success', 'Polyline added successfully.');
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
        $data = [
            'title' => 'Edit Polyline',
            'id' => $id,
            'polyline' => $this->polylines->find($id),
        ];

        return view('map-edit-polyline', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //Validasi input
        $request->validate(
            [
                'geometry' => 'required',
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ],
            [
                'geometry_polyline.required' => 'Geometry polyline is required.',
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

        $image_old = $this->polylines->find($id)->image;

        // Get Uploaded Image
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name_image = time() . "_polyline." . strtolower($image->getClientOriginalExtension());
            $image->move('storage/images', $name_image);

            // hapus file gambar jika ada
            if ($image_old != null) {

                // cek apakah file gambar ada di direktori penyimpanan
                if (file_exists('storage/images/' . $image_old)) {

                    // hapus file gambar dari direktori penyimpanan
                    unlink('storage/images/' . $image_old);
                }
            }
        } else {
            $name_image = $image_old;
        }

        // simpan data ke database
        $data = [
            'geom' => $request->geometry,
            'name' => $request->name,
            'description' => $request->description,
            'image' => $name_image,
        ];

        // simpan data ke database
        if (!$this->polylines->find($id)->update($data)) {
            // kembali ke halaman map
            return redirect()->route('map')->with('error', 'Failed to update polyline.');
        }

        // kembali ke halaman map dengan pesan sukses
        return redirect()->route('map')->with('success', 'Polyline updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //Mencari nama file gambar berdasarkan id polyline
        $image = $this->polylines->find($id)->image;

        // hapus data dari database
        if (!$this->polylines->destroy($id)) {
            // kembali ke halaman map
            return redirect()->route('map')->with('error', 'Failed to delete polyline.');
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
        return redirect()->route('map')->with('success', 'Polyline deleted successfully.');
    }
}
