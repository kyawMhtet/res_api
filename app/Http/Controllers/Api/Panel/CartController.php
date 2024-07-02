<?php

namespace App\Http\Controllers\Api\Panel;

use App\Models\Cart;
use App\Models\Dish;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{

    public function index()
    {
        try {

            $user = Auth::user();
            $cartMenus = Cart::where('user_id', $user->id)->with('dish')->get();

            Log::info('Cart Menus:', ['cartMenus' => $cartMenus]);

            return response()->json([
                'cartMenus' => $cartMenus
            ], 200);
        } catch (\Throwable $th) {
            // throw $th;
            Log::error("error cart");
        }
    }

    //
    public function addToCart(Request $request, $id)
    {
        try {
            $menu = Dish::findOrFail($id);
            $user = Auth::user();
            $cartItem = Cart::where('menu_id', $id)->where('user_id', $user->id)->first();

            if ($cartItem) {
                // If item exists, update quantity
                $cartItem->quantity += $request->input('quantity');
                $cartItem->save();
            } else if ($user && $user->role === 'waiter') {
                $cart = Cart::create([
                    'menu_id' => $menu->id,
                    'user_id' => $user->id,
                    'quantity' => $request->quantity
                ]);

                return response()->json([
                    'cart' => $cart
                ], 200);
            } else {
                return response()->json(['error' => 'Unauthorized'], 403);
            }
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }


    // add qty
    public function addQty(Request $request)
    {
        try {
            // $cartItem = Cart::findOrFail($request->id);
            $user = Auth::user();

            $cartItem = Cart::where('id', $request->id)->where('user_id', $user->id)->first();

            $cartItem->update([
                'quantity' => DB::raw('quantity + 1'),
            ]);

            return response()->json([
                'message' => 'Qty added'
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            Log::error('addQty error');
        }
    }


    public function subQty(Request $request)
    {
        try {
            // $cartItem = Cart::findOrFail($request->id);
            $user = Auth::user();

            $cartItem = Cart::where('id', $request->id)->where('user_id', $user->id)->first();

            if ($cartItem->quantity > 1) {
                $cartItem->update([
                    'quantity' => DB::raw('quantity - 1'),
                ]);
            }

            return response()->json([
                'message' => 'Qty substract'
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            Log::error('subQty error');
        }
    }


    // checkout
    public function checkout(Request $request)
    {
        try {
            $table_number = $request->table_id;
            $user = Auth::user();

            // Check if there are any existing orders for the table
            $existingOrder = Order::where('table_id', $table_number)->latest()->first();
            $order_id = $existingOrder ? $existingOrder->order_id : $this->generateOrderId();

            $cartItems = Cart::where('user_id', $user->id)->get();


            foreach ($cartItems as $cartItem) {
                $order = Order::where('table_id', $table_number)
                    ->where('dish_id', $cartItem->menu_id)
                    ->first();

                if ($order) {
                    $order->quantity += $cartItem->quantity;
                    $order->save();
                } else {
                    $order = Order::create([
                        'dish_id' => $cartItem->menu_id,
                        'table_id' => $table_number,
                        'user_id' => $user->id,
                        'quantity' => $cartItem->quantity,
                        'status' => 'new_order',
                        'sub_total_price' => $request->sub_total_price,
                        'order_id' => $order_id
                    ]);
                }
            }

            Cart::where('user_id', $user->id)->delete();

            return response()->json([
                'message' => 'Order Placed',
                'order_id' => $order_id,
                'order' => $order
            ], 200);
        } catch (\Throwable $th) {
            Log::error("Checkout error: " . $th->getMessage());
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    private function generateOrderId()
    {
        do {
            $order_id = str_pad(mt_rand(0, 99999), 5, '0', STR_PAD_LEFT);
        } while (Order::where('order_id', $order_id)->exists());

        return $order_id;
    }
}
