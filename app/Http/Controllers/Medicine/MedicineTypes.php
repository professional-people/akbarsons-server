<?php

namespace App\Http\Controllers\Medicine;

use App\Http\Controllers\Controller;
use App\Http\Resources\MedicineTypesResource;
use App\MedicineType;
use Illuminate\Http\Request;

class MedicineTypes extends Controller
{
    public function medicineTypes(Request $request)
    {
        $query = MedicineType::query();

        if ($request->filled('type')) {
            $query->where('type', 'LIKE', '%' . $request->type . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $medicineTypes = $query->orderBy('id', 'DESC')->paginate('10');

        if ($medicineTypes->isNotEmpty()) {
            return MedicineTypesResource::collection($medicineTypes);
        } else {
            return response()->json(['data' => []]);
        }
    }
    public function medicineTypesStatus(Request $request)
    {
        $medicineTypesStatus = MedicineType::find($request->id);
        if ($medicineTypesStatus) {
            $status = ($medicineTypesStatus->status == "1") ? "0" : "1";
            $medicineTypesStatus->status = $status;
            if ($medicineTypesStatus->save()) {
                return response()->json(['status' => true, 'msg' => ($status == "1") ? "Medicine Type status activated successfully." : "Medicine Type status inactivated successfully."]);
            } else {
                return response()->json(['status' => false, 'msg' => 'Error! Please try again.']);
            }
        } else {
            return response()->json(['status' => false, 'msg' => 'Error! Please try again.']);
        }
    }

    public function addMedicineType(Request $request)
    {
        $medicineType = new MedicineType();
        $medicineType->type = $request->type;
        $medicineType->description = $request->description;
        $medicineType->status = 1;
        if ($medicineType->save()) {
            return response()->json(['status' => true, 'msg' => 'Medicine Type added successfully.']);
        } else {
            return response()->json(['status' => false, 'msg' => 'Error! Please try again.']);
        }
    }

    public function medicineTypeDetails(Request $request)
    {
        $medicineTypesDetails = MedicineType::find($request->id);
        if ($medicineTypesDetails) {
            return new MedicineTypesResource($medicineTypesDetails);
        } else {
            return response()->json(['status' => false, 'msg' => 'Error! Please try again.']);
        }
    }

    public function updateMedicineType(Request $request)
    {
        $medicineType = MedicineType::find($request->id);
        if ($medicineType) {
            $medicineType->type = $request->type;
            $medicineType->description = $request->description;
            if ($medicineType->save()) {
                return response()->json(['status' => true, 'msg' => 'Medicine type details updated successfully.']);
            } else {
                return response()->json(['status' => false, 'msg' => 'Error! Please try again.']);
            }
        } else {
            return response()->json(['status' => false, 'msg' => 'Error! Medicine type not found.']);
        }
    }

    public function deleteMedicineType(Request $request)
    {
        $medicineType = MedicineType::find($request->id);
        if ($medicineType) {
            if ($medicineType->delete()) {
                return response()->json(['status' => true, 'msg' => 'Medicine type deleted successfully.']);
            } else {
                return response()->json(['status' => false, 'msg' => 'Error! Please try again.']);
            }
        } else {
            return response()->json(['status' => false, 'msg' => 'Error! Medicine type not found.']);
        }
    }
}
