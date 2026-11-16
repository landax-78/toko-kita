<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Payment extends Model {
 protected $fillable=['order_id','method','reference','status','confirmed_at'];
 protected function casts(): array { return ['confirmed_at'=>'datetime']; }
 public function order(): BelongsTo { return $this->belongsTo(Order::class); }
}
