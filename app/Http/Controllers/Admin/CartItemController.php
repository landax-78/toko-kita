<?php
namespace App\Http\Controllers\Admin;
use App\Models\{CartItem,User,Product};
use Illuminate\Validation\Rule;
class CartItemController extends BaseResourceController {
 protected string $model=CartItem::class; protected string $resource='cart-items'; protected string $title='Item Keranjang';
 protected array $fields=['user_id'=>['label'=>'Pelanggan','type'=>'select','options_model'=>User::class],'product_id'=>['label'=>'Produk','type'=>'select','options_model'=>Product::class],'quantity'=>['label'=>'Jumlah','type'=>'number','step'=>'1']];
 protected function rules(?int $id=null):array{return ['user_id'=>'required|exists:users,id','product_id'=>['required','exists:products,id',Rule::unique('cart_items','product_id')->where('user_id',request('user_id'))->ignore($id)],'quantity'=>'required|integer|min:1'];}
}
