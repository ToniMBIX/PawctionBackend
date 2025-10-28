<?php
namespace Database\Seeders; use Illuminate\Database\Seeder; use App\Models\{Animal,Auction};
class DatabaseSeeder extends Seeder{
  public function run(): void{
    $now=now()->valueOf();
    foreach([['id'=>'cat-luna','name'=>'Luna','species'=>'cat'],['id'=>'dog-max','name'=>'Max','species'=>'dog'],['id'=>'cat-nala','name'=>'Nala','species'=>'cat']] as $a) \App\Models\Animal::create($a);
    foreach([['id'=>'a1','animal_id'=>'cat-luna','title'=>'Pack Luna','base_price'=>10,'current_price'=>10,'min_increment'=>1,'ends_at'=>$now+3600000,'status'=>'active'],['id'=>'a2','animal_id'=>'dog-max','title'=>'Pack Max','base_price'=>12,'current_price'=>12,'min_increment'=>2,'ends_at'=>$now+5400000,'status'=>'active']] as $x) \App\Models\Auction::create($x);
  }
}