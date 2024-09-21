<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Config;
use Illuminate\Http\Request;

class ConfigController extends Controller
{
    public function index()
    {
        $configs = Config::all();

        foreach ($configs as $config) {
            $decodedValue = json_decode($config->value, true);
            if (is_array($decodedValue)) {
                $config->value = $decodedValue;
            }
        }

        return view('pages.config.index', compact('configs'));
    }
    public function update(Request $request, $id)
    {
        $config = Config::findOrFail($id);
        $data = $request->all();

        $configData = json_decode($config->value, true);

        if ($request->has('title')) {
            $configData['title'] = $request->input('title');
        }

        if ($request->has('content')) {
            $configData['content'] = $request->input('content');
        }
        if ($request->has('description')) {
            $configData['description'] = $request->input('description'); 
        } 

        if ($request->hasFile('banner')) {
            foreach ($request->file('banner') as $index => $bannerFile) {
                if ($bannerFile->isValid()) {
                    $filename = time() . '_' . $bannerFile->getClientOriginalName();
                    $bannerFile->move(public_path('images/banners'), $filename);
                    $configData['banner'][$index] = env('APP_URL') . '/images/banners/' . $filename;
                }
            }
        }

        $config->value = json_encode($configData);
        $config->save();

        return redirect()->route('config')->with('success', 'Cập nhật cấu hình thành công!');
    }
}
