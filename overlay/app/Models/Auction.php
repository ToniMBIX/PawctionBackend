<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Auction extends Model {
    use HasFactory;
    protected $fillable = ['title','description','image_url','starting_price','current_price','ends_at','animal_name','animal_info_url','qr_path','winner_user_id'];
    protected $casts = ['ends_at' => 'datetime'];

    public function bids(): HasMany { return $this->hasMany(Bid::class); }
    public function favorites(): HasMany { return $this->hasMany(Favorite::class); }
}
