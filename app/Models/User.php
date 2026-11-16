<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
class User extends Authenticatable {
 use Notifiable;
 protected $fillable=['name','email','password','role'];
 protected $hidden=['password','remember_token'];
 protected function casts(): array { return ['email_verified_at'=>'datetime','password'=>'hashed']; }
 public function cartItems(): HasMany { return $this->hasMany(CartItem::class); }
 public function orders(): HasMany { return $this->hasMany(Order::class); }
}
