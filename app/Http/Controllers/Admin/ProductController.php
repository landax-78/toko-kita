<?php
namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProductController extends BaseResourceController
{
    protected string $model = Product::class;
    protected string $resource = 'products';
    protected string $title = 'Produk';
    protected array $fields = [
        'category_id' => ['label' => 'Kategori', 'type' => 'select', 'options_model' => Category::class],
        'sku' => ['label' => 'SKU unik', 'type' => 'text'],
        'name' => ['label' => 'Nama produk', 'type' => 'text'],
        'description' => ['label' => 'Deskripsi', 'type' => 'textarea'],
        'price' => ['label' => 'Harga (Rp)', 'type' => 'number', 'step' => '0.01'],
        'stock' => ['label' => 'Stok', 'type' => 'number', 'step' => '1'],
        'image_upload' => ['label' => 'Foto produk (JPG, PNG, WebP, maksimal 2 MB)', 'type' => 'file'],
    ];

    protected function rules(?int $id = null): array
    {
        return [
            'category_id' => 'required|exists:categories,id',
            'sku' => ['required', 'string', 'max:50', Rule::unique('products', 'sku')->ignore($id)],
            'name' => 'required|string|max:150',
            'description' => 'nullable|string|max:2000',
            'price' => 'required|numeric|min:0|max:9999999999',
            'stock' => 'required|integer|min:0',
            'image_upload' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ];
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules());
        unset($data['image_upload']);
        $newImage = null;

        if ($request->hasFile('image_upload')) {
            $newImage = $request->file('image_upload')->store('products', 'public');
            $data['image_path'] = $newImage;
        }

        try {
            DB::transaction(fn () => Product::create($data));
        } catch (\Throwable $e) {
            if ($newImage) Storage::disk('public')->delete($newImage);
            throw $e;
        }

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function update(Request $request, int $id)
    {
        $product = Product::findOrFail($id);
        $data = $request->validate($this->rules($id));
        unset($data['image_upload']);
        $oldImage = $product->image_path;
        $newImage = null;

        if ($request->hasFile('image_upload')) {
            $newImage = $request->file('image_upload')->store('products', 'public');
            $data['image_path'] = $newImage;
        }

        try {
            DB::transaction(fn () => $product->update($data));
        } catch (\Throwable $e) {
            if ($newImage) Storage::disk('public')->delete($newImage);
            throw $e;
        }

        if ($newImage && $oldImage) Storage::disk('public')->delete($oldImage);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(int $id)
    {
        $product = Product::findOrFail($id);
        $oldImage = $product->image_path;

        try {
            DB::transaction(fn () => $product->delete());
        } catch (QueryException $e) {
            return back()->with('error', 'Produk masih digunakan oleh tabel lain, sehingga tidak dapat dihapus.');
        }

        if ($oldImage) Storage::disk('public')->delete($oldImage);

        return back()->with('success', 'Produk berhasil dihapus.');
    }
}
