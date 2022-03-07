<?php

namespace App\Http\Controllers\Medicine;

use App\Company;
use App\Http\Controllers\Controller;
use App\Http\Resources\CompanyDropdownResource;
use App\Http\Resources\MedicineResource;
use App\Http\Resources\MedicineTypesResource;
use App\Http\Resources\VendorResourse;
use App\Medicine;
use App\MedicineType;
use App\Stock;
use App\StockType;
use App\Vendor;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    public function companiesDropdown()
    {
        $allCompanies = Company::where('status', 1)->orderBy('id', 'DESC')->get();
        if ($allCompanies->isNotEmpty()) {
            return CompanyDropdownResource::collection($allCompanies);
        } else {
            return response()->json(['status' => false, 'data' => []]);
        }
    }

    public function medicineTypesDropdown()
    {
        $medicineTypes = MedicineType::where('status', 1)->get();
        if ($medicineTypes->isNotEmpty()) {
            return MedicineTypesResource::collection($medicineTypes);
        } else {
            return response()->json(['status' => false, 'data' => []]);
        }
    }

    public function addMedicine(Request $request)
    {
        $medicine = new Medicine();
        $medicine->type_id = $request->type_id;
        $medicine->company_id = $request->company_id;
        $medicine->vendor_id = $request->vendor_id;
        $medicine->medicine_name = $request->medicine_name;
        $medicine->chemical_name = $request->chemical_name;
        $medicine->medicine_description = $request->medicine_description;
        $medicine->status = 1;
        if ($medicine->save()) {
            return response()->json(['status' => true, 'msg' => 'Medicine added successfully.']);
        } else {
            return response()->json(['status' => false, 'msg' => 'Error! Please try again.']);
        }
    }

    public function allMedicines(Request $request)
    {
        $query = Medicine::query();

        if ($request->filled("medicine_type")) {
            $query->where('type_id', $request->medicine_type);
        }

        if ($request->filled("company_name")) {
            $query->where('company_id', $request->company_name);
        }

        if ($request->filled("vendor_id")) {
            $query->where('vendor_id', $request->vendor_id);
        }

        if ($request->filled("medicine_name")) {
            $query->where('medicine_name', 'LIKE', '%' . $request->medicine_name . '%');
        }

        if ($request->filled("chemical_name")) {
            $query->where('chemical_name', 'LIKE', '%' . $request->chemical_name . '%');
        }

        if ($request->filled("medicine_status")) {
            $query->where('status', $request->medicine_status);
        }

        $allMedicines = $query->orderBy('id', 'DESC')->paginate(10);

        if ($allMedicines->isNotEmpty()) {
            return MedicineResource::collection($allMedicines);
        } else {
            return response()->json(['status' => false, 'data' => []]);
        }
    }

    public function medicineStatus(Request $request)
    {
        $medicine = Medicine::find($request->id);
        $status = ($medicine->status == "1") ? "0" : "1";
        $medicine->status = $status;
        if ($medicine->save()) {
            return response()->json(['status' => true, 'msg' => ($status == "1") ? "Medicine status activated successfully." : "Medicine status inactivated successfully."]);
        } else {
            return response()->json(['status' => false, 'msg' => 'Error! Please try again.']);
        }
    }

    public function deleteMedicine(Request $request)
    {
        $medicine = Medicine::find($request->id);

        if (empty($medicine)) {
            return response()->json(['status' => false, 'msg' => 'Error! Medicine not found..']);
        }

        if ($medicine->delete()) {
            return response()->json(['status' => true, 'msg' => "Medicine deleted successfully."]);
        } else {
            return response()->json(['status' => false, 'msg' => 'Error! Please try again.']);
        }
    }

    public function medicineDetails(Request $request)
    {
        $medicine = Medicine::find($request->id);
        if ($medicine) {
            return new MedicineResource($medicine);
        } else {
            return response()->json(['data' => []]);
        }
    }

    public function medicineUpdate(Request $request)
    {
        $medicine = Medicine::find($request->id);
        if ($medicine) {
            $medicine->type_id = $request->type_id;
            $medicine->company_id = $request->company_id;
            $medicine->vendor_id = $request->vendor_id;
            $medicine->medicine_name = $request->medicine_name;
            $medicine->chemical_name = $request->chemical_name;
            $medicine->medicine_description = $request->medicine_description;
            if ($medicine->save()) {
                return response()->json(['status' => true, 'msg' => 'Medicine update successfully.']);
            } else {
                return response()->json(['status' => false, 'msg' => 'Error! Please try again.']);
            }
        } else {
            return response()->json(['status' => false, 'msg' => 'Error! Medicine not found..']);
        }
    }

    public function vendorsListDropdown()
    {
        $vendors = Vendor::where('status', 1)->orderBy('vendor_name', 'ASC')->get();
        if ($vendors->isNotEmpty()) {
            return VendorResourse::collection($vendors);
        } else {
            return response()->json(['data' => []]);
        }
    }
}
