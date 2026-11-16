<?php
namespace App\Http\Controllers\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
class UserController extends BaseResourceController {
 protected string $model=User::class; protected string $resource='users'; protected string $title='Pengguna';
 protected array $fields=['name'=>['label'=>'Nama','type'=>'text'],'email'=>['label'=>'Email','type'=>'email'],'password'=>['label'=>'Kata sandi (kosongkan saat edit)','type'=>'password'],'role'=>['label'=>'Peran','type'=>'select','options'=>['customer'=>'Pelanggan','admin'=>'Admin']]];
 protected function rules(?int $id=null):array{return ['name'=>'required|string|max:100','email'=>['required','email','max:255',Rule::unique('users','email')->ignore($id)],'password'=>$id?'nullable|string|min:8':'required|string|min:8','role'=>'required|in:admin,customer'];}
 public function update(Request $request,int $id){$data=$request->validate($this->rules($id));if(empty($data['password']))unset($data['password']);$this->record($id)->update($data);return redirect()->route('admin.users.index')->with('success','Pengguna diperbarui.');}
 public function destroy(int $id){if(auth()->id()===$id)return back()->with('error','Tidak boleh menghapus akun sendiri.');return parent::destroy($id);}
}
