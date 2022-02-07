<?php

namespace App\Http\Controllers\Medicine;

use App\Company;
use App\Http\Controllers\Controller;
use App\Http\Resources\CompanyDropdownResource;
use App\Http\Resources\MedicineResource;
use App\Http\Resources\MedicineTypesResource;
use App\Medicine;
use App\MedicineType;
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

    public function allMedicines()
    {
        $allMedicines = Medicine::orderBy('id', 'DESC')->get();
        if ($allMedicines->isNotEmpty()) {
            return MedicineResource::collection($allMedicines);
        } else {
            return response()->json(['status' => false, 'data' => []]);
        }
    }
}
