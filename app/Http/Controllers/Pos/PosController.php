<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use App\Http\Resources\PosMedicineResource;
use App\Sale;
use App\SaleDetail;
use App\Stock;
use Illuminate\Http\Request;

class PosController extends Controller
{
    public function posMedicines(Request $request)
    {
        $qry = Stock::where('available_qty', '>', '0');
        if ($request->filled('search')) {
            $qry->whereHas('getMedicine', function ($q) use ($request) {
                $q->where('medicine_name', 'LIKE', '%' . $request->search . '%');
            });
        }
        $posMedicines = $qry->limit('6')->get();
        if ($posMedicines->isNotEmpty()) {
            return PosMedicineResource::collection($posMedicines);
        } else {
            return response()->json(['data' => []]);
        }
    }

    public function posSaveSale(Request $request)
    {
        $post = $request->all();
        $sale = new Sale();
        $sale->user_id = 1;
        $sale->sub_total = $post['sub_total'];
        $sale->discount_type = $post['discount_type'];
        $sale->discount_amount = $post['discount_amount'];
        $sale->pay_amount = $post['pay_amount'];
        $sale->cash_amount = $post['cash_amount'];
        $sale->due_amount = $post['due_amount'];
        if ($sale->save()) {
            $saleId = $sale->id;
            foreach ($post['cart_items'] as $item) {
                $saleDetail = new SaleDetail();
                $saleDetail->sale_id = $saleId;
                $saleDetail->medicine_id = $item['medicine_id'];
                $saleDetail->medicine_name = $item['medicine_name'];
                $saleDetail->medicine_price = $item['medicine_price'];
                $saleDetail->medicine_qty = $item['medicine_qty'];
                $saleDetail->save();
            }
            return response()->json(['status' => true, 'msg' => "Sale information saved successfully."]);
        } else {
            return response()->json(['status' => false, 'msg' => "Error! Please try again."]);
        }
    }
}
