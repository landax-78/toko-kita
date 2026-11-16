<?php
namespace App\Http\Controllers;
use App\Models\{CartItem,Product};
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
class CartController extends Controller {
 public function index(Request $request){$items=$request->user()->cartItems()->with('product')->get();return view('cart.index',compact('items'));}
 public function store(Request $request){
  $data=$request->validate(['product_id'=>'required|exists:products,id','quantity'=>'required|integer|min:1']);
  $product=Product::findOrFail($data['product_id']);$cart=CartItem::firstOrNew(['user_id'=>$request->user()->id,'product_id'=>$product->id]);
  $quantity=(int)($cart->quantity ?? 0)+(int)$data['quantity'];
  if($quantity>$product->stock)throw ValidationException::withMessages(['quantity'=>'Jumlah melebihi stok tersedia.']);
  $cart->quantity=$quantity;$cart->save();return redirect()->route('cart.index')->with('success','Produk masuk keranjang.');
 }
 public function update(Request $request,CartItem $cart){abort_unless($cart->user_id===$request->user()->id,403);
  $data=$request->validate(['quantity'=>'required|integer|min:1']);
  if($data['quantity']>$cart->product->stock)throw ValidationException::withMessages(['quantity'=>'Stok tidak mencukupi.']);
  $cart->update($data);return back()->with('success','Keranjang diperbarui.');
 }
 public function destroy(Request $request,CartItem $cart){abort_unless($cart->user_id===$request->user()->id,403);$cart->delete();return back()->with('success','Produk dihapus dari keranjang.');}
}
