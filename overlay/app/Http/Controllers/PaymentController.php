<?php
namespace App\Http\Controllers;
use App\Models\{Auction,Order}; use Illuminate\Http\Request; use Illuminate\Support\Str; use Stripe\StripeClient;
class PaymentController extends Controller{
  public function createStripeCheckout(Request $req){
    $d=$req->validate(['auctionId'=>'required|string|exists:auctions,id','buyer.name'=>'required|string','buyer.email'=>'required|email','buyer.address'=>'required|string','successUrl'=>'required|url','cancelUrl'=>'required|url']);
    $a=Auction::find($d['auctionId']); $s=new StripeClient(config('services.stripe.secret'));
    $session=$s->checkout->sessions->create(['mode'=>'payment','success_url'=>$d['successUrl'].'?session_id={CHECKOUT_SESSION_ID}','cancel_url'=>$d['cancelUrl'],'customer_email'=>$d['buyer']['email'],'line_items'=>[[ 'quantity'=>1,'price_data'=>['currency'=>'eur','unit_amount'=>(int) round($a->current_price*100),'product_data'=>['name'=>$a->title,'metadata'=>['auction_id'=>$a->id]]]]],'metadata'=>['auction_id'=>$a->id,'buyer_name'=>$d['buyer']['name'],'buyer_address'=>$d['buyer']['address']]]);
    return ['checkoutUrl'=>$session->url];
  }
  public function stripeWebhook(Request $req){ return response('OK',200); }
}