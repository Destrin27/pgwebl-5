<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class polygonsModel extends Model
{
    protected $table = 'polygons'; // nama tabel
    protected $guarded = ['id'];

     public function geojson_polygon($id)
    {
        $polygons = $this->select(DB::raw('id, ST_AsGeoJSON(geom) as geojson, name, description, image,
        created_at, updated_at, updated_at'))->where('id', $id)->get();

        $geojson = [
            'type' => 'FeatureCollection',
            'features' => [],
        ];

        //perulangan untuk setiap titik dan tambahkan ke array fitur geojson
        foreach ($polygons as $polygon) {
            $feature = [
                'type' => 'Feature',
                'geometry' => json_decode($polygon->geojson),
                'properties' => [
                    'id' => $polygon->id,
                    'name' => $polygon->name,
                    'description' => $polygon->description,
                    'image' => $polygon->image,
                    'created_at' => $polygon->created_at,
                    'updated_at' => $polygon->updated_at,
                ],
            ];

            array_push($geojson['features'], $feature);

        }

        return $geojson;

    }

    public function geojson_polygons()
    {
        $polygons = $this->select(DB::raw('id, ST_AsGeoJSON(geom) as geojson, name, description, image,
        created_at, updated_at, updated_at'))->get();

        $geojson = [
            'type' => 'FeatureCollection',
            'features' => [],
        ];

        //perulangan untuk setiap titik dan tambahkan ke array fitur geojson
        foreach ($polygons as $polygon) {
            $feature = [
                'type' => 'Feature',
                'geometry' => json_decode($polygon->geojson),
                'properties' => [
                    'id' => $polygon->id,
                    'name' => $polygon->name,
                    'description' => $polygon->description,
                    'image' => $polygon->image,
                    'created_at' => $polygon->created_at,
                    'updated_at' => $polygon->updated_at,
                ],
            ];

            array_push($geojson['features'], $feature);

        }

        return $geojson;

    }
}
