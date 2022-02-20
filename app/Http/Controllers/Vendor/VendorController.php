<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Resources\VendorResourse;
use App\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VendorController extends Controller
{
    public function addVendor(Request $request)
    {
        $vendro = new Vendor();
        $vendro->vendor_name = Str::title($request->vendor_name);
        $vendro->vendor_description = $request->vendor_description;
        $vendro->status = 1;
        if ($vendro->save()) {
            return response()->json(['status' => true, 'msg' => 'Vendor added successfully.']);
        } else {
            return response()->json(['status' => false, 'msg' => 'Error! Please try again.']);
        }
    }

    public function allVendors(Request $request)
    {
        $query = Vendor::query();

        if ($request->filled('vendor_name')) {
            $query->where('vendor_name', 'LIKE', '%' . $request->vendor_name . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $allVendors = $query->orderBy('id', 'DESC')->orderBy('status', 'DESC')->paginate(10);

        if ($allVendors->isNotEmpty()) {
            return VendorResourse::collection($allVendors);
        } else {
            return response()->json(['status' => false, 'data' => []]);
        }
    }

    public function vendorStatus(Request $request)
    {
        $vendor = Vendor::find($request->id);
        $status = ($vendor->status == "1") ? "0" : "1";
        $vendor->status = $status;
        if ($vendor->save()) {
            return response()->json(['status' => true, 'msg' => ($status == "1") ? "Vendor status activated successfully." : "Vendor status inactivated successfully."]);
        } else {
            return response()->json(['status' => false, 'msg' => 'Error! Please try again.']);
        }
    }

    public function vendorDelete(Request $request)
    {
        $vendor = Vendor::find($request->id);
        if ($vendor) {
            if ($vendor->delete()) {
                return response()->json(['status' => true, 'msg' => "Vendor deleted successfully."]);
            } else {
                return response()->json(['status' => false, 'msg' => 'Error! Please try again.']);
            }
        } else {
            return response()->json(['status' => false, 'msg' => 'Vendor not found.']);
        }
    }

    public function vendorDetails(Request $request)
    {
        $vendor = Vendor::find($request->id);
        if ($vendor) {
            return new VendorResourse($vendor);
        } else {
            return response()->json(['data' => []]);
        }
    }

    public function updateVendor(Request $request)
    {
        $vendor = Vendor::find($request->id);
        if ($vendor) {
            $vendor->vendor_name = $request->vendor_name;
            $vendor->vendor_description = $request->vendor_description;
            if ($vendor->save()) {
                return response()->json(['status' => true, 'msg' => "Vendor details edited successfully."]);
            } else {
                return response()->json(['status' => false, 'msg' => 'Error! Please try again.']);
            }
        } else {
            return response()->json(['status' => false, 'msg' => 'Vendor not found.']);
        }
    }
}
