<?php
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\EnsureAdmin;
use App\Http\Controllers\{AuthController,ShopController,CartController,CheckoutController,OrderController,PaymentSubmissionController};
use App\Http\Controllers\Admin;
Route::get('/', [ShopController::class, 'home'])->name('home');
Route::get('/produk', [ShopController::class, 'index'])->name('shop.index');
Route::get('/produk/{product}',[ShopController::class,'show'])->name('shop.show');
Route::middleware('guest')->group(function(){
 Route::get('/login',[AuthController::class,'loginForm'])->name('login');Route::post('/login',[AuthController::class,'login']);
 Route::get('/register',[AuthController::class,'registerForm'])->name('register');Route::post('/register',[AuthController::class,'register']);
});
Route::middleware('auth')->group(function(){
 Route::post('/logout',[AuthController::class,'logout'])->name('logout');
 Route::resource('keranjang',CartController::class)->only(['index','store','update','destroy'])->parameters(['keranjang'=>'cart'])->names('cart');
 Route::get('/checkout',[CheckoutController::class,'create'])->name('checkout.create');Route::post('/checkout',[CheckoutController::class,'store'])->name('checkout.store');
 Route::get('/pesanan',[OrderController::class,'index'])->name('orders.index');Route::get('/pesanan/{order}',[OrderController::class,'show'])->name('orders.show');
 Route::post('/pesanan/{order}/batal',[OrderController::class,'cancel'])->name('orders.cancel');
 Route::post('/pesanan/{order}/pembayaran',[PaymentSubmissionController::class,'store'])->name('payments.submit');
});
Route::prefix('admin')->name('admin.')->middleware(['auth',EnsureAdmin::class])->group(function(){
 Route::get('/', [Admin\DashboardController::class, 'index'])->name('home');
 Route::resource('users',Admin\UserController::class)->parameters(['users'=>'id'])->except('show');
 Route::resource('categories',Admin\CategoryController::class)->parameters(['categories'=>'id'])->except('show');
 Route::resource('products',Admin\ProductController::class)->parameters(['products'=>'id'])->except('show');
 Route::resource('cart-items',Admin\CartItemController::class)->parameters(['cart-items'=>'id'])->except('show');
 Route::resource('orders',Admin\OrderController::class)->parameters(['orders'=>'id'])->except('show');
 Route::resource('order-items',Admin\OrderItemController::class)->parameters(['order-items'=>'id'])->except('show');
 Route::resource('payments',Admin\PaymentController::class)->parameters(['payments'=>'id'])->except('show');
});
