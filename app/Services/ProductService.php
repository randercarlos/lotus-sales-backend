<?php

namespace App\Services;

use App\Http\Requests\ProductFormRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductService extends AbstractService
{
    protected $model;

    public function __construct() {
        $this->model = new Product();
    }

    public function findAll(Request $request) {

        if ($request->hasHeader('X-LoadAll-Without-Pagination')) {
            return $this->findAllOrderedByName();
        }

        $query = Product::with('category');
        $query = $this->buildFilters($query, $request);

        return $query->orderBy($request->_sort ?? 'id', $request->_order ?? 'asc')
            ->paginate($request->_limit ?? Product::RECORDS_PER_PAGE, ['*'], 'page', $request->_page ?? 1);
    }

    public function findAllOrderedByName() {
        return Product::orderBy('name')->get();
    }

    public function create($request) {
        $data = $request->all();

        $path = null;
        if ($request->file('photo')) {
            $path = $request->file('photo')->store(Product::PHOTO_UPLOAD_FOLDER);
            $data['photo'] = explode('//', $path)[1];
        }

        if (!$product = Product::create($data)) {
            return response()->json('Fail on create product', 500);
        }

        return $product;
    }

    public function update($request, $id) {
        $data = $request->all();
        if (!$product = Product::find($id)) {
            throw new \Exception("Product with id $id not exists");
        }

        $path = null;
        if ($request->file('photo') ) {

           $this->deleteOldImage($product);

            // upload new photo
            $path = $request->file('photo')->store(Product::PHOTO_UPLOAD_FOLDER);
            $data['photo'] = explode('//', $path)[1];
        }

        if (!$product = $product->update($data)) {
            return response()->json("Fail on update product with id $id", 500);
        }

        return $product;
    }

    private function upload($base64_image): string {
        $image_64 = $base64_image; //your base64 encoded data
        $extension = explode('/', explode(':', substr($image_64, 0, strpos($image_64, ';')))[1])[1];   // .jpg .png .pdf
        $replace = substr($image_64, 0, strpos($image_64, ',')+1);

        // find substring fro replace here eg: data:image/png;base64,
        $image = str_replace($replace, '', $image_64);
        $image = str_replace(' ', '+', $image);

        $imageName = Str::random(10) . '.' . $extension;
        Storage::disk('public')->put(Product::PHOTO_UPLOAD_FOLDER . $imageName, base64_decode($image));

        return $imageName;
    }
    private function deleteOldImage(Product $product) {
         // delete old photo
        if ($product->photo && Storage::exists(Product::PHOTO_UPLOAD_FOLDER . $product->photo)) {
            if (!Storage::delete(Product::PHOTO_UPLOAD_FOLDER . $product->photo)) {
                throw new \Exception("Fail on delete old photo of product with id {$product->id}!");
            }
        }
    }

    private function buildFilters(Builder $query, $request): Builder {
        $query->when($request->id, function ($q) use ($request) {
            return $q->find((int) $request->id);
        });

        $query->when($request->name, function ($q) use ($request) {
            return $q->where('name', 'like', '%' . mb_strtolower($request->name) . '%');
        });

        $query->when($request->category_id, function ($q) use ($request) {
            return $q->where('category_id', $request->category_id);
        });

        $query->when($request->cost_price['lte'], function ($q) use ($request) {
            return $q->where('cost_price', '<=', (int) $request->cost_price['lte']);
        });

        $query->when($request->cost_price['gte'], function ($q) use ($request) {
            return $q->where('cost_price', '>=', (int) $request->cost_price['gte']);
        });

        $query->when($request->sale_price['lte'], function ($q) use ($request) {
            return $q->where('sale_price', '<=', (int) $request->sale_price['lte']);
        });

        $query->when($request->sale_price['gte'], function ($q) use ($request) {
            return $q->where('sale_price', '>=', (int) $request->sale_price['gte']);
        });

        $query->when($request->units_stock['lte'], function ($q) use ($request) {
            return $q->where('units_stock', '<=', (int) $request->units_stock['lte']);
        });

        $query->when($request->units_stock['gte'], function ($q) use ($request) {
            return $q->where('units_stock', '>=', (int) $request->units_stock['gte']);
        });

        $query->when($request->active, function ($q) use ($request) {
            return $q->whereActive((boolean) $request->active);
        });

        return $query;
    }

    public function delete(int $id): bool {

        if (!$product = Product::find($id)) {
            throw new \Exception("Product with id $id not exists");
        }

        if ($product->photo && Storage::exists(Product::PHOTO_UPLOAD_FOLDER . $product->photo)) {
             if (!Storage::delete(Product::PHOTO_UPLOAD_FOLDER . $product->photo)) {
                 throw new \Exception("Fail on delete photo of product with id $id!");
             }
        }

        if (!$product->delete()) {
            throw new \Exception("Fail on delete product with id $id!");
        }

        return true;
    }
}
