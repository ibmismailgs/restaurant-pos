<?php

namespace App\Http\Controllers\Products;

use Illuminate\Http\Request;
use App\Models\Products\Recipes;
use App\Models\Products\Products;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Ingredients\Ingredients;
use Yajra\DataTables\Facades\DataTables;

class RecipesController extends Controller
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

                    $data = DB::table('recipes')
                        ->Join('products', 'products.id', '=', 'recipes.product_id')
                        ->select('recipes.*', 'products.name as productName')
                        ->whereNull('recipes.deleted_at')
                        ->get();

                return Datatables::of($data)

                    ->addColumn('ingredientName', function ($data) {

                        $ingredients = Ingredients::all();
                        $ingredientId = json_decode($data->ingredient_id);
                        $quantityId = json_decode($data->quantity);
                        $ingredientName = [];

                        foreach ($ingredientId as $key => $value){
                            foreach ($ingredients as $ingredient){
                                if($ingredient->id == $value){
                                    $ingredientName[] = $ingredient->name . ' => ' . $quantityId[$key].',';
                                    $result = implode("</br>", $ingredientName);
                                }
                            }
                        }
                        return $result;
                    })


                    ->addColumn('action', function ($data) {

                        $button = '';

                        $show = '<li><a class="dropdown-item" href="' . route('recipes.show', $data->id) . ' " ><i class="ik ik-eye f-16 text-blue"></i> Details</a></li>';

                        $edit = '<li><a class="dropdown-item" id="edit" href="' . route('recipes.edit', $data->id) . ' " title="Edit"><i class="ik ik-edit f-16 text-green"></i> Edit</a></li>';

                        $delete = '<li><a class="dropdown-item" id="delete" href="' . route('recipes.destroy', $data->id) . ' " title="Delete"><i class="ik ik-trash-2 f-16 text-red"></i> Delete</a></li>';


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
                    ->rawColumns(['ingredientName','action'])
                    ->toJson();
            }
            return view('recipes.index');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function Ingredient(Request $request)
    {
        $ingredient = Ingredients::with('units')->where('id', $request->ingredient_id)->first();
        return response()->json($ingredient);
    }

    public function create()
    {
        $ingredients = Ingredients::all();
        $products = Products::all();
        return view('recipes.create', compact('ingredients', 'products'));
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
            'product_id.required'  => 'Select product',
            'ingredient_id.required'  => 'Select ingredient',
            'quantity.required'  => 'Enter quantity',
        );

        $this->validate($request, array(
            'ingredient_id' => 'required|',
            'product_id' => 'required|',
            'quantity' => 'required|',
        ), $messages);

        try {
            $data = new Recipes();
            $data->date = $request->date;
            $data->product_id = $request->product_id;
            $data->ingredient_id = json_encode($request->ingredient_id);
            $data->quantity = json_encode($request->quantity);
            $data->description = $request->description;
            $data->created_by = Auth::user()->id;
            $data->save();

            return redirect()->route('recipes.index')
                ->with('success', 'Recipes created successfully');
        } catch (\Exception $exception) {
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
        $data = Recipes::findOrFail($id);
        $ingredients = Ingredients::all();
        return view('recipes.show', compact('data','ingredients'));
    }

    public function edit($id)
    {
        $data = Recipes::with('ingredients')->findOrFail($id);
        $ingredients = Ingredients::all();
        $products = Products::all();
        return view('recipes.edit', compact('data', 'ingredients', 'products'));
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
            'product_id.required'  => 'Select product',
            'ingredient_id.required'  => 'Select ingredient',
            'quantity.required'  => 'Enter quantity',
        );

        $this->validate($request, array(
            'ingredient_id' => 'required|',
            'product_id' => 'required|',
            'quantity' => 'required|',
        ), $messages);

        try {
            $data = Recipes::findOrFail($id);
            $data->date = $request->date;
            $data->product_id = $request->product_id;
            $data->ingredient_id = json_encode($request->ingredient_id);
            $data->quantity = json_encode($request->quantity);
            $data->description = $request->description;
            $data->updated_by = Auth::user()->id;
            $data->update();

            return redirect()->route('recipes.index')
                ->with('success', 'Recipes updated successfully');
        } catch (\Exception $exception) {
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
            $data = Recipes::findOrFail($id);
            $data->delete();
            return response()->json([
                'success' => true,
                'message' => 'Recipes deleted successfully',
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Recipes deleted failed',
            ]);
        }
    }
}
