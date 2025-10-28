<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\{Auction,Bid,Favorite,Order};
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facade\QrCode;

class AuctionController extends Controller {
    public function index() { return Auction::orderBy('ends_at')->get(); }
    public function show(Auction $auction) {
        return $auction;
    }
    public function bid(Request $r, Auction $auction) {
        $r->validate(['amount'=>'required|integer|min:1']);
        $user = $r->user();
        if (now()->greaterThan($auction->ends_at)) return response()->json(['message'=>'Auction ended'],400);

        DB::transaction(function() use($r,$user,$auction) {
            $amount = $r->amount;
            if ($amount <= $auction->current_price) abort(400, 'Bid must be higher');
            Bid::create(['user_id'=>$user->id,'auction_id'=>$auction->id,'amount'=>$amount]);
            // Reinicio 24h
            $auction->current_price = $amount;
            $auction->ends_at = now()->addDay();
            $auction->save();
        });
        return ['ok'=>true];
    }
    public function favorite(Request $r, Auction $auction) {
        $user = $r->user();
        Favorite::firstOrCreate(['user_id'=>$user->id,'auction_id'=>$auction->id]);
        return ['ok'=>true];
    }
    public function myFavorites(Request $r) {
        return Favorite::with('auction')->where('user_id',$r->user()->id)->get();
    }
    public function checkout(Request $r, Auction $auction) {
        $r->validate(['payment_method'=>'required']);
        // Simulación Stripe (usar STRIPE_SECRET para real). Creamos order y "bloqueamos" subasta.
        if (now()->lessThan($auction->ends_at)) return response()->json(['message'=>'Auction not finished'],400);

        $total = $auction->current_price;
        $split = intval($total/2);
        $order = Order::create([
            'user_id'=>$r->user()->id,
            'auction_id'=>$auction->id,
            'total_amount'=>$total,
            'split_pawction'=>$split,
            'split_greenpeace'=>$total - $split,
            'status'=>'paid',
            'payment_id'=>'test_'+uniqid()
        ]);
        // PDF + email (mailer log)
        $pdf = Pdf::loadView('order', ['order'=>$order, 'auction'=>$auction]);
        $pdfPath = 'orders/order_'.$order->id.'.pdf';
        Storage::put('public/'.$pdfPath, $pdf->output());
        // QR
        $qrContent = config('app.url').'/animal/'.$auction->id;
        $qrData = QrCode::format('png')->size(300)->generate($qrContent);
        $qrPath = 'qrs/qr_'.$auction->id.'.png';
        Storage::put('public/'.$qrPath, $qrData);
        $auction->qr_path = '/storage/'.$qrPath;
        $auction->winner_user_id = $r->user()->id;
        $auction->save();
        return ['ok'=>true,'order_id'=>$order->id];
    }
    public function health() { return ['ok'=>true]; }
}
