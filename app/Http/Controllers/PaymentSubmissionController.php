<?php
namespace App\Http\Controllers;
use App\Models\Order;
use Illuminate\Http\Request;
class PaymentSubmissionController extends Controller {
 public function store(Request $request,Order $order){
  abort_unless($order->user_id===$request->user()->id,403);
  abort_unless($order->status==='pending',422,'Pesanan sudah selesai atau dibatalkan.');
  $data=$request->validate(['reference'=>'required|string|min:4|max:100']);
  $order->payment()->updateOrCreate(['order_id'=>$order->id],['method'=>'bank_transfer','reference'=>$data['reference'],'status'=>'pending','confirmed_at'=>null]);
  return back()->with('success','Referensi transfer diterima. Menunggu pemeriksaan admin.');
 }
}
