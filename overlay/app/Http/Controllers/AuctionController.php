<?php
namespace App\Http\Controllers;
use App\Models\Auction;
class AuctionController extends Controller{
  public function index(){ return Auction::orderBy('ends_at')->get()->map(fn($a)=>[
    'id'=>$a->id,'animalId'=>$a->animal_id,'title'=>$a->title,'basePrice'=>(float)$a->base_price,'currentPrice'=>(float)$a->current_price,'minIncrement'=>(float)$a->min_increment,'endsAt'=>(int)$a->ends_at,'status'=>$a->status
  ]); }
  public function show($id){ $a=Auction::with('bids')->find($id); if(!$a) return response()->json(['error'=>'Not found'],404); return [
    'id'=>$a->id,'animalId'=>$a->animal_id,'title'=>$a->title,'basePrice'=>(float)$a->base_price,'currentPrice'=>(float)$a->current_price,'minIncrement'=>(float)$a->min_increment,'endsAt'=>(int)$a->ends_at,'status'=>$a->status,'bids'=>$a->bids
  ]; }
}