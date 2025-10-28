<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model; use Illuminate\Database\Eloquent\Factories\HasFactory;
class Order extends Model{ use HasFactory; public $incrementing=false; protected $keyType='string'; public $timestamps=false;
protected $fillable=['id','auction_id','buyer_name','buyer_email','buyer_address','total','created_at','payment_provider','payment_id','payment_status'];
protected $casts=['created_at'=>'integer']; public function auction(){ return $this->belongsTo(Auction::class); } }