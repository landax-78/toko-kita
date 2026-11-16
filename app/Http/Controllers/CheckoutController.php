<?php
namespace App\Http\Controllers;
use App\Models\{Order,Product};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
class CheckoutController extends Controller {
 public function create(Request $request){$items=$request->user()->cartItems()->with('product')->get();if($items->isEmpty())return redirect()->route('cart.index')->with('error','Keranjang masih kosong.');return view('checkout.create',compact('items'));}
 public function store(Request $request){
  $data=$request->validate(['shipping_name'=>'required|string|max:100','shipping_address'=>'required|string|min:10|max:500']);
  $order=DB::transaction(function()use($request,$data){
    $items=$request->user()->cartItems()->lockForUpdate()->get();
    if($items->isEmpty())throw ValidationException::withMessages(['cart'=>'Keranjang kosong.']);
    $order=Order::create(['user_id'=>$request->user()->id,'shipping_name'=>$data['shipping_name'],'shipping_address'=>$data['shipping_address'],'status'=>'pending','total'=>0]);
    $total=0;
    foreach($items as $item){
      $product=Product::whereKey($item->product_id)->lockForUpdate()->firstOrFail();
      if($item->quantity>$product->stock)throw ValidationException::withMessages(['cart'=>"Stok {$product->name} tidak mencukupi."]);
      $product->decrement('stock',$item->quantity);
      $subtotal=round((float)$product->price*$item->quantity,2);
      $order->items()->create(['product_id'=>$product->id,'product_name'=>$product->name,'quantity'=>$item->quantity,'unit_price'=>$product->price,'subtotal'=>$subtotal]);
      $total+=$subtotal;
    }
    $order->update(['total'=>$total]);
    $order->payment()->create(['method'=>'bank_transfer','status'=>'pending']);
    $request->user()->cartItems()->delete();return $order;
  });
  return redirect()->route('orders.show',$order)->with('success','Checkout berhasil. Silakan masukkan referensi transfer (simulasi).');
 }
}
