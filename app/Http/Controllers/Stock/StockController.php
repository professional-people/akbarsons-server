<?php

namespace App\Http\Controllers\Stock;

use App\AlertDuration;
use App\Http\Controllers\Controller;
use App\Http\Resources\AlertDurationResource;
use App\Http\Resources\MedicineResource;
use App\Http\Resources\StockTypeResource;
use App\Medicine;
use App\StockType;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function alertDurations()
    {
        $alertDurations = AlertDuration::where('status', '1')->get();
        if ($alertDurations->isNotEmpty()) {
            return AlertDurationResource::collection($alertDurations);
        } else {
            return response()->json(['data' => []]);
        }
    }

    public function stockTypes()
    {
        $stockTypes = StockType::where('status', '1')->get();
        if ($stockTypes->isNotEmpty()) {
            return StockTypeResource::collection($stockTypes);
        } else {
            return response()->json(['data' => []]);
        }
    }

    public function medicineList()
    {
        $medicineList = Medicine::where('status', 1)->orderBy('medicine_name', 'ASC')->get();
        if ($medicineList->isNotEmpty()) {
            return MedicineResource::collection($medicineList);
        } else {
            return response()->json(['data' => []]);
        }
    }
}
