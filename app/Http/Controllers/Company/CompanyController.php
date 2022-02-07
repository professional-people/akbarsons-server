<?php

namespace App\Http\Controllers\Company;

use App\Company;
use App\Http\Controllers\Controller;
use App\Http\Resources\CompanyResource;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CompanyController extends Controller
{
    public function addCompany(Request $request): \Illuminate\Http\JsonResponse
    {
        $company = new Company();
        $company->title = Str::title($request->title);
        $company->description = $request->description;
        $company->status = 1;
        if ($company->save()) {
            return response()->json(['status' => true, 'msg' => 'Company added successfully.']);
        } else {
            return response()->json(['status' => false, 'msg' => 'Error! Please try again.']);
        }
    }

    public function allCompanies(Request $request)
    {
        $query = Company::query();

        if ($request->filled('title')) {
            $query->where('title', 'LIKE', '%' . $request->title . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        $allCompanies = $query->orderBy('id', 'DESC')->orderBy('status', 'DESC')->paginate(10);

        if ($allCompanies->isNotEmpty()) {
            return CompanyResource::collection($allCompanies);
        } else {
            return response()->json(['status' => false, 'data' => []]);
        }
    }

    public function companyStatus(Request $request)
    {
        $company = Company::find($request->id);
        $status = ($company->status == "1") ? "0" : "1";
        $company->status = $status;
        if ($company->save()) {
            return response()->json(['status' => true, 'msg' => ($status == "1") ? "Company status activated successfully." : "Company status inactivated successfully."]);
        } else {
            return response()->json(['status' => false, 'msg' => 'Error! Please try again.']);
        }
    }

    public function companyDelete(Request $request)
    {
        $company = Company::find($request->id);
        if ($company) {
            if ($company->delete()) {
                return response()->json(['status' => true, 'msg' => "Company deleted successfully."]);
            } else {
                return response()->json(['status' => false, 'msg' => 'Error! Please try again.']);
            }
        } else {
            return response()->json(['status' => false, 'msg' => 'Company not found.']);
        }
    }

    public function companyDetails(Request $request)
    {
        $company = Company::find($request->id);
        if ($company) {
            return new CompanyResource($company);
        } else {
            return response()->json(['status' => false, 'data' => []]);
        }
    }

    public function editCompany(Request $request)
    {
        $company = Company::find($request->id);
        if ($company) {
            $company->title = $request->title;
            $company->description = $request->description;
            if ($company->save()) {
                return response()->json(['status' => true, 'msg' => "Company details edited successfully."]);
            } else {
                return response()->json(['status' => false, 'msg' => 'Error! Please try again.']);
            }
        } else {
            return response()->json(['status' => false, 'msg' => 'Company not found.']);
        }
    }
}
