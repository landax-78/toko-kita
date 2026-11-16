<?php
namespace App\Http\Controllers\Admin;
use App\Models\{Payment,Order};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
class PaymentController extends BaseResourceController {
 protected string $model=Payment::class; protected string $resource='payments'; protected string $title='Pembayaran';
 protected array $fields=['order_id'=>['label'=>'Pesanan','type'=>'select','options_model'=>Order::class,'display'=>'code'],'method'=>['label'=>'Metode','type'=>'select','options'=>['bank_transfer'=>'Transfer Bank (simulasi)']],'reference'=>['label'=>'Referensi transfer','type'=>'text'],'status'=>['label'=>'Status','type'=>'select','options'=>['pending'=>'Menunggu','confirmed'=>'Dikonfirmasi','rejected'=>'Ditolak']]];
 protected function rules(?int $id=null):array{return ['order_id'=>['required','exists:orders,id',Rule::unique('payments','order_id')->ignore($id)],'method'=>'required|in:bank_transfer','reference'=>'nullable|string|max:100','status'=>'required|in:pending,confirmed,rejected'];}
 private function sync(Payment $payment,?int $previousOrder=null):void {
  if($previousOrder && $previousOrder!==$payment->order_id){$old=Order::find($previousOrder);if($old && $old->status!=='cancelled')$old->update(['status'=>'pending']);}
  $order=Order::whereKey($payment->order_id)->lockForUpdate()->firstOrFail();
  if($order->status==='cancelled' && $payment->status==='confirmed')throw ValidationException::withMessages(['status'=>'Pesanan yang dibatalkan tidak dapat dibayar.']);
  $payment->updateQuietly(['confirmed_at'=>$payment->status==='confirmed'?now():null]);
  if($order->status!=='cancelled')$order->update(['status'=>$payment->status==='confirmed'?'paid':'pending']);
 }
 public function store(Request $request){$data=$request->validate($this->rules());DB::transaction(function()use($data){$payment=Payment::create($data);$this->sync($payment);});return redirect()->route('admin.payments.index')->with('success','Pembayaran dibuat.');}
 public function update(Request $request,int $id){$data=$request->validate($this->rules($id));DB::transaction(function()use($data,$id){$payment=Payment::whereKey($id)->lockForUpdate()->firstOrFail();$before=$payment->order_id;$payment->update($data);$this->sync($payment,$before);});return redirect()->route('admin.payments.index')->with('success','Pembayaran diperbarui.');}
 public function destroy(int $id){DB::transaction(function()use($id){$payment=Payment::whereKey($id)->lockForUpdate()->firstOrFail();$order=$payment->order;$payment->delete();if($order->status!=='cancelled')$order->update(['status'=>'pending']);});return back()->with('success','Pembayaran dihapus.');}
}
