<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CurrencyController extends Controller
{
    //
    public function index(Request $request)
    {
        $query = Currency::query();

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('code', 'LIKE', "%{$search}%");
            });
        }

        $pageSize = $request->get('page_size', 10);

        $currencies = $query->paginate($pageSize);
        return view('pages.currencies.index', compact('currencies'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'code' => 'required',
            'exchange_rate' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $currency = new Currency();
        $currency->name = $request->name;
        $currency->code = $request->code;
        $currency->exchange_rate = $request->exchange_rate;
        $currency->save();

        return redirect()->back()->with('success', 'Currency created successfully');
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'code' => 'required',
            'exchange_rate' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $currency = Currency::find($id);
        $currency->name = $request->name;
        $currency->code = $request->code;
        $currency->exchange_rate = $request->exchange_rate;
        $currency->save();

        return redirect()->back()->with('success', 'Currency updated successfully');
    }

    public function destroy($id)
    {
        $currency = Currency::find($id);
        $currency->delete();

        return redirect()->back()->with('success', 'Currency deleted successfully');
    }
}
