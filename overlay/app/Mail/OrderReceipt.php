<?php
namespace App\Mail; use Illuminate\Bus\Queueable; use Illuminate\Mail\Mailable; use Illuminate\Queue\SerializesModels;
class OrderReceipt extends Mailable{ use Queueable, SerializesModels; public $order; public function __construct($order){ $this->order=$order; }
public function build(){ return $this->subject('Pawction — Confirmación de pedido')->markdown('mail.order-receipt',['order'=>$this->order]); } }