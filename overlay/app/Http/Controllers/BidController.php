<?php
namespace App\Http\Controllers;
use App\Models\{Auction,Bid}; use Illuminate\Http\Request; use Illuminate\Support\Str;
class BidController extends Controller{
  const DAY_MS=86400000;
  public function store(Request $req,$id){
    $data=$req->validate(['amount'=>'required|numeric|min:0.01']); $a=Auction::find($id);
    if(!$a) return response()->json(['error'=>'auction not found'],404); if($a->status!=='active') return response()->json(['error'=>'auction is not active'],400);
    if(now()->valueOf()>=$a->ends_at) return response()->json(['error'=>'auction ended'],400);
    if($data['amount']<$a->min_increment) return response()->json(['error'=>"min increment is {$a->min_increment}"],400);
    $a->current_price += $data['amount']; $a->ends_at = now()->valueOf()+self::DAY_MS; $a->save();
    Bid::create(['id'=>Str::upper(Str::random(10)),'auction_id'=>$a->id,'amount'=>$data['amount'],'created_at'=>now()->valueOf()]);
    return ['id'=>$a->id,'currentPrice'=>(float)$a->current_price,'endsAt'=>(int)$a->ends_at,'minIncrement'=>(float)$a->min_increment,'status'=>$a->status];
  }
}