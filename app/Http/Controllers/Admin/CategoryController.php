<?php
namespace App\Http\Controllers\Admin;
use App\Models\Category;
use Illuminate\Validation\Rule;
class CategoryController extends BaseResourceController {
 protected string $model=Category::class; protected string $resource='categories'; protected string $title='Kategori';
 protected array $fields=['name'=>['label'=>'Nama kategori','type'=>'text'],'description'=>['label'=>'Deskripsi','type'=>'textarea']];
 protected function rules(?int $id=null):array{return ['name'=>['required','string','max:100',Rule::unique('categories','name')->ignore($id)],'description'=>'nullable|string|max:500'];}
}
