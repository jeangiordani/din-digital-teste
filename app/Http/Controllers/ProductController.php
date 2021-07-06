<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    protected $model;

    public function __construct(Product $product)
    {
        $this->model = $product;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json($this->model->get(), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = $this->validateAll($request->all());

        if ($validator->fails()) {
            $validator = $validator->errors()->first();
            return response()->json(['errors' =>
            ['messages' => $validator]], 404);
        }

        $product = $this->model->create($request->all());
        return response()->json($product, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $validator = Validator::make(['id' => $id], [
            'id' => 'required|numeric|exists:products,id',

        ], [
            'id.exists' => "Product not found"
        ]);

        if ($validator->fails()) {
            $validator = $validator->errors()->messages();
            return response()->json(['errors' =>
            ['messages' => $validator['id']]], 404);
        }

        $product = $this->model->find($id);
        return response()->json($product, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $result = $this->validateId($id);
        if ($result) {
            return $result;
        }

        $validator = $this->validateAll($request->all());

        if ($validator->fails()) {
            $validator = $validator->errors()->first();
            return response()->json(['errors' =>
            ['messages' => $validator]], 400);
        }

        $this->model->find($id)->update($request->all());
        return response()->json(['message' => 'Successfuly updated'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $result = $this->validateId($id);
        if ($result) {
            return $result;
        }
        $product = $this->model->find($id);
        $product->delete();
        return response()->json(['message' => 'Product deleted successfuly'], 204);
    }

    public function validateAll(array $data)
    {
        return Validator::make($data, [
            'user_id' => 'required|numeric|exists:users,id',
            'name' => 'required|string|max:140',
            'price' => 'required|numeric|',
            'weight' => 'required|numeric|',
        ], [
            'user_id.exists' => 'User not Found'
        ]);
    }

    public function validateId($id)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|numeric|exists:products,id',

        ], [
            'id.exists' => "Product not found"
        ]);

        if ($validator->fails()) {
            $validator = $validator->errors()->messages();
            return response()->json(['errors' =>
            ['messages' => $validator['id']]], 400);
        }
    }
}
