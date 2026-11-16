<?php
namespace Database\Seeders;
use App\Models\{User,Category,Product};
use Illuminate\Database\Seeder;
class DatabaseSeeder extends Seeder {
 public function run(): void {
  User::updateOrCreate(['email'=>'admin@toko.test'],['name'=>'Admin Toko','password'=>'Admin12345!','role'=>'admin']);
  User::updateOrCreate(['email'=>'pelanggan@toko.test'],['name'=>'Pelanggan Demo','password'=>'Pelanggan123!','role'=>'customer']);
  $a=Category::firstOrCreate(['name'=>'Elektronik'],['description'=>'Peralatan elektronik']);
  $b=Category::firstOrCreate(['name'=>'Alat Tulis'],['description'=>'Alat tulis sekolah']);
  Product::updateOrCreate(['sku'=>'EL-001'],['category_id'=>$a->id,'name'=>'Mouse Wireless','description'=>'Mouse tanpa kabel','price'=>85000,'stock'=>25]);
  Product::updateOrCreate(['sku'=>'AT-001'],['category_id'=>$b->id,'name'=>'Buku Tulis','description'=>'Buku 50 lembar','price'=>10000,'stock'=>100]);
  Product::updateOrCreate(['sku'=>'AT-002'],['category_id'=>$b->id,'name'=>'Pulpen Hitam','description'=>'Pulpen tinta hitam','price'=>5000,'stock'=>80]);
 }
}
