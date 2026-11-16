<?php
namespace App\Http\Controllers\Admin;
use App\Models\{OrderItem,Order,Product};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
class OrderItemController extends BaseResourceController {
 protected string $model=OrderItem::class; protected string $resource='order-items'; protected string $title='Detail Pesanan';
 protected array $fields=['order_id'=>['label'=>'Pesanan','type'=>'select','options_model'=>Order::class,'display'=>'code'],'product_id'=>['label'=>'Produk','type'=>'select','options_model'=>Product::class],'quantity'=>['label'=>'Jumlah','type'=>'number','step'=>'1']];
 protected function rules(?int $id=null):array{return ['order_id'=>'required|exists:orders,id','product_id'=>'required|exists:products,id','quantity'=>'required|integer|min:1'];}
 private function available(Order $order):void { if($order->status!=='pending'||$order->stock_returned)throw ValidationException::withMessages(['order_id'=>'Hanya pesanan pending yang boleh diubah.']); }
 private function reduce(Product $product,int $quantity):void {if($product->stock<$quantity)throw ValidationException::withMessages(['quantity'=>'Stok produk tidak mencukupi.']);$product->decrement('stock',$quantity);}
 public function store(Request $request){$data=$request->validate($this->rules());DB::transaction(function()use($data){
  $order=Order::whereKey($data['order_id'])->lockForUpdate()->firstOrFail();$this->available($order);
  $product=Product::whereKey($data['product_id'])->lockForUpdate()->firstOrFail();$this->reduce($product,$data['quantity']);
  $order->items()->create(['product_id'=>$product->id,'product_name'=>$product->name,'quantity'=>$data['quantity'],'unit_price'=>$product->price,'subtotal'=>round((float)$product->price*$data['quantity'],2)]);$order->refreshTotal();
 });return redirect()->route('admin.order-items.index')->with('success','Detail pesanan berhasil ditambahkan.');}
 public function update(Request $request,int $id){$data=$request->validate($this->rules($id));DB::transaction(function()use($data,$id){
  $item=OrderItem::whereKey($id)->lockForUpdate()->firstOrFail();$oldOrder=Order::whereKey($item->order_id)->lockForUpdate()->firstOrFail();$this->available($oldOrder);
  $newOrder=Order::whereKey($data['order_id'])->lockForUpdate()->firstOrFail();$this->available($newOrder);
  $oldProduct=Product::whereKey($item->product_id)->lockForUpdate()->firstOrFail();$oldProduct->increment('stock',$item->quantity);
  $newProduct=Product::whereKey($data['product_id'])->lockForUpdate()->firstOrFail();$this->reduce($newProduct,$data['quantity']);
  $item->update(['order_id'=>$newOrder->id,'product_id'=>$newProduct->id,'product_name'=>$newProduct->name,'quantity'=>$data['quantity'],'unit_price'=>$newProduct->price,'subtotal'=>round((float)$newProduct->price*$data['quantity'],2)]);
  $oldOrder->refreshTotal();if($oldOrder->id!==$newOrder->id)$newOrder->refreshTotal();
 });return redirect()->route('admin.order-items.index')->with('success','Detail pesanan diperbarui.');}
 public function destroy(int $id){DB::transaction(function()use($id){$item=OrderItem::whereKey($id)->lockForUpdate()->firstOrFail();$order=Order::whereKey($item->order_id)->lockForUpdate()->firstOrFail();$this->available($order);$item->product()->increment('stock',$item->quantity);$item->delete();$order->refreshTotal();});return back()->with('success','Detail pesanan dihapus.');}
}
