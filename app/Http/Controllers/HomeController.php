<?php

namespace App\Http\Controllers;

use App\Models\Sales\Sales;
use Illuminate\Http\Request;
use App\Models\Products\Products;
use App\Models\Ingredients\Purchase;
use Illuminate\Support\Facades\Artisan;
use App\Models\Ingredients\GeneralSetting;

class HomeController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function dashboard(){
        $date = now()->format('Y-m-d');
        $products = Products::where('created_at', '=', $date)->count();
        $purchaseAmount = Purchase::where('date', '=', $date)->sum('total_amount');
        $orders = Sales::where('date', '=', $date)->count();
        $saleAmount = Sales::where('date', '=', $date)->sum('grand_total');
        return view('pages.dashboard',compact('products','orders','purchaseAmount','saleAmount'));
    }

    public function clearCache()
    {
        Artisan::call('cache:clear');

        return view('clear-cache');
    }
    public function GeneralSettings(){
        try {
            $data = GeneralSetting::first();
            return view('general_setting.setting', compact('data'));
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function GeneralSettingStore(Request $request)
    {
        try {
            if (!$request->id) {
                $request->validate([
                    'name' => 'required',
                    'website' => 'required',
                    'email' => 'required',
                    'phone' => 'required',
                    'address' => 'required',
                    'favicon' => 'required',
                    'logo' => 'required',
                ]);

                $data = new GeneralSetting();
            } else {
                $data = GeneralSetting::findOrFail($request->id);
            }
            if ($request->file('logo')) {
                $file = $request->file('logo');
                $filename = time() . $file->getClientOriginalName();
                $file->move(public_path('/img/'), $filename);
                $data->logo = $filename;
            }
            if ($request->file('favicon')) {
                $file = $request->file('favicon');
                $filenamefavicon = time() . $file->getClientOriginalName();
                $file->move(public_path('/img/'),  $filenamefavicon);
                $data->favicon =  $filenamefavicon;
            }
            $data->name = $request->name;
            $data->website = $request->website;
            $data->email = $request->email;
            $data->phone = $request->phone;
            $data->address = $request->address;
            $data->map = $request->map;
            $data->description = $request->description;

            if (!$request->id) {
                $data->save();
                return redirect()->route('general-settings')->with('success', ' General settings created successfull');
            } else {
                $data->update();
                return redirect()->route('general-settings')->with('success', 'General settings updated successfull');
            }

        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }
}
