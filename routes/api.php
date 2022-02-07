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

Route::post('add-company', ['uses' => 'Company\CompanyController@addCompany']);
Route::post('all-companies', ['uses' => 'Company\CompanyController@allCompanies']);
Route::post('status-company', ['uses' => 'Company\CompanyController@companyStatus']);
Route::post('delete-company', ['uses' => 'Company\CompanyController@companyDelete']);
Route::post('company-details', ['uses' => 'Company\CompanyController@companyDetails']);
Route::post('edit-company', ['uses' => 'Company\CompanyController@editCompany']);

Route::get('companies-dropdown', ['uses' => 'Medicine\MedicineController@companiesDropdown']);
Route::get('medicine-type-dropdown', ['uses' => 'Medicine\MedicineController@medicineTypesDropdown']);
Route::post('add-medicine', ['uses' => 'Medicine\MedicineController@addMedicine']);
Route::get('all-medicines', ['uses' => 'Medicine\MedicineController@allMedicines']);
