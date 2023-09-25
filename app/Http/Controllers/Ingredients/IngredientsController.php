<?php

namespace App\Http\Controllers\Ingredients;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Ingredients\Unit;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Ingredients\Ingredients;
use Yajra\DataTables\Facades\DataTables;

class IngredientsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                $data = DB::table('ingredients')
                    ->join('units', 'ingredients.unit_id', '=', 'units.id')
                    ->select('ingredients.*', 'units.unit_name')
                    ->whereNull('ingredients.deleted_at')
                    ->orderByDesc('ingredients.id')
                    ->get();

                return Datatables::of($data)

                    ->addColumn('description', function ($data) {
                        $result = isset($data->description) ? strip_tags($data->description) : '--' ;
                        return Str::limit( $result, 60) ;
                    })

                    ->addColumn('action', function ($data) {

                        $button = '';

                        $show = '<li><a class="dropdown-item" href="' . route('ingredients.show', $data->id) . ' " ><i class="ik ik-eye f-16 text-blue"></i> Details</a></li>';

                        $edit = '<li><a class="dropdown-item" id="edit" href="' . route('ingredients.edit', $data->id) . ' " title="Edit"><i class="ik ik-edit f-16 text-green"></i> Edit</a></li>';

                        $delete = '<li><a class="dropdown-item" id="delete" href="' . route('ingredients.destroy', $data->id) . ' " title="Delete"><i class="ik ik-trash-2 f-16 text-red"></i> Delete</a></li>';


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
                    ->rawColumns(['description', 'action'])
                    ->toJson();
            }
            return view('ingredients.index');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function create()
    {
        $units = Unit::all();
        return view('ingredients.create', compact('units'));
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
            'unit_id.required'  => 'Select unit',
        );

        $this->validate($request, array(
            'name' => 'required|unique:ingredients,name,NULL,id,deleted_at,NULL',
        ), $messages);

        try {
            $data = new Ingredients();
            $data->name = $request->name;
            $data->unit_id = $request->unit_id;
            $data->description = $request->description;
            $data->created_by = Auth::user()->id;
            $data->save();

            return redirect()->route('ingredients.index')
                ->with('success', 'Ingredients created successfully');
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
        $data = Ingredients::with('units')->findOrFail($id);
        return view('ingredients.show', compact('data'));
    }
    public function edit($id)
    {
        $data = Ingredients::findOrFail($id);
        $units = Unit::all();
        return view('ingredients.edit', compact('data','units'));
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
            'unit_id.required'  => 'Select unit',
        );

        $this->validate($request, array(
            'name' => 'required|unique:ingredients,name,' . $id . ',id,deleted_at,NULL',
        ), $messages);

        try {
            $data = Ingredients::findOrFail($id);
            $data->name = $request->name;
            $data->unit_id = $request->unit_id;
            $data->description = $request->description;
            $data->updated_by = Auth::user()->id;
            $data->update();

            return redirect()->route('ingredients.index')
                ->with('success', 'Ingredients updated successfully');
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
            $data = Ingredients::findOrFail($id);
            $data->delete();
            return response()->json([
                'success' => true,
                'message' => 'Ingredients deleted successfully',
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Ingredients deleted failed',
            ]);
        }
    }
}
