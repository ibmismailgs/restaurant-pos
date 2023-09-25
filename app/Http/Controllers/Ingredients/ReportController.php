<?php

namespace App\Http\Controllers\Ingredients;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class ReportController extends Controller
{
    public function InventoryReport(Request $request)
    {
        try {
            if ($request->ajax()) {

                    $data = DB::table('inventories')
                        ->Join('ingredients', 'ingredients.id', '=', 'inventories.ingredient_id')
                        ->Join('units', 'units.id', '=', 'ingredients.unit_id')
                        ->select('inventories.*', 'ingredients.name','units.unit_name','units.unit_value')
                        ->groupBy(['inventories.ingredient_id'])
                        ->selectRaw('sum(inventories.stock_in) as stockIn')
                        ->selectRaw('sum(inventories.stock_out) as stockOut')
                        ->whereNull('inventories.deleted_at')
                        ->get();

                    return Datatables::of($data)

                    ->addColumn('ingredientName', function ($data) {
                        $name = $data->name;
                        return $name;
                    })

                    ->addColumn('stockIn', function ($data) {
                        $stock = $data->stockIn;
                        $total = $stock / $data->unit_value;
                        return $total;
                    })

                    ->addColumn('stockOut', function ($data) {
                        $stock = $data->stockOut;
                        $total = $stock / $data->unit_value;
                        return $total;
                    })

                    ->addColumn('currentStock', function ($data) {
                        $stockIn = $data->stockIn;
                        $totalStockIn = $stockIn / $data->unit_value;

                        $stockOut = $data->stockOut;
                        $totalStockOut = $stockOut / $data->unit_value;

                        $currentStock = $totalStockIn - $totalStockOut;
                        return $currentStock;
                    })

                    ->addIndexColumn()
                    ->rawColumns(['ingredientName','stockIn','stockOut','currentStock'])
                    ->toJson();
            }
            return view('reports.inventory');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function MonthlyInventoryReport(Request $request)
    {
        try {
            if ($request->ajax()) {

            if($request->month){
                $startDate = Carbon::parse($request->month)->format('Y-m-d');
                $endDate = Carbon::parse($request->month)->endOfMonth()->format('Y-m-d');

                    $data = DB::table('inventories')
                        ->Join('ingredients', 'ingredients.id', '=', 'inventories.ingredient_id')
                        ->Join('units', 'units.id', '=', 'ingredients.unit_id')
                        ->select('inventories.*', 'ingredients.name','units.unit_name','units.unit_value')
                        ->groupBy(['inventories.ingredient_id'])
                        ->where('date', '>', $startDate)
                        ->where('date', '<=', $endDate)
                        ->selectRaw('sum(inventories.stock_in) as stockIn')
                        ->selectRaw('sum(inventories.stock_out) as stockOut')
                        ->whereNull('inventories.deleted_at')
                        ->get();

                }elseif($request->today){

                    $data = DB::table('inventories')
                    ->Join('ingredients', 'ingredients.id', '=', 'inventories.ingredient_id')
                    ->Join('units', 'units.id', '=', 'ingredients.unit_id')
                    ->select('inventories.*', 'ingredients.name','units.unit_name','units.unit_value')
                    ->groupBy(['inventories.ingredient_id'])
                    ->where('date', '=', $request->today)
                    ->selectRaw('sum(inventories.stock_in) as stockIn')
                    ->selectRaw('sum(inventories.stock_out) as stockOut')
                    ->whereNull('inventories.deleted_at')
                    ->get();

                }else{

                    $data = DB::table('inventories')
                    ->Join('ingredients', 'ingredients.id', '=', 'inventories.ingredient_id')
                    ->Join('units', 'units.id', '=', 'ingredients.unit_id')
                    ->select('inventories.*', 'ingredients.name','units.unit_name','units.unit_value')
                    ->groupBy(['inventories.ingredient_id'])
                    ->where('date', '>', $request->start_date)
                    ->where('date', '<=', $request->end_date)
                    ->selectRaw('sum(inventories.stock_in) as stockIn')
                    ->selectRaw('sum(inventories.stock_out) as stockOut')
                    ->whereNull('inventories.deleted_at')
                    ->get();
                }

                    return Datatables::of($data)

                    ->addColumn('ingredientName', function ($data) {
                        $name = $data->name;
                        return $name;
                    })

                    ->addColumn('stockIn', function ($data) {
                        $stock = $data->stockIn;
                        $total = $stock / $data->unit_value;
                        return $total;
                    })

                    ->addColumn('stockOut', function ($data) {
                        $stock = $data->stockOut;
                        $total = $stock / $data->unit_value;
                        return $total;
                    })


                    ->addColumn('currentStock', function ($data) {

                        $stockIn = $data->stockIn;
                        $totalStockIn = $stockIn / $data->unit_value;

                        $stockOut = $data->stockOut;
                        $totalStockOut = $stockOut / $data->unit_value;

                        $currentStock = $totalStockIn - $totalStockOut;

                        return $currentStock;
                    })

                    ->addIndexColumn()
                    ->rawColumns(['ingredientName','stockIn','stockOut','currentStock'])
                    ->toJson();
            }

        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function PurchaseReport(Request $request)
    {
        try {
            if ($request->ajax()) {

                    $data = DB::table('purchase_details')
                        ->Join('ingredients', 'ingredients.id', '=', 'purchase_details.ingredient_id')
                        ->Join('units', 'units.id', '=', 'ingredients.unit_id')
                        ->select('purchase_details.*', 'ingredients.name','units.unit_name')
                        ->groupBy(['purchase_details.ingredient_id'])
                        ->selectRaw('sum(purchase_details.quantity) as totalQuantity')
                        ->selectRaw('sum(purchase_details.amount) as totalAmount')
                        ->whereNull('purchase_details.deleted_at')
                        ->get();

                    $totalAmount = DB::table('purchase_details')->whereNull('deleted_at')->sum('amount');

                    return Datatables::of($data)

                    ->addColumn('ingredientName', function ($data) {
                        $name = $data->name;
                        return $name;
                    })

                    ->addColumn('totalQuantity', function ($data) {
                        $total = $data->totalQuantity;
                        return $total;
                    })

                    ->addColumn('totalAmount', function ($data) {
                        $total = $data->totalAmount;
                        return $total;
                    })

                    ->addIndexColumn()
                    ->with('totalAmount', number_format($totalAmount, 2))
                    ->rawColumns(['ingredientName','totalQuantity','totalAmount'])
                    ->toJson();
            }
            return view('reports.purchase');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function MonthlyPurchaseReport(Request $request)
    {
        try {
            if ($request->ajax()) {

            if($request->month){
                $startDate = Carbon::parse($request->month)->format('Y-m-d');
                $endDate = Carbon::parse($request->month)->endOfMonth()->format('Y-m-d');

                    $data = DB::table('purchase_details')
                        ->Join('ingredients', 'ingredients.id', '=', 'purchase_details.ingredient_id')
                        ->Join('units', 'units.id', '=', 'ingredients.unit_id')
                        ->Join('purchases', 'purchases.id', '=', 'purchase_details.purchase_id')
                        ->select('purchase_details.*', 'ingredients.name','purchases.date','units.unit_name')
                        ->groupBy(['purchase_details.ingredient_id'])
                        ->where('purchases.date', '>', $startDate)
                        ->where('purchases.date', '<=', $endDate)
                        ->selectRaw('sum(purchase_details.quantity) as totalQuantity')
                        ->selectRaw('sum(purchase_details.amount) as totalAmount')
                        ->whereNull('purchase_details.deleted_at')
                        ->get();

                    $totalAmount = DB::table('purchase_details')
                        ->Join('purchases', 'purchases.id', '=', 'purchase_details.purchase_id')
                        ->select('purchase_details.*','purchases.date')
                        ->where('purchases.date', '>', $startDate)
                        ->where('purchases.date', '<=', $endDate)
                        ->whereNull('purchase_details.deleted_at')
                        ->sum('purchase_details.amount');

                }elseif($request->today){

                    $data = DB::table('purchase_details')
                        ->Join('ingredients', 'ingredients.id', '=', 'purchase_details.ingredient_id')
                        ->Join('units', 'units.id', '=', 'ingredients.unit_id')
                        ->Join('purchases', 'purchases.id', '=', 'purchase_details.purchase_id')
                        ->select('purchase_details.*', 'ingredients.name','purchases.date','units.unit_name')
                        ->groupBy(['purchase_details.ingredient_id'])
                        ->where('purchases.date', '=', $request->today)
                        ->selectRaw('sum(purchase_details.quantity) as totalQuantity')
                        ->selectRaw('sum(purchase_details.amount) as totalAmount')
                        ->whereNull('purchase_details.deleted_at')
                        ->get();

                    $totalAmount = DB::table('purchase_details')
                        ->Join('purchases', 'purchases.id', '=', 'purchase_details.purchase_id')
                        ->select('purchase_details.*','purchases.date')
                        ->where('purchases.date', '=', $request->today)
                        ->whereNull('purchase_details.deleted_at')
                        ->sum('purchase_details.amount');
                }else{

                    $data = DB::table('purchase_details')
                        ->Join('ingredients', 'ingredients.id', '=', 'purchase_details.ingredient_id')
                        ->Join('units', 'units.id', '=', 'ingredients.unit_id')
                        ->Join('purchases', 'purchases.id', '=', 'purchase_details.purchase_id')
                        ->select('purchase_details.*', 'ingredients.name','purchases.date','units.unit_name')
                        ->groupBy(['purchase_details.ingredient_id'])
                        ->where('purchases.date', '>', $request->start_date)
                        ->where('purchases.date', '<=', $request->end_date)
                        ->selectRaw('sum(purchase_details.quantity) as totalQuantity')
                        ->selectRaw('sum(purchase_details.amount) as totalAmount')
                        ->whereNull('purchase_details.deleted_at')
                        ->get();

                    $totalAmount = DB::table('purchase_details')
                        ->Join('purchases', 'purchases.id', '=', 'purchase_details.purchase_id')
                        ->select('purchase_details.*','purchases.date')
                        ->where('purchases.date', '>', $request->start_date)
                        ->where('purchases.date', '<=', $request->end_date)
                        ->whereNull('purchase_details.deleted_at')
                        ->sum('purchase_details.amount');
                }

                    return Datatables::of($data)

                    ->addColumn('ingredientName', function ($data) {
                        $name = $data->name;
                        return $name;
                    })

                    ->addColumn('totalQuantity', function ($data) {
                        $total = $data->totalQuantity;
                        return $total;
                    })

                    ->addColumn('totalAmount', function ($data) {
                        $total = $data->totalAmount;
                        return $total;
                    })

                    ->addIndexColumn()
                    ->with('totalAmount', number_format($totalAmount, 2))
                    ->rawColumns(['ingredientName','totalQuantity','totalAmount'])
                    ->toJson();
            }

        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function SaleReport(Request $request)
    {
        try {
            if ($request->ajax()) {

                    $data = DB::table('sale_details')
                        ->Join('products', 'products.id', '=', 'sale_details.product_id')
                        ->Join('units', 'units.id', '=', 'products.unit_id')
                        ->select('sale_details.*', 'products.name','units.unit_name')
                        ->groupBy(['sale_details.product_id'])
                        ->selectRaw('sum(sale_details.quantity) as totalQuantity')
                        ->selectRaw('sum(sale_details.amount) as totalAmount')
                        ->whereNull('sale_details.deleted_at')
                        ->get();

                    $totalAmount = DB::table('sale_details')
                                ->whereNull('deleted_at')
                                ->sum('amount');

                    return Datatables::of($data)

                    ->addColumn('productName', function ($data) {
                        $name = $data->name;
                        return $name;
                    })

                    ->addColumn('totalQuantity', function ($data) {
                        $total = $data->totalQuantity;
                        return $total;
                    })

                    ->addColumn('totalAmount', function ($data) {
                        $total = $data->totalAmount;
                        return $total;
                    })

                    ->addIndexColumn()
                    ->with('totalAmount', number_format($totalAmount, 2))
                    ->rawColumns(['productName','totalQuantity','totalAmount'])
                    ->toJson();
            }
            return view('reports.sale');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function MonthlySaleReport(Request $request)
    {
        try {
            if ($request->ajax()) {

            if($request->month){
                $startDate = Carbon::parse($request->month)->format('Y-m-d');
                $endDate = Carbon::parse($request->month)->endOfMonth()->format('Y-m-d');

                    $data = DB::table('sale_details')
                        ->Join('products', 'products.id', '=', 'sale_details.product_id')
                        ->Join('units', 'units.id', '=', 'products.unit_id')
                        ->Join('sales', 'sales.id', '=', 'sale_details.sale_id')
                        ->select('sale_details.*', 'products.name','sales.date','units.unit_name')
                        ->groupBy(['sale_details.product_id'])
                        ->where('sales.date', '>', $startDate)
                        ->where('sales.date', '<=', $endDate)
                        ->selectRaw('sum(sale_details.quantity) as totalQuantity')
                        ->selectRaw('sum(sale_details.amount) as totalAmount')
                        ->whereNull('sale_details.deleted_at')
                        ->get();

                    $totalAmount = DB::table('sale_details')
                        ->Join('sales', 'sales.id', '=', 'sale_details.sale_id')
                        ->select('sale_details.*','sales.date')
                        ->where('sales.date', '>', $startDate)
                        ->where('sales.date', '<=', $endDate)
                        ->whereNull('sale_details.deleted_at')
                        ->sum('sale_details.amount');

                }elseif($request->today){

                    $data = DB::table('sale_details')
                        ->Join('products', 'products.id', '=', 'sale_details.product_id')
                        ->Join('units', 'units.id', '=', 'products.unit_id')
                        ->Join('sales', 'sales.id', '=', 'sale_details.sale_id')
                        ->select('sale_details.*', 'products.name','sales.date','units.unit_name')
                        ->groupBy(['sale_details.product_id'])
                        ->where('sales.date', '=', $request->today)
                        ->selectRaw('sum(sale_details.quantity) as totalQuantity')
                        ->selectRaw('sum(sale_details.amount) as totalAmount')
                        ->whereNull('sale_details.deleted_at')
                        ->get();

                    $totalAmount = DB::table('sale_details')
                        ->Join('sales', 'sales.id', '=', 'sale_details.sale_id')
                        ->select('sale_details.*','sales.date')
                        ->where('sales.date', '=', $request->today)
                        ->whereNull('sale_details.deleted_at')
                        ->sum('sale_details.amount');

                }else{

                    $data = DB::table('sale_details')
                        ->Join('products', 'products.id', '=', 'sale_details.product_id')
                        ->Join('units', 'units.id', '=', 'products.unit_id')
                        ->Join('sales', 'sales.id', '=', 'sale_details.sale_id')
                        ->select('sale_details.*', 'products.name','sales.date','units.unit_name')
                        ->groupBy(['sale_details.product_id'])
                        ->where('sales.date', '>', $request->start_date)
                        ->where('sales.date', '<=', $request->end_date)
                        ->selectRaw('sum(sale_details.quantity) as totalQuantity')
                        ->selectRaw('sum(sale_details.amount) as totalAmount')
                        ->whereNull('sale_details.deleted_at')
                        ->get();

                    $totalAmount = DB::table('sale_details')
                        ->Join('products', 'products.id', '=', 'sale_details.product_id')
                        ->Join('sales', 'sales.id', '=', 'sale_details.sale_id')
                        ->select('sale_details.*', 'products.name','sales.date')
                        ->where('sales.date', '>', $request->start_date)
                        ->where('sales.date', '<=', $request->end_date)
                        ->whereNull('sale_details.deleted_at')
                        ->sum('sale_details.amount');
                }

                    return Datatables::of($data)

                    ->addColumn('productName', function ($data) {
                        $name = $data->name;
                        return $name;
                    })

                    ->addColumn('totalQuantity', function ($data) {
                        $total = $data->totalQuantity;
                        return $total;
                    })

                    ->addColumn('totalAmount', function ($data) {
                        $total = $data->totalAmount;
                        return $total;
                    })

                    ->addIndexColumn()
                    ->with('totalAmount', number_format($totalAmount, 2))
                    ->rawColumns(['productName','totalQuantity','totalAmount'])
                    ->toJson();
            }

        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }
}
