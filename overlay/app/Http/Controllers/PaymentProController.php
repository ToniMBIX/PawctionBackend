<?php
namespace App\Http\Controllers;
use App\Models\{Auction,Order}; use Illuminate\Http\Request; use Illuminate\Support\Str; use Illuminate\Support\Facades\Mail; use App\Mail\OrderReceipt; use Barryvdh\DomPDF\Facade\Pdf;
class PaymentProController extends Controller{
  public static function sendReceiptWithPdf(Order $order){ $pdf=Pdf::loadView('pdf.order',['order'=>$order])->output(); Mail::to($order->buyer_email)->send((new OrderReceipt($order))->attachData($pdf,"Pawction_{$order->id}.pdf",['mime'=>'application/pdf'])); }
  public function createPayPal(Request $req){ return response()->json(['todo'=>'configure_paypal_sandbox'],501); }
  public function capturePayPal(Request $req){ return response()->json(['todo'=>'capture_paypal_order'],501); }
}