<?php

namespace App\Http\Controllers\Dashboard;

use App\Company;
use App\Http\Controllers\Controller;
use App\Medicine;
use App\Stock;
use App\Vendor;

class DashboardController extends Controller
{
    public function dashboardCounter()
    {
        $totalActiveCompanies = Company::where('status', '1')->count();
        $totalActiveMedicines = Medicine::where('status', '1')->count();
        $totalActiveVendors = Vendor::where('status', '1')->count();
        $totalNearExpire = $this->expiryMedicine('near');
        $totalExpired = $this->expiryMedicine('expired');
        return response()->json([
            'total_active_companies'    => $totalActiveCompanies,
            'total_active_medicines'    => $totalActiveMedicines,
            'total_active_vendors'      => $totalActiveVendors,
            'total_near_expiry'         => count($totalNearExpire),
            'total_expired'             => count($totalExpired)
        ]);
    }

    public function expiryMedicine($type)
    {
        $stockInfo = Stock::with(['getStockDetails'])->where('is_expiry_alert', '1')->get();
        return $stockInfo->map(function ($item) use ($type) {
            $qty = $item->getStockDetails->sum('total_quantity');
            $expiryDate = $item->expiry_date;
            $alertBefore = $item->getAlertDuration->duration;
            $alertDate = date("Y-m-d", strtotime('+' . $alertBefore . ' month'));
            $today = date("Y-m-d");
            if ($qty > 0 && $alertDate >= $expiryDate && $today < $expiryDate && $type == 'near') {
                return $item->medicine_id;
            } elseif ($qty > 0 && $alertDate >= $expiryDate && $today > $expiryDate && $type == 'expired') {
                return $item->medicine_id;
            } elseif ($qty > 0 && $alertDate >= $expiryDate && $today < $expiryDate && $type == 'nearExpList') {
                return [
                    'medicine_title'    => $item->getMedicine->medicine_name ?? "",
                    'batch_no'          => $item->batch_no,
                    'expiry_date'       => $item->expiry_date
                ];
            } elseif ($qty > 0 && $alertDate >= $expiryDate && $today > $expiryDate && $type == 'expList') {
                return [
                    'medicine_title'    => $item->getMedicine->medicine_name ?? "",
                    'batch_no'          => $item->batch_no,
                    'expiry_date'       => $item->expiry_date
                ];
            }
        })->filter()->values()->toArray();
    }

    public function nearExpiryMedicineList()
    {
        $data = $this->expiryMedicine('nearExpList');
        return response()->json(['data' => $data]);
    }

    public function expiredMedicineList()
    {
        $data = $this->expiryMedicine('expList');
        return response()->json(['data' => $data]);
    }
}
