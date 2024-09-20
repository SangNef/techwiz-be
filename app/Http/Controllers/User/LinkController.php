<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Link;
use App\Models\Trip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;


class LinkController extends Controller
{
    public function index($trip_id)
    {
        $trip = Trip::find($trip_id);

        if (!$trip) {
            return response()->json(['message' => 'Trip not found'], 404);
        }

        $links = Link::where('trip_id', $trip_id)->get();

        return response()->json($links, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'trip_id' => 'required|exists:trips,id',
        ]);

        $randomLink = $this->generateRandomLink();

        $link = new Link();
        $link->trip_id = $request->trip_id;
        $link->url = $randomLink; 
        $link->save();

        return response()->json([
            'message' => 'Link created successfully',
            'link' => $link
        ], 201);
    }


    public function show($id)
    {
        $link = Link::find($id);

        if (!$link) {
            return response()->json(['message' => 'Link not found'], 404);
        }

        return response()->json($link, 200);
    }

    public function update($id)
    {
        $link = Link::find($id);

        if (!$link) {
            return response()->json(['message' => 'Link not found'], 404);
        }

        $link->url = $this->generateRandomLink(); 
        $link->save();

        return response()->json(['message' => 'Link updated successfully', 'link' => $link], 200);
    }


    public function destroy($id)
    {
        $link = Link::find($id);

        if (!$link) {
            return response()->json(['message' => 'Link not found'], 404);
        }

        $link->delete();

        return response()->json(['message' => 'Link deleted successfully'], 200);
    }
    private function generateRandomLink()
    {
        $randomString = Str::random(10); 
        return $randomString;
    }
}
