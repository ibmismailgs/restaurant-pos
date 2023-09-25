<?php

namespace App\Http\Controllers\Ingredients;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Ingredients\Unit;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class UnitController extends Controller
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
                    $data = DB::table('units')
                        ->whereNull('deleted_at')
                        ->get();

                return Datatables::of($data)

                    ->addColumn('action', function ($data) {
                        $button = '';

                        $show = '<li><a class="dropdown-item" href="' . route('units.show', $data->id) . ' " ><i class="ik ik-eye f-16 text-blue"></i> Details</a></li>';

                        $edit = '<li><a class="dropdown-item" id="edit" href="' . route('units.edit', $data->id) . ' " title="Edit"><i class="ik ik-edit f-16 text-green"></i> Edit</a></li>';

                        $delete = '<li><a class="dropdown-item" id="delete" href="' . route('units.destroy', $data->id) . ' " title="Delete"><i class="ik ik-trash-2 f-16 text-red"></i> Delete</a></li>';


                        if(Auth::user()->can('manage_user')){
                            $button = $show. $edit . $delete;
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
            return view('units.index');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function create()
    {
        return view('units.create');
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
            'unit_name.required'  => 'Enter unit name',
            'unit.required'  => 'Enter unit value',
        );

        $this->validate($request, array(
            'unit_name' => 'required|unique:units,unit_name,NULL,id,deleted_at,NULL',
        ), $messages);

        try {
            $data = new Unit();
            $data->unit_name = $request->unit_name;
            $data->unit_value = $request->unit_value;
            $data->description = $request->description;
            $data->created_by = Auth::user()->id;
            $data->save();

            return redirect()->route('units.index')
                ->with('success', 'Units created successfully');
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
        $data = Unit::findOrFail($id);
        return view('units.show', compact('data'));
    }

    public function edit($id)
    {
        $data = Unit::findOrFail($id);
        return view('units.edit', compact('data'));
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
            'unit_name.required'  => 'Enter unit name',
            'unit_value.required'  => 'Enter unit value',
        );

        $this->validate($request, array(
            'unit_name' => 'required|unique:units,unit_name,' . $id . ',id,deleted_at,NULL',
        ), $messages);

        try {
            $data = Unit::findOrFail($id);
            $data->unit_name = $request->unit_name;
            $data->unit_value = $request->unit_value;
            $data->description = $request->description;
            $data->updated_by = Auth::user()->id;
            $data->update();

            return redirect()->route('units.index')
                ->with('success', 'Units updated successfully');
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
            $data = Unit::findOrFail($id);
            $data->delete();
            return response()->json([
                'success' => true,
                'message' => 'Unit deleted successfully',
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Unit deleted failed',
            ]);
        }
    }
}
