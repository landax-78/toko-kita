<?php
namespace Tests\Feature;
use App\Models\{User,Product,Category,Order};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
class TokoOnlineTest extends TestCase {
 use RefreshDatabase;
 public function test_beranda_dan_pencarian_katalog_dapat_diakses(): void {
  $category=Category::create(['name'=>'Elektronik']);
  Product::create(['category_id'=>$category->id,'sku'=>'H-001','name'=>'Headphone Demo','price'=>250000,'stock'=>5]);
  $this->get(route('home'))->assertOk()->assertSee('Produk terbaru')->assertSee('Headphone Demo');
  $this->get(route('shop.index',['q'=>'Headphone']))->assertOk()->assertSee('Headphone Demo');
  $this->get(route('shop.index',['q'=>'TidakAda']))->assertOk()->assertDontSee('Headphone Demo');
 }
 public function test_pelanggan_bisa_checkout_dan_admin_mengonfirmasi_pembayaran(): void {
  $customer=User::create(['name'=>'Rani','email'=>'rani@example.test','password'=>'secret123','role'=>'customer']);
  $admin=User::create(['name'=>'Admin','email'=>'admin@example.test','password'=>'secret123','role'=>'admin']);
  $category=Category::create(['name'=>'Alat Tulis']);$product=Product::create(['category_id'=>$category->id,'sku'=>'P-001','name'=>'Pulpen','price'=>5000,'stock'=>10]);
  $this->actingAs($customer)->post(route('cart.store'),['product_id'=>$product->id,'quantity'=>2])->assertRedirect(route('cart.index'));
  $this->post(route('checkout.store'),['shipping_name'=>'Rani','shipping_address'=>'Jl. Merpati Nomor 10'])->assertRedirect();
  $order=Order::with('payment')->firstOrFail();
  $this->assertEquals(10000,(float)$order->total);$this->assertEquals(8,$product->fresh()->stock);
  $this->post(route('payments.submit',$order),['reference'=>'TRX-12345'])->assertRedirect();
  $this->actingAs($admin)->put(route('admin.payments.update',$order->payment->id),['order_id'=>$order->id,'method'=>'bank_transfer','reference'=>'TRX-12345','status'=>'confirmed'])->assertRedirect();
  $this->assertEquals('paid',$order->fresh()->status);
 }
 public function test_pelanggan_tidak_bisa_masuk_halaman_admin():void {
  $customer=User::create(['name'=>'Rani','email'=>'rani@example.test','password'=>'secret123','role'=>'customer']);
  $this->actingAs($customer)->get(route('admin.products.index'))->assertForbidden();
 }
 public function test_validasi_produk_harus_memiliki_sku_unik():void {
  $admin=User::create(['name'=>'Admin','email'=>'admin@example.test','password'=>'secret123','role'=>'admin']);
  $category=Category::create(['name'=>'Elektronik']);Product::create(['category_id'=>$category->id,'sku'=>'SK-001','name'=>'Mouse','price'=>10000,'stock'=>3]);
  $this->actingAs($admin)->post(route('admin.products.store'),['category_id'=>$category->id,'sku'=>'SK-001','name'=>'Keyboard','price'=>20000,'stock'=>2])->assertSessionHasErrors('sku');
 }
 public function test_pembatalan_mengembalikan_stok():void {
  $customer=User::create(['name'=>'Rani','email'=>'rani@example.test','password'=>'secret123','role'=>'customer']);
  $category=Category::create(['name'=>'Elektronik']);$product=Product::create(['category_id'=>$category->id,'sku'=>'SK-001','name'=>'Mouse','price'=>10000,'stock'=>3]);
  $this->actingAs($customer)->post(route('cart.store'),['product_id'=>$product->id,'quantity'=>2]);
  $this->post(route('checkout.store'),['shipping_name'=>'Rani','shipping_address'=>'Jl. Merpati Nomor 10']);
  $order=Order::firstOrFail();$this->post(route('orders.cancel',$order))->assertRedirect();
  $this->assertEquals('cancelled',$order->fresh()->status);$this->assertEquals(3,$product->fresh()->stock);
 }
}
