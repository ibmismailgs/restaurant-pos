<?php

namespace App\Http\Controllers\Ingredients;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Ingredients\Purchase;
use Illuminate\Support\Facades\Auth;
use App\Models\Ingredients\Ingredients;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Ingredients\PurchaseDetails;
use App\Models\Inventory\Inventory;

class PurchaseController extends Controller
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
                    $data = DB::table('purchases')
                        ->whereNull('deleted_at')
                        ->get();

                return Datatables::of($data)

                ->addColumn('date', function ($data) {
                    $date = Carbon::parse($data->date)->format('d F, Y');
                    return $date;
                })

                    ->addColumn('action', function ($data) {

                        $button = '';

                        $show = '<li><a class="dropdown-item" href="' . route('purchase.show', $data->id) . ' " ><i class="ik ik-eye f-16 text-blue"></i> Details</a></li>';

                        $edit = '<li><a class="dropdown-item" id="edit" href="' . route('purchase.edit', $data->id) . ' " title="Edit"><i class="ik ik-edit f-16 text-green"></i> Edit</a></li>';

                        $delete = '<li><a class="dropdown-item" id="delete" href="' . route('purchase.destroy', $data->id) . ' " title="Delete"><i class="ik ik-trash-2 f-16 text-red"></i> Delete</a></li>';


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
            return view('purchase.index');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function create()
    {
        $ingredients = Ingredients::all();
        return view('purchase.create', compact('ingredients'));
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
            'ref_no	.required'  => 'Enter refrence no',
            'ingredient_id.required'  => 'Select ingredient',
            'quantity.required'  => 'Enter quantity',
            'unit_price.required'  => 'Enter unit price',
            'amount.required'  => 'Enter amount',
        );

        $this->validate($request, array(
            'date' => 'required',
            'ref_no' => 'required',
            'quantity' => 'required',
            'unit_price' => 'required',
            'amount' => 'required',
        ), $messages);

        DB::beginTransaction();

        try {
            $purchase = new Purchase();
            $purchase->date = $request->date;
            $purchase->ref_no = $request->ref_no;
            $purchase->total_quantity = $request->total_quantity;
            $purchase->total_amount = $request->total_amount;
            $purchase->description = $request->description;
            $purchase->created_by = Auth::user()->id;
            $purchase->save();

            foreach($request->ingredient_id as $key => $ingredientId) {
                $purchaseDetail = new PurchaseDetails();
                $purchaseDetail->purchase_id     = $purchase->id;
                $purchaseDetail->ingredient_id   = $ingredientId;
                $purchaseDetail->quantity        = $request->quantity[$key];
                $purchaseDetail->amount          = $request->amount[$key];
                $purchaseDetail->unit_price      = $request->unit_price[$key];
                $purchaseDetail->created_by      = Auth::user()->id;
                $purchaseDetail->save();

                $inventory = new Inventory();
                $inventory->purchase_id     = $purchase->id;
                $inventory->ingredient_id   = $ingredientId;
                $inventory->date            = $request->date;
                $inventory->stock_in        = ($request->quantity[$key]) * ($request->unit_value[$key]);
                $inventory->created_by      = Auth::user()->id;
                $inventory->save();
            }

            DB::commit();

            return redirect()->route('purchase.index')
                ->with('success', 'Purchase created successfully');
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
        $purchase = Purchase::findOrFail($id);
        $purchaseDetails = PurchaseDetails::with('ingredients')->where('purchase_id', $id)->get();
        return view('purchase.show', compact('purchase','purchaseDetails'));
    }

    public function edit($id)
    {
        $purchase = Purchase::findOrFail($id);
        $purchaseDetails = PurchaseDetails::with('ingredients')->where('purchase_id', $id)->get();
        foreach ($purchaseDetails as $key => $value) {
            $previousIngredients[] = $value->ingredient_id;
        }

        $ingredients = Ingredients::all();
        return view('purchase.edit', compact('purchase', 'ingredients','purchaseDetails', 'previousIngredients'));
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
            'ref_no	.required'  => 'Enter refrence no',
            'ingredient_id.required'  => 'Select ingredient',
            'quantity.required'  => 'Enter quantity',
            'unit_price.required'  => 'Enter unit price',
            'amount.required'  => 'Enter amount',
        );

        $this->validate($request, array(
            'date' => 'required',
            'ref_no' => 'required',
            'quantity' => 'required',
            'unit_price' => 'required',
            'amount' => 'required',
        ), $messages);

        DB::beginTransaction();

        try {
            $purchase = Purchase::findOrFail($id);
            $purchase->date = $request->date;
            $purchase->ref_no = $request->ref_no;
            $purchase->total_quantity = $request->total_quantity;
            $purchase->total_amount = $request->total_amount;
            $purchase->description = $request->description;
            $purchase->updated_by = Auth::user()->id;
            $purchase->update();

            PurchaseDetails::where('purchase_id', $purchase->id)->delete();
            Inventory::where('purchase_id', $purchase->id)->delete();

            foreach($request->ingredient_id as $key => $ingredientId) {
                $purchaseDetail = new PurchaseDetails();
                $purchaseDetail->purchase_id     = $purchase->id;
                $purchaseDetail->ingredient_id   = $ingredientId;
                $purchaseDetail->quantity        = $request->quantity[$key];
                $purchaseDetail->amount          = $request->amount[$key];
                $purchaseDetail->unit_price      = $request->unit_price[$key];
                $purchaseDetail->created_by      = Auth::user()->id;
                $purchaseDetail->save();

                $inventory = new Inventory();
                $inventory->purchase_id     = $purchase->id;
                $inventory->ingredient_id   = $ingredientId;
                $inventory->date            = $request->date;
                $inventory->stock_in        = ($request->quantity[$key]) * ($request->unit_value[$key]);
                $inventory->created_by      = Auth::user()->id;
                $inventory->save();
            }

            DB::commit();

            return redirect()->route('purchase.index')
                ->with('success', 'Purchase updated successfully');
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
            $data = Purchase::findOrFail($id);
            if($data){
                PurchaseDetails::where('purchase_id', $data->id)->delete();
                Inventory::where('purchase_id', $data->id)->delete();
                $data->delete();
            }
            return response()->json([
                'success' => true,
                'message' => 'Purchase deleted successfully',
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Purchase deleted failed',
            ]);
        }
    }
}
