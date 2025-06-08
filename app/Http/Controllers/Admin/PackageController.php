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
        $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'image' => 'required|image|max:2048', // 2MB max
        ]);

        // Store uploaded image
        $imagePath = $request->file('image')->store('packages', 'public');

        $package = Package::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'images' => ["/storage/$imagePath"], // store as JSON array
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
