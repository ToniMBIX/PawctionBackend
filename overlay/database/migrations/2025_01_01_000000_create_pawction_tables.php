<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('auctions', function (Blueprint $t) {
            $t->id();
            $t->string('title');
            $t->text('description')->nullable();
            $t->string('image_url')->nullable();
            $t->string('animal_name')->nullable();
            $t->string('animal_info_url')->nullable();
            $t->unsignedBigInteger('starting_price')->default(0);
            $t->unsignedBigInteger('current_price')->default(0);
            $t->timestamp('ends_at')->index();
            $t->unsignedBigInteger('winner_user_id')->nullable();
            $t->string('qr_path')->nullable();
            $t->timestamps();
        });
        Schema::create('bids', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->foreignId('auction_id')->constrained()->cascadeOnDelete();
            $t->unsignedBigInteger('amount');
            $t->timestamps();
        });
        Schema::create('favorites', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->foreignId('auction_id')->constrained()->cascadeOnDelete();
            $t->timestamps();
            $t->unique(['user_id','auction_id']);
        });
        Schema::create('orders', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->foreignId('auction_id')->constrained()->cascadeOnDelete();
            $t->unsignedBigInteger('total_amount');
            $t->unsignedBigInteger('split_pawction');
            $t->unsignedBigInteger('split_greenpeace');
            $t->string('status')->default('pending');
            $t->string('payment_id')->nullable();
            $t->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('orders');
        Schema::dropIfExists('favorites');
        Schema::dropIfExists('bids');
        Schema::dropIfExists('auctions');
    }
};
