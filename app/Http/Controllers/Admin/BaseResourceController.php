<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
abstract class BaseResourceController extends Controller {
 protected string $model;
 protected string $resource;
 protected string $title;
 protected array $fields=[];
 abstract protected function rules(?int $id=null): array;
 protected function record(int $id){return ($this->model)::findOrFail($id);}
 protected function formFields(): array {
  $fields=$this->fields;
  foreach($fields as &$f){if(isset($f['options_model'])){$model=$f['options_model'];$display=$f['display']??'name';$f['options']=$model::orderBy($display)->pluck($display,'id')->all();}}
  return $fields;
 }
 protected function page(array $extra=[]): array {return array_merge(['title'=>$this->title,'resource'=>$this->resource,'fields'=>$this->formFields()],$extra);}
 public function index(){return view('admin.resources.index',$this->page(['records'=>($this->model)::latest()->paginate(15)]));}
 public function create(){return view('admin.resources.form',$this->page(['record'=>null]));}
 public function store(Request $request){$data=$request->validate($this->rules());DB::transaction(fn()=>($this->model)::create($data));return redirect()->route('admin.'.$this->resource.'.index')->with('success','Data berhasil ditambahkan.');}
 public function edit(int $id){return view('admin.resources.form',$this->page(['record'=>$this->record($id)]));}
 public function update(Request $request,int $id){$data=$request->validate($this->rules($id));DB::transaction(fn()=>$this->record($id)->update($data));return redirect()->route('admin.'.$this->resource.'.index')->with('success','Data berhasil diperbarui.');}
 public function destroy(int $id){
  try{DB::transaction(fn()=>$this->record($id)->delete());}
  catch(QueryException $e){return back()->with('error','Data masih digunakan oleh tabel lain, sehingga tidak dapat dihapus.');}
  return back()->with('success','Data berhasil dihapus.');
 }
}
