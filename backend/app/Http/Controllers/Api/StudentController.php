<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    private function haversineDistance($lat1, $lon1, $lat2, $lon2)
    {
        $R = 6371.0; // Radius of the Earth in kilometers
    
        $lat1 = deg2rad($lat1);
        $lon1 = deg2rad($lon1);
        $lat2 = deg2rad($lat2);
        $lon2 = deg2rad($lon2);
    
        $dlat = $lat2 - $lat1;
        $dlon = $lon2 - $lon1;
    
        // Haversine formula
        $a = sin($dlat / 2) * sin($dlat / 2) + cos($lat1) * cos($lat2) * sin($dlon / 2) * sin($dlon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = $R * $c;
    
        return $distance;
    }

    function haversineFilter($data, $latitude, $longitude, $radius) 
    {
        $filteredData = array();
    
        foreach ($data as $point) {
            $pointLat = $point["latitude"];
            $pointLon = $point["longitude"];
    
            $distance = $this->haversineDistance($latitude, $longitude, $pointLat, $pointLon);
    
            if ($distance <= $radius) {

                // Format Data
                $point["Name"] = $point["name"];
                $point["Distance (km)"] = round($distance, 2);
                unset($point["id"]);
                unset($point["name"]);
                unset($point["latitude"]);
                unset($point["longitude"]);
                unset($point["created_at"]);
                unset($point["updated_at"]);

                $filteredData[] = $point;
            }
        }

        usort($filteredData, function($a, $b) {
            return $a["Distance (km)"] - $b["Distance (km)"];
        });
    
        return $filteredData;
    }

    public function index(Request $request)
    {
        $allStudents = Student::all();

        $latitude = $request->query('latitude');
        $longitude = $request->query('longitude');
        $radius = $request->query('radius');

        if (is_null($latitude) || is_null($longitude) || is_null($radius)) {
            return response()->json('Bad Query',422);
        }

        $students = $this->haversineFilter($allStudents, $latitude, $longitude, $radius);
        $limitStudents = array_slice($students, 0, 100);

        return response()->json($limitStudents, 200);
    }

    public function store(Request $request) {
        {

            $validator = Validator::make($request->all(), [
                'file' => 'required|mimes:csv,txt|max:2048', // Max file size: 2MB
            ]);
    
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()->first()], 400);
            }
    
            $file = $request->file('file');
            $csvData = file_get_contents($file);
            // array_slice to exclude header
            $parsedData = array_slice(array_map('str_getcsv', explode("\n", $csvData)),1);

            $latLonPattern =  "/^-?[0-9]{1,3}(?:\.[0-9]{1,10})?$/";

            foreach ($parsedData as $row) {

                $name = $row[0];
                $lat = $row[1];
                $lon = $row[2];

                if (
                    !empty($name) && 
                    preg_match($latLonPattern, $lat) && 
                    preg_match($latLonPattern, $lon)
                ) 
                {
                    Student::create([
                        'name' => $name,
                        'latitude' => $lat,
                        'longitude' => $lon
                    ]);
                }
            }
    
            return response()->json(['data' => $parsedData], 200);
        }
    }
}
