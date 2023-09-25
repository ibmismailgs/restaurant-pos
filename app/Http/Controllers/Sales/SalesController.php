<?php

namespace App\Http\Controllers\Sales;

use Carbon\Carbon;
use App\Models\Sales\Sales;
use Illuminate\Http\Request;
use App\Models\Products\Products;
use App\Models\Sales\SaleDetails;
use Illuminate\Support\Facades\DB;
use App\Models\Inventory\Inventory;
use App\Http\Controllers\Controller;
use App\Models\Ingredients\GeneralSetting;
use App\Models\Ingredients\Ingredients;
use App\Models\Products\Recipes;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class SalesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                    $data = DB::table('sales')
                        ->whereNull('deleted_at')
                        ->get();

                    return Datatables::of($data)

                    ->addColumn('date', function ($data) {
                        $date = Carbon::parse($data->date)->format('d F, Y');
                        return $date;
                    })

                    ->addColumn('action', function ($data) {

                        $button = '';

                        $show = '<li><a class="dropdown-item" href="' . route('sales.show', $data->id) . ' " ><i class="ik ik-eye f-16 text-blue"></i> Details</a></li>';

                        $edit = '<li><a class="dropdown-item" id="edit" href="' . route('sales.edit', $data->id) . ' " title="Edit"><i class="ik ik-edit f-16 text-green"></i> Edit</a></li>';

                        $delete = '<li><a class="dropdown-item" id="delete" href="' . route('sales.destroy', $data->id) . ' " title="Delete"><i class="ik ik-trash-2 f-16 text-red"></i> Delete</a></li>';

                        if(Auth::user()->can('manage_user')){
                            $button =  $show . $edit . $delete;
                        }

                        return '<div class="btn-group open">
                            <a class="badge badge-primary dropdown-toggle" href="#" role="button"  data-toggle="dropdown">Actions<i class="ik ik-chevron-down mr-0 align-middle"></i></a>
                            <ul class="dropdown-menu" role="menu" style="width:auto; min-width:auto;">'.$button.'
                        </ul>
                        </div>';
                    })

                    ->addIndexColumn()
                    ->rawColumns(['date','action'])
                    ->toJson();
            }
            return view('sales.index');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function Product(Request $request)
    {
        $product = Products::where('id', $request->product_id)->first();
        $recipe = Recipes::where('product_id', $request->product_id)->first();
        return response()->json([
            'product' => $product,
            'recipe' => $recipe,
        ]);
    }

    public function create()
    {
        $products = Products::all();
        $orderNo = Sales::count();
        return view('sales.create', compact('products','orderNo'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $messages = array(
            'date.required'  => 'Enter date',
            'product_id.required'  => 'Select Product',
            'quantity.required'  => 'Enter quantity',
            'payment_type.required'  => 'Select payment type',
        );

        $this->validate($request, array(
            'date' => 'required',
            'quantity' => 'required',
            'product_id' => 'required',
            'payment_type' => 'required',
        ), $messages);

        DB::beginTransaction();

        try {
            $sale = new Sales();
            $sale->date = $request->date;
            $sale->order_no = $request->order_no;
            $sale->customer_name = $request->customer_name;
            $sale->customer_mobile = $request->customer_mobile;
            $sale->total_quantity = $request->total_quantity;
            $sale->grand_total = $request->grand_total;
            $sale->discount = $request->discount;
            $sale->tax_amount = $request->tax_amount;
            $sale->payment_type = $request->payment_type;
            $sale->transaction_id = $request->transaction_id;
            $sale->change_amount = $request->change_amount;
            $sale->note = $request->note;
            $sale->created_by = Auth::user()->id;
            $sale->save();

            $ingredientIds = [];

            foreach($request->product_id as $key => $productId) {
                $saleDetail = new SaleDetails();
                $saleDetail->sale_id         = $sale->id;
                $saleDetail->product_id      = $productId;
                $saleDetail->quantity        = $request->quantity[$key];
                $saleDetail->amount          = $request->amount[$key];
                $saleDetail->price          = $request->price[$key];
                $saleDetail->created_by      = Auth::user()->id;
                $saleDetail->save();

                $recipe = Recipes::where('product_id', $productId)->first();
                $ingredientIds[] = json_decode($recipe->ingredient_id);
                $ingredientQuantity[] = json_decode($recipe->quantity);
            }

            foreach ($ingredientIds as $key => $ingredientId){
                foreach ($ingredientId as $value => $id){
                    $ingredientQuantities = $ingredientQuantity[$key][$value];
                    $ingredient = Ingredients::find($id);
                    $unit = $ingredient->units->unit_value;

                    $inventory = new Inventory();
                    $inventory->sale_id         = $sale->id;
                    $inventory->product_id      = $request->product_id[$key];
                    $inventory->ingredient_id   = $id;
                    $inventory->date            = $request->date;
                    $inventory->stock_out       = ($request->quantity[$key]) * ($unit) *  ($ingredientQuantities);
                    $inventory->created_by      = Auth::user()->id;
                    $inventory->save();
                }
            }

            DB::commit();

            return redirect()->route('sales.show', $sale->id)
                ->with('success', 'Sales created successfully');
        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $sale = Sales::findOrFail($id);
        $saleDetails = SaleDetails::with('sales','products')->where('sale_id', $id)->get();
        $products = Products::all();
        return view('sales.show', compact('sale','saleDetails','products'));
    }

    public function edit($id)
    {
        $sale = Sales::findOrFail($id);
        $saleDetails = SaleDetails::with('products')->where('sale_id', $id)->get();
        foreach ($saleDetails as $key => $value) {
            $previousProducts[] = $value->product_id;
        }
        $totalAmount = SaleDetails::with('products')->where('sale_id', $id)->sum('amount');
        $products = Products::all();
        return view('sales.edit', compact('sale', 'products','saleDetails', 'previousProducts','totalAmount'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $messages = array(
            'date.required'  => 'Enter date',
            'product_id.required'  => 'Select Product',
            'quantity.required'  => 'Enter quantity',
            'payment_type.required'  => 'Select payment type',
        );

        $this->validate($request, array(
            'date' => 'required',
            'quantity' => 'required',
            'product_id' => 'required',
            'payment_type' => 'required',
        ), $messages);

        DB::beginTransaction();

        try {
            $sale = Sales::findOrFail($id);
            $sale->date = $request->date;
            $sale->order_no = $request->order_no;
            $sale->customer_name = $request->customer_name;
            $sale->customer_mobile = $request->customer_mobile;
            $sale->total_quantity = $request->total_quantity;
            $sale->grand_total = $request->grand_total;
            $sale->discount = $request->discount;
            $sale->tax_amount = $request->tax_amount;
            $sale->payment_type = $request->payment_type;
            $sale->transaction_id = $request->transaction_id;
            $sale->change_amount = $request->change_amount;
            $sale->note = $request->note;
            $sale->updated_by = Auth::user()->id;
            $sale->update();

            SaleDetails::where('sale_id', $sale->id)->delete();
            Inventory::where('sale_id', $sale->id)->delete();

            $ingredientIds = [];

            foreach($request->product_id as $key => $productId) {
                $saleDetail = new SaleDetails();
                $saleDetail->sale_id         = $sale->id;
                $saleDetail->product_id      = $productId;
                $saleDetail->quantity        = $request->quantity[$key];
                $saleDetail->amount          = $request->amount[$key];
                $saleDetail->price           = $request->price[$key];
                $saleDetail->created_by      = Auth::user()->id;
                $saleDetail->save();

                $recipe = Recipes::where('product_id', $productId)->first();
                $ingredientIds[] = json_decode($recipe->ingredient_id);
                $ingredientQuantity[] = json_decode($recipe->quantity);
            }

            foreach ($ingredientIds as $key => $ingredientId){
                foreach ($ingredientId as $value => $ingredients_id){
                    $ingredientQuantities = $ingredientQuantity[$key][$value];
                    $ingredient = Ingredients::find($ingredients_id);
                    $unit = $ingredient->units->unit_value;

                    $inventory = new Inventory();
                    $inventory->sale_id         = $sale->id;
                    $inventory->product_id      = $request->product_id[$key];
                    $inventory->ingredient_id   = $ingredients_id;
                    $inventory->date            = $request->date;
                    $inventory->stock_out       = ($request->quantity[$key]) * ($unit) *  ($ingredientQuantities);
                    $inventory->created_by      = Auth::user()->id;
                    $inventory->save();
                }
            }

            DB::commit();

            return redirect()->route('sales.show', $id)
                ->with('success', 'Sales updated successfully');
        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $sale = Sales::findOrFail($id);
            if($sale){
                SaleDetails::where('sale_id', $sale->id)->delete();
                Inventory::where('sale_id', $sale->id)->delete();
                $sale->delete();
            }
            return response()->json([
                'success' => true,
                'message' => 'Sales deleted successfully',
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Sales deleted failed',
            ]);
        }
    }

    public function Invoice($id)
    {
        $sale = Sales::findOrFail($id);
        $saleDetails = SaleDetails::with('sales','products')->where('sale_id', $id)->get();
        $products = Products::all();
        $totalAmount = SaleDetails::with('products')->where('sale_id', $id)->sum('amount');
        $setting = GeneralSetting::first();
        return view('sales.invoice', compact('sale','saleDetails','products','totalAmount','setting'));
    }
    public function KitchenInvoice($id)
    {
        $sale = Sales::findOrFail($id);
        $saleDetails = SaleDetails::with('sales','products')->where('sale_id', $id)->get();
        $products = Products::all();
        $totalAmount = SaleDetails::with('products')->where('sale_id', $id)->sum('amount');
        $setting = GeneralSetting::first();
        return view('sales.kitchen', compact('sale','saleDetails','products','totalAmount','setting'));
    }

}
