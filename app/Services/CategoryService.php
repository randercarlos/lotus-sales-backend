<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class CategoryService extends AbstractService
{
    protected $model;

    public function __construct() {
        $this->model = new Category();
    }

    public function findAll(Request $request) {

        if ($request->hasHeader('X-LoadAll-Without-Pagination')) {
            return $this->findAllOrderedByName();
        }

        $query = Category::query();
        $query = $this->buildFilters($query, $request);

        return $query->orderBy($request->_sort ?? 'id', $request->_order ?? 'asc')
            ->paginate($request->_limit ?? Category::RECORDS_PER_PAGE, ['*'], 'page', $request->_page ?? 1);

    }

    public function findAllOrderedByName() {
        return Category::orderBy('name')->get();
    }

    public function create($request) {
        $data = $request->all();

        if (!$category = Category::create($data)) {
            return response()->json('Fail on create category', 500);
        }

        return $category;
    }

    public function update($request, $id) {
        $data = $request->all();

        if (!$category = Category::find($id)) {
            throw new \Exception("Category with id $id not exists");
        }

        if (!$category = $category->update($data)) {
            return response()->json("Fail on update category with id $id", 500);
        }

        return $category;
    }

    private function buildFilters(Builder $query, $request): Builder {
        $query->when($request->id, function ($q) use ($request) {
            return $q->find((int) $request->id);
        });

        $query->when($request->name, function ($q) use ($request) {
            return $q->where('name', 'like', '%' . mb_strtolower($request->name) . '%');
        });

        $query->when($request->description, function ($q) use ($request) {
            return $q->where('description', 'like', '%' . mb_strtolower($request->description) . '%');
        });

        return $query;
    }

    public function delete(int $id): bool {

        if (!$category = Category::find($id)) {
            throw new \Exception("Category with id $id not exists");
        }

        if (!$category->delete()) {
            throw new \Exception("Fail on delete category with id $id!");
        }

        return true;
    }

}
