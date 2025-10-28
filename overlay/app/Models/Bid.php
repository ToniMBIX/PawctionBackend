<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model; use Illuminate\Database\Eloquent\Factories\HasFactory;
class Bid extends Model{ use HasFactory; public $incrementing=false; protected $keyType='string'; public $timestamps=false;
protected $fillable=['id','auction_id','amount','created_at']; protected $casts=['created_at'=>'integer']; public function auction(){ return $this->belongsTo(Auction::class); } }