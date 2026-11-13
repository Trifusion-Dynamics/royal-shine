<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$tables = ['users','categories','collections','products','product_images','banners','enquiries','pages','galleries','settings','testimonials'];
foreach($tables as $t){
    try{
        $c = DB::table($t)->count();
        echo str_pad($t, 25).': '.$c.PHP_EOL;
    } catch(\Exception $e){
        echo str_pad($t, 25).': ERROR-'.$e->getMessage().PHP_EOL;
    }
}
