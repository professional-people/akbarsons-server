<?php

namespace App\Http\Controllers\Medicine;

use App\Company;
use App\Http\Controllers\Controller;
use App\Http\Resources\CompanyDropdownResource;
use App\Http\Resources\MedicineResource;
use App\Http\Resources\MedicineTypesResource;
use App\Medicine;
use App\MedicineType;
use App\Stock;
use App\StockType;
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
        $medicine->medicine_name = $request->medicine_name;
        $medicine->chemical_name = $request->chemical_name;
        $medicine->medicine_description = $request->medicine_description;
        $medicine->status = 1;
        if ($medicine->save()) {
            if ($request->filled("is_stock") && $request->is_stock == "1") {
                if($this->saveStock($medicine->id, $request)) {
                    return response()->json(['status' => true, 'msg' => 'Medicine and stock added successfully.']);
                } else {
                    return response()->json(['status' => false, 'msg' => 'Error! Stock data is in correct.']);
                }
            }
            return response()->json(['status' => true, 'msg' => 'Medicine added successfully.']);
        } else {
            return response()->json(['status' => false, 'msg' => 'Error! Please try again.']);
        }
    }

    public function allMedicines(Request $request)
    {
        $query = Medicine::query();

        if ($request->filled("company_name")) {
            $query->where('company_id', $request->company_name);
        }

        if ($request->filled("medicine_type")) {
            $query->where('type_id', $request->medicine_type);
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

    public function saveStock($medicineId, $request)
    {
        $stockType = StockType::where('slug', $request->stock_type)->first();

        $stock = new Stock();
        $stock->medicine_id = $medicineId;
        $stock->batch_no = $request->batch_no;
        $stock->expiry_date = Carbon::parse($request->expiry_date)->format("Y-m-d");
        $stock->is_expiry_alert = $request->is_expiry_alert;
        $stock->alert_duration_id = $request->alert_duration_id;
        $stock->stock_type = $stockType->id;
        $stock->quantity = $request->quantity;
        $stock->quantity_in_stock_type = $request->quantity_in_stock_type;
        $stock->total_quantity = $request->total_quantity;
        $stock->purchase_price = $request->purchase_price;
        $stock->unit_purchase_price = $request->unit_purchase_price;
        $stock->sale_price = $request->sale_price;
        $stock->unit_sale_price = $request->unit_sale_price;
        $stock->status = 1;
        if ($stock->save()) {
            return true;
        } else {
            return false;
        }
    }
}
