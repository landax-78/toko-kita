<?php
namespace App\Http\Controllers\Admin;
use App\Models\{Order,User};
use Illuminate\Support\Facades\DB;
class OrderController extends BaseResourceController {
 protected string $model=Order::class; protected string $resource='orders'; protected string $title='Pesanan';
 protected array $fields=['user_id'=>['label'=>'Pelanggan','type'=>'select','options_model'=>User::class],'shipping_name'=>['label'=>'Nama penerima','type'=>'text'],'shipping_address'=>['label'=>'Alamat pengiriman','type'=>'textarea']];
 protected function rules(?int $id=null):array{return ['user_id'=>'required|exists:users,id','shipping_name'=>'required|string|max:100','shipping_address'=>'required|string|min:10|max:500'];}
 public function destroy(int $id){
  DB::transaction(function()use($id){
   $order=Order::whereKey($id)->lockForUpdate()->firstOrFail();
   if(!$order->stock_returned){foreach($order->items as $item){$item->product()->increment('stock',$item->quantity);}}
   $order->delete();
  });return back()->with('success','Pesanan dihapus dan stok telah disesuaikan.');
 }
}
