<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Dish;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DishController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $dishes = Dish::with('category')->orderBy('id', 'desc')->paginate(6);
        $category = Category::all();

        return response()->json([
            'data' => $dishes,
            'category' => $category
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validate the request
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'price' => 'required|numeric',
                'category_id' => 'required|integer',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            // Handle the file upload
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $image_name = uniqid() . ($image->getClientOriginalName());
                $image->move(public_path('/images'), $image_name);
            }

            // Save the dish with the image name
            $dish = Dish::create([
                'category_id' => $request->category_id,
                'image' => $image_name,
                'name' => $request->name,
                'price' => $request->price,
                'description' => $request->description
            ]);

            return response()->json([
                'message' => 'success',
                'data' => $dish
            ], 201);
        } catch (\Throwable $th) {
            // Handle the exception
            return response()->json([
                'message' => 'Error saving dish',
                'error' => $th->getMessage()
            ], 500);
        }
    }


    public function search(Request $request)
    {
        // return $request->all();
        try {
            $dishes = Dish::where('name', 'LIKE', '%' . $request->key . '%')->with('category')->paginate(6);
            return response()->json([
                'data' => $dishes,
            ], 200);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
        $dish = Dish::where('id', $id)->firstOrFail();

        $dish->update($request->all());
        return response()->json([
            'message' => 'Dish updated successfully',
            'data' => $dish
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        try {
            $dish = Dish::findOrFail($id);

            $dish->delete();
        } catch (\Throwable $th) {
            //throw $th;
            Log::error('delete error');
        }
    }
}
