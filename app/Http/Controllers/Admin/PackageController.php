<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Package;

class PackageController extends Controller
{
    public function index()
    {
        return response()->json(Package::all());
    }

    public function store(Request $request)
    {
        $package = Package::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'images' => $request->images, // handled by cast
        ]);

        return response()->json($package);
    }

    public function update(Request $request, Package $package)
    {
        $package->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'images' => $request->images,
        ]);

        return response()->json($package);
    }

    public function destroy(Package $package)
    {
        $package->delete();

        return response()->json(['message' => 'Package deleted successfully']);
    }
}
