<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model {
    protected $fillable = ['user_id','auction_id','total_amount','split_pawction','split_greenpeace','status','payment_id'];
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function auction(): BelongsTo { return $this->belongsTo(Auction::class); }
}
