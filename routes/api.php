<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('dashboard-counts', ['uses' => 'Dashboard\DashboardController@dashboardCounter']);
Route::get('near-expiry-list', ['uses' => 'Dashboard\DashboardController@nearExpiryMedicineList']);
Route::get('expired-list', ['uses' => 'Dashboard\DashboardController@expiredMedicineList']);

Route::post('add-company', ['uses' => 'Company\CompanyController@addCompany']);
Route::post('all-companies', ['uses' => 'Company\CompanyController@allCompanies']);
Route::post('status-company', ['uses' => 'Company\CompanyController@companyStatus']);
Route::post('delete-company', ['uses' => 'Company\CompanyController@companyDelete']);
Route::post('company-details', ['uses' => 'Company\CompanyController@companyDetails']);
Route::post('edit-company', ['uses' => 'Company\CompanyController@editCompany']);

Route::post('add-vendor', ['uses' => 'Vendor\VendorController@addVendor']);
Route::post('all-vendors', ['uses' => 'Vendor\VendorController@allVendors']);
Route::post('vendor-status', ['uses' => 'Vendor\VendorController@vendorStatus']);
Route::post('vendor-delete', ['uses' => 'Vendor\VendorController@vendorDelete']);
Route::post('vendor-details', ['uses' => 'Vendor\VendorController@vendorDetails']);
Route::post('vendor-update', ['uses' => 'Vendor\VendorController@updateVendor']);

Route::get('companies-dropdown', ['uses' => 'Medicine\MedicineController@companiesDropdown']);
Route::get('medicine-type-dropdown', ['uses' => 'Medicine\MedicineController@medicineTypesDropdown']);
Route::get('vendors-list-dropdown', ['uses' => 'Medicine\MedicineController@vendorsListDropdown']);
Route::post('add-medicine', ['uses' => 'Medicine\MedicineController@addMedicine']);
Route::post('all-medicines', ['uses' => 'Medicine\MedicineController@allMedicines']);
Route::post('medicine-status', ['uses' => 'Medicine\MedicineController@medicineStatus']);
Route::post('delete-medicine', ['uses' => 'Medicine\MedicineController@deleteMedicine']);
Route::post('medicine-details', ['uses' => 'Medicine\MedicineController@medicineDetails']);
Route::post('medicine-update', ['uses' => 'Medicine\MedicineController@medicineUpdate']);

Route::get('alert-duration', ['uses' => 'Stock\StockController@alertDurations']);
Route::get('stock-types', ['uses' => 'Stock\StockController@stockTypes']);
Route::get('medicine-list', ['uses' => 'Stock\StockController@medicineList']);
Route::post('add-stock', ['uses' => 'Stock\StockController@saveStock']);
Route::post('all-stocks', ['uses' => 'Stock\StockController@allStocks']);
Route::post('stock-status', ['uses' => 'Stock\StockController@stockStatus']);
Route::post('delete-stock', ['uses' => 'Stock\StockController@deleteStock']);
Route::post('item-detail', ['uses' => 'Stock\StockController@itemDetail']);
Route::post('add-items', ['uses' => 'Stock\StockController@addItems']);
Route::post('stock-details', ['uses' => 'Stock\StockController@stockDetails']);
Route::post('update-qty', ['uses' => 'Stock\StockController@updateQty']);
Route::post('stock-info', ['uses' => 'Stock\StockController@stockInfo']);
Route::post('update-stock', ['uses' => 'Stock\StockController@updateStock']);

Route::post('medicine-types', ['uses' => 'Medicine\MedicineTypes@medicineTypes']);
Route::post('medicine-types-status', ['uses' => 'Medicine\MedicineTypes@medicineTypesStatus']);
Route::post('add-medicine-type', ['uses' => 'Medicine\MedicineTypes@addMedicineType']);
Route::post('medicine-type-details', ['uses' => 'Medicine\MedicineTypes@medicineTypeDetails']);
Route::post('update-medicine-type', ['uses' => 'Medicine\MedicineTypes@updateMedicineType']);
Route::post('delete-medicine-type', ['uses' => 'Medicine\MedicineTypes@deleteMedicineType']);

Route::post('pos-medicines', ['uses' => 'Pos\PosController@posMedicines']);
Route::post('pos-save-sale', ['uses' => 'Pos\PosController@posSaveSale']);
