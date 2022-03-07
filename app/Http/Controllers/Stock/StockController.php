<?php

namespace App\Http\Controllers\Stock;

use App\AlertDuration;
use App\Http\Controllers\Controller;
use App\Http\Resources\AlertDurationResource;
use App\Http\Resources\MedicineResource;
use App\Http\Resources\StockDetailsResource;
use App\Http\Resources\StockResource;
use App\Http\Resources\StockTypeResource;
use App\Medicine;
use App\Stock;
use App\StockDetail;
use App\StockType;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function alertDurations()
    {
        $alertDurations = AlertDuration::where('status', '1')->get();
        if ($alertDurations->isNotEmpty()) {
            return AlertDurationResource::collection($alertDurations);
        } else {
            return response()->json(['data' => []]);
        }
    }

    public function stockTypes()
    {
        $stockTypes = StockType::where('status', '1')->get();
        if ($stockTypes->isNotEmpty()) {
            return StockTypeResource::collection($stockTypes);
        } else {
            return response()->json(['data' => []]);
        }
    }

    public function medicineList()
    {
        $medicineList = Medicine::where('status', 1)->orderBy('medicine_name', 'ASC')->get();
        if ($medicineList->isNotEmpty()) {
            return MedicineResource::collection($medicineList);
        } else {
            return response()->json(['data' => []]);
        }
    }

    public function saveStock(Request $request)
    {
        $stockType = StockType::where('slug', $request->stock_type)->first();
        $stockData = [
            'medicine_id'           => $request->medicine_id,
            'batch_no'              => $request->batch_no,
            'expiry_date'           => $request->expiry_date,
            'is_expiry_alert'       => $request->is_expiry_alert,
            'alert_duration_id'     => $request->alert_duration_id,
            'stock_type'            => $stockType->id ?? null,
            'purchase_price'        => $request->purchase_price,
            'unit_purchase_price'   => $request->unit_purchase_price,
            'sale_price'            => $request->sale_price,
            'unit_sale_price'       => $request->unit_sale_price,
            'status'                => 1
        ];
        $stock = new Stock();
        $saveStock = $stock->create($stockData);
        if ($saveStock) {
            $stockDetailsData = [
                'stock_id'                  => $saveStock->id,
                'quantity'                  => $request->quantity,
                'quantity_in_stock_type'    => $request->quantity_in_stock_type,
                'total_quantity'            => $request->total_quantity,
                'status'                    => 1
            ];
            $stockDetails = new StockDetail();
            $saveStockDetails = $stockDetails->create($stockDetailsData);
            if ($saveStockDetails) {
                return response()->json(['status' => true, 'msg' => 'Stock added successfully.']);
            } else {
                return response()->json(['status' => false, 'msg' => 'Error! Please try again.']);
            }
        } else {
            return response()->json(['status' => false, 'msg' => 'Error! Please try again.']);
        }
    }

    public function allStocks(Request $request)
    {
        $query = Stock::query();

        if ($request->filled("medicine_id")) {
            $query->where('medicine_id', $request->medicine_id);
        }

        if ($request->filled("batch_no")) {
            $query->where('batch_no', 'LIKE', '%'.$request->batch_no.'%');
        }

        if ($request->filled("status")) {
            $query->where('status', $request->status);
        }

        $allStocks = $query->paginate(10);

        if ($allStocks->isNotEmpty()) {
            return StockResource::collection($allStocks);
        } else {
            return response()->json(['data' => []]);
        }
    }

    public function stockStatus(Request $request)
    {
        $stock = Stock::find($request->id);
        $status = ($stock->status == "1") ? "0" : "1";
        $stock->status = $status;
        if ($stock->save()) {
            return response()->json(['status' => true, 'msg' => ($status == "1") ? "Stock status activated successfully." : "Stock status inactivated successfully."]);
        } else {
            return response()->json(['status' => false, 'msg' => 'Error! Please try again.']);
        }
    }

    public function deleteStock(Request $request)
    {
        $stock = Stock::find($request->id);

        if (empty($stock)) {
            return response()->json(['status' => false, 'msg' => 'Error! Stock not found..']);
        }

        if ($stock->delete()) {
            StockDetail::where('stock_id', $request->id)->delete();
            return response()->json(['status' => true, 'msg' => "Stock deleted successfully."]);
        } else {
            return response()->json(['status' => false, 'msg' => 'Error! Please try again.']);
        }
    }

    public function itemDetail(Request $request)
    {
        $itemDetails = Stock::where('id', $request->id)->first();
        if ($itemDetails) {
            return response()->json(['data' => [
                'id'            => $itemDetails->id,
                'stock_type'    => $itemDetails->getStockType->slug ?? "",
                'qty_in_stock'  => $itemDetails->getStockDetails->quantity_in_stock_type ?? ""
            ]]);
        } else {
            return response()->json(['data' => []]);
        }
    }

    public function addItems(Request $request)
    {
        $data = [
            'stock_id'                  => $request->stock_id,
            'quantity'                  => $request->quantity,
            'quantity_in_stock_type'    => $request->quantity_in_stock_type,
            'total_quantity'            => $request->total_quantity,
            'status'                    => '1'
        ];
        $StockDetail = new StockDetail();
        $addItems = $StockDetail->create($data);
        if ($addItems) {
            return response()->json(['status' => true, 'msg' => 'Items added successfully.']);
        } else {
            return response()->json(['status' => false, 'msg' => 'Error! Please try again.']);
        }
    }

    public function stockDetails(Request $request)
    {
        $stockDetails = StockDetail::where('stock_id', $request->stock_id)->get();
        if ($stockDetails) {
            return StockDetailsResource::collection($stockDetails);
        } else {
            return response()->json(['data' => []]);
        }
    }
}
