<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use App\Models\DestinationImage;
use Illuminate\Http\Request;
use Validator;

class DestinationController extends Controller
{
    //
    public function index()
    {
        $destinations = Destination::with('images')->get();
        return view('pages.destinations.index', compact('destinations'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'nullable|string',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $destination = new Destination();
        $destination->name = $request->name;
        $destination->description = $request->description;
        $destination->save();

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $name = time() . '-' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('images/destinations'), $name);

                $destinationImage = new DestinationImage();
                $destinationImage->destination_id = $destination->id;
                $destinationImage->image = $name;
                $destinationImage->save();
            }
        }

        return redirect()->back()->with('success', 'Destination created successfully');
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $destination = Destination::find($id);
        $destination->name = $request->name;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name = time() . '.' . $image->getClientOriginalExtension();
            $destination->image = $name;
            $destination->save();
            $image->move(public_path('images/destinations'), $name);
        }

        $destination->save();

        return redirect()->back()->with('success', 'Destination updated successfully');
    }

    public function destroy($id)
    {
        $destination = Destination::find($id);
        $destination->delete();
        return redirect()->back()->with('success', 'Destination deleted successfully');
    }
}
