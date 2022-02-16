<?php

namespace App\Http\Controllers\Dashboard;

use App\Company;
use App\Http\Controllers\Controller;
use App\Medicine;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboardCounter()
    {
        $totalActiveCompanies = Company::where('status', '1')->count();
        $totalActiveMedicines = Medicine::where('status', '1')->count();
        return response()->json([
            'total_active_companies' => $totalActiveCompanies,
            'total_active_medicines' => $totalActiveMedicines,
        ]);
    }
}
