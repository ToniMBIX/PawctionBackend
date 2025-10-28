<?php
namespace App\Http\Controllers;
use App\Models\{Auction,Order}; use Illuminate\Http\Request; use Illuminate\Support\Str;
class OrderController extends Controller{
  public function store(Request $req){
    $d=$req->validate(['auctionId'=>'required|string|exists:auctions,id','buyer.name'=>'required|string','buyer.email'=>'required|email','buyer.address'=>'required|string']);
    $a=Auction::find($d['auctionId']); $o=Order::create(['id'=>Str::upper(Str::random(10)),'auction_id'=>$a->id,'buyer_name'=>$d['buyer']['name'],'buyer_email'=>$d['buyer']['email'],'buyer_address'=>$d['buyer']['address'],'total'=>$a->current_price,'created_at'=>now()->valueOf(),'payment_provider'=>'none','payment_id'=>null,'payment_status'=>'unpaid']); $a->status='sold'; $a->save();
    return ['orderId'=>$o->id];
  }
}