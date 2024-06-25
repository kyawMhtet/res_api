<?php

namespace App\Http\Controllers\Api\Panel;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Dish;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MenuController extends Controller
{
    //

    public function index()
    {
        try {
            $categories = Category::get();
            // $menus = Dish::with('category')->orderBy('id', 'desc')->
            return response()->json([
                'categories' => $categories
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            Log::error('categories error');
        }
    }

    // menus
    public function menus($id)
    {
        try {
            $menus = Dish::where('category_id', $id)->orderBy('id', 'desc')->get();

            return response()->json([
                'menus' => $menus
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            Log::error('menus error');
        }
    }
}
