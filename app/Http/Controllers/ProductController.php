<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductFormRequest;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    private $productService;

    public function __construct(ProductService $productService) {
        $this->productService = $productService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return ProductResource::collection($this->productService->findAll($request));
//        return new ProductCollection($this->productService->findAll($request));
    }

    public function show(int $id)
    {
        return new ProductResource($this->productService->find($id));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return response()->json($this->productService->create($request), 201);
    }

    public function update(Request $request, $id)
    {
        return response()->json($this->productService->update($request, $id), 200);
    }

    public function destroy(int $id)
    {
        return response()->json($this->productService->delete($id), 200);
    }

}
