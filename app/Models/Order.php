<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;
class Order extends Model {
 protected $fillable=['user_id','code','shipping_name','shipping_address','total','status','stock_returned'];
 protected function casts(): array { return ['total'=>'decimal:2','stock_returned'=>'boolean']; }
 protected static function booted(): void { static::creating(function (self $order) { if (!$order->code) $order->code='ORD-'.now()->format('YmdHis').'-'.Str::upper(Str::random(6)); }); }
 public function user(): BelongsTo { return $this->belongsTo(User::class); }
 public function items(): HasMany { return $this->hasMany(OrderItem::class); }
 public function payment(): HasOne { return $this->hasOne(Payment::class); }
 public function refreshTotal(): void { $this->update(['total'=>$this->items()->sum('subtotal')]); }
}
