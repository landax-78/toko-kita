<?php
namespace App\Http\Controllers;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class OrderController extends Controller {
 public function index(Request $request){$orders=$request->user()->orders()->latest()->paginate(10);return view('orders.index',compact('orders'));}
 public function show(Request $request,Order $order){abort_unless($order->user_id===$request->user()->id,403);$order->load(['items','payment']);return view('orders.show',compact('order'));}
 public function cancel(Request $request,Order $order){abort_unless($order->user_id===$request->user()->id,403);
  DB::transaction(function()use($order){
   $order=Order::whereKey($order->id)->lockForUpdate()->firstOrFail();
   abort_unless($order->status==='pending' && !$order->stock_returned,422,'Pesanan tidak dapat dibatalkan.');
   foreach($order->items as $item){$item->product()->increment('stock',$item->quantity);}
   $order->update(['status'=>'cancelled','stock_returned'=>true]);
  });return back()->with('success','Pesanan dibatalkan dan stok dikembalikan.');
 }
}
