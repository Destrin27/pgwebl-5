<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class polylinesModel extends Model
{
    protected $table = 'polylines'; // nama tabel
    protected $guarded = ['id']; // primary key

    public function geojson_polylines()
    {
        $polylines = $this->select(DB::raw('id, ST_AsGeoJSON(geom) as geojson, name, description, image,
        created_at, updated_at, updated_at'))->get();

        $geojson = [
            'type' => 'FeatureCollection',
            'features' => [],
        ];

        //perulangan untuk setiap garis dan tambahkan ke array fitur geojson
        foreach ($polylines as $polyline) {
            $feature = [
                'type' => 'Feature',
                'geometry' => json_decode($polyline->geojson),
                'properties' => [
                    'id' => $polyline->id,
                    'name' => $polyline->name,
                    'description' => $polyline->description,
                    'image' => $polyline->image,
                    'created_at' => $polyline->created_at,
                    'updated_at' => $polyline->updated_at,
                ],
            ];

            array_push($geojson['features'], $feature);
        }

        return $geojson;
    }
}
