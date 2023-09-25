<?php

namespace App\Http\Controllers\Products;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Ingredients\Unit;
use App\Models\Products\Products;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class ProductsController extends Controller
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

                $data = DB::table('products')
                    ->join('units', 'products.unit_id', '=', 'units.id')
                    ->select('products.*', 'units.unit_name')
                    ->whereNull('products.deleted_at')
                    ->orderByDesc('products.id')
                    ->get();

                return Datatables::of($data)

                    ->addColumn('action', function ($data) {

                        $button = '';

                        $show = '<li><a class="dropdown-item" href="' . route('product.show', $data->id) . ' " ><i class="ik ik-eye f-16 text-blue"></i> Details</a></li>';

                        $edit = '<li><a class="dropdown-item" id="edit" href="' . route('product.edit', $data->id) . ' " title="Edit"><i class="ik ik-edit f-16 text-green"></i> Edit</a></li>';

                        $delete = '<li><a class="dropdown-item" id="delete" href="' . route('product.destroy', $data->id) . ' " title="Delete"><i class="ik ik-trash-2 f-16 text-red"></i> Delete</a></li>';


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
                    ->rawColumns(['action'])
                    ->toJson();
            }
            return view('products.index');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function create()
    {
        $units = Unit::all();
        return view('products.create', compact('units'));
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
            'name.required'  => 'Enter name',
            'price.required'  => 'Enter price',
            'unit_id.required'  => 'Select unit',
        );

        $this->validate($request, array(
            'price' => 'required|',
            'image.*' => 'required|max:2048|mimes:jpeg,png,jpg',
        ), $messages);



        try {
            $data = new Products();

            if ($request->file('image')) {
                $file = $request->file('image');
                $filename = time() . $file->getClientOriginalName();
                $file->move(public_path('/img/product/'), $filename);
                $data->image = $filename;
            }

            $data->name = $request->name;
            $data->unit_id = $request->unit_id;
            $data->price = $request->price;
            $data->description = $request->description;
            $data->created_by = Auth::user()->id;
            $data->save();

            return redirect()->route('product.index')
                ->with('success', 'Products created successfully');
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
        $data = Products::with('units')->findOrFail($id);
        return view('products.show', compact('data'));
    }
    public function edit($id)
    {
        $data = Products::findOrFail($id);
        $units = Unit::all();
        return view('products.edit', compact('data','units'));
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
            'name.required'  => 'Enter name',
            'price.required'  => 'Enter name',
            'unit_id.required'  => 'Select name',
        );

        $this->validate($request, array(
            'price' => 'required|',
            'image.*' => 'required|max:2048|mimes:jpeg,png,jpg',
        ), $messages);

        try {

            $data = Products::findOrFail($id);

            if ($request->file('image')) {
                $file = $request->file('image');
                $filename = time() . $file->getClientOriginalName();
                $file->move(public_path('/img/product/'), $filename);
                $data->image = $filename;
            }

            $data->name = $request->name;
            $data->unit_id = $request->unit_id;
            $data->price = $request->price;
            $data->description = $request->description;
            $data->updated_by = Auth::user()->id;
            $data->update();

            return redirect()->route('product.index')
                ->with('success', 'Products updated successfully');
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
            $data = Products::findOrFail($id);
            $data->delete();
            return response()->json([
                'success' => true,
                'message' => 'Products deleted successfully',
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Products deleted failed',
            ]);
        }
    }
}
