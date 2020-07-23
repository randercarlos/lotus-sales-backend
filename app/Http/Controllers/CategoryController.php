<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Services\CategoryService;
use App\Services\ProductService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    private $categoryService;

    public function __construct(CategoryService $categoryService) {
        $this->categoryService = $categoryService;
    }

    public function index(Request $request) {
        return $this->categoryService->findAll($request);
    }

    public function show(int $id)
    {
        return $this->categoryService->find($id);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return response()->json($this->categoryService->create($request), 201);
    }

    public function update(Request $request, $id)
    {
        return response()->json($this->categoryService->update($request, $id), 200);
    }

    public function destroy(int $id)
    {
        return response()->json($this->categoryService->delete($id), 200);
    }
}
