<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model; use Illuminate\Database\Eloquent\Factories\HasFactory;
class Animal extends Model{ use HasFactory; public $incrementing=false; protected $keyType='string';
protected $fillable=['id','name','species','description','image']; public function auctions(){ return $this->hasMany(Auction::class); } }