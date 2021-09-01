<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

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
        // $this->model->get()
        $user = auth()->user();

        return response()->json($this->model->where('user_id', $user['id'])->get(), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        $data = [
            'name' => $request->get('name'),
            'price' => $request->get('price'),
            'weight' => $request->get('weight'),
            'user_id' => $user['id']
        ];

        $validator = $this->validateAll($data);

        if ($validator->fails()) {
            $validator = $validator->errors()->first();
            return response()->json(['errors' =>
            ['messages' => $validator]], 404);
        }

        $product = $this->model->create($data);
        return response()->json($product, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $user = auth()->user();
        $data = [
            'name' => $request->get('name'),
            'price' => $request->get('price'),
            'weight' => $request->get('weight'),
            'user_id' => $user['id']
        ];

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

        $product = $this->model->where('user_id', $user['id'])->find($id);

        if ($product == null) {
            return response()->json(['message' => "Product not found"], 404);
        }
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

        $user = auth()->user();
        $data = [
            'name' => $request->get('name'),
            'price' => $request->get('price'),
            'weight' => $request->get('weight'),
            'user_id' => $user['id']
        ];

        $validator = $this->validateAll($data);

        if ($validator->fails()) {
            $validator = $validator->errors()->first();
            return response()->json(['errors' =>
            ['messages' => $validator]], 400);
        }

        $this->model->where('user_id', $user['id'])->find($id)->update($data);
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
        $user = auth()->user();
        $product = $this->model->where('user_id', $user['id'])->find($id);
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
