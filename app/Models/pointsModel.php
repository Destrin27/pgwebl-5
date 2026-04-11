<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class pointsModel extends Model
{
    protected $table = 'points';
    protected $guarded = ['id'];

    public function geojson_points()
    {
        $points = $this->select(DB::raw('id, ST_AsGeoJSON(geom) as geojson, name, description, image,
        created_at, updated_at, updated_at'))->get();

        $geojson = [
            'type' => 'FeatureCollection',
            'features' => [],
        ];

        //perulangan untuk setiap titik dan tambahkan ke array fitur geojson
        foreach ($points as $point) {
            $feature = [
                'type' => 'Feature',
                'geometry' => json_decode($point->geojson),
                'properties' => [
                    'id' => $point->id,
                    'name' => $point->name,
                    'description' => $point->description,
                    'image' => $point->image,
                    'created_at' => $point->created_at,
                    'updated_at' => $point->updated_at,
                ],
            ];

            array_push($geojson['features'], $feature);

        }

        return $geojson;

    }
}
