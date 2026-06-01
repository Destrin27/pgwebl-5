<?php

namespace App\Http\Controllers;

use App\Models\pointsModel;
use App\Models\polylinesModel;
use App\Models\polygonsModel;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    protected $points;
    protected $polylines;
    protected $polygons;

    public function __construct()
    {
        $this->points = new pointsModel();
        $this->polylines = new polylinesModel();
        $this->polygons = new polygonsModel();
    }

    public function geojson_points()
    {
        $points = $this->points->geojson_points();

        return response()->json($points, 200, [], JSON_NUMERIC_CHECK);
    }

    public function geojson_point($id)
    {
        $point = $this->points->geojson_point($id);

        return response()->json($point, 200, [], JSON_NUMERIC_CHECK);
    }

    public function geojson_polylines()
    {
        $polylines = $this->polylines->geojson_polylines();

        return response()->json($polylines, 200, [], JSON_NUMERIC_CHECK);
    }

     public function geojson_polyline($id)
    {
        $polyline = $this->polylines->geojson_polyline($id);

        return response()->json($polyline, 200, [], JSON_NUMERIC_CHECK);
    }

    public function geojson_polygons()
    {
        $polygons = $this->polygons->geojson_polygons();

        return response()->json($polygons, 200, [], JSON_NUMERIC_CHECK);
    }

     public function geojson_polygon($id)
    {
        $polygon = $this->polygons->geojson_polygon($id);

        return response()->json($polygon, 200, [], JSON_NUMERIC_CHECK);
    }

}
