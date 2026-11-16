<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void { Schema::create('orders',function(Blueprint $t){$t->id();$t->foreignId('user_id')->constrained()->restrictOnDelete();$t->string('code')->unique();$t->string('shipping_name');$t->text('shipping_address');$t->decimal('total',12,2)->default(0);$t->string('status')->default('pending');$t->boolean('stock_returned')->default(false);$t->timestamps();}); }
 public function down(): void { Schema::dropIfExists('orders'); }
};
