<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model; use Illuminate\Database\Eloquent\Factories\HasFactory;
class Auction extends Model{ use HasFactory; public $incrementing=false; protected $keyType='string';
protected $fillable=['id','animal_id','title','base_price','current_price','min_increment','ends_at','status'];
protected $casts=['ends_at'=>'integer']; public function animal(){ return $this->belongsTo(Animal::class); } public function bids(){ return $this->hasMany(Bid::class)->orderByDesc('created_at'); } }