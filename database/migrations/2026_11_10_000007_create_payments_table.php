<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void { Schema::create('payments',function(Blueprint $t){$t->id();$t->foreignId('order_id')->unique()->constrained()->cascadeOnDelete();$t->string('method')->default('bank_transfer');$t->string('reference')->nullable();$t->string('status')->default('pending');$t->timestamp('confirmed_at')->nullable();$t->timestamps();}); }
 public function down(): void { Schema::dropIfExists('payments'); }
};
