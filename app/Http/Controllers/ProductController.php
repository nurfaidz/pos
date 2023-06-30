<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Categorie;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use Exception;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('level:admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $products = Product::leftjoin('categories', 'categories.category_id', '=', 'products.category_id')->select('products.*', 'category_name')->get();

        if ($request->ajax()) {
            return DataTables::of($products)
                ->addIndexColumn()
                ->addColumn('select_all', function ($data) {
                    return '
                    <input type="checkbox" name="product_id[]" value="' . $data->product_id . '">';
                })
                ->addColumn('product_code', function ($data) {
                    return '
                    <span class="badge badge-dark">' . $data->product_code . '</span>';
                })
                ->addColumn('action', function ($data) {
                    $actionBtn = '<button title="Edit" type="button" data-toggle="modal" data-target="#modal-form" data-id="' . $data->product_id . '" class="edit btn btn-outline-success btn-lg" id="edit">Edit</button> <form id="form_delete_data" style="display:inline" class="" action="/product/delete/' . $data->product_id . '" method="post" title="Delete"><button title="Hapus" type="submit"  class="btn btn-outline-danger btn-lg" onclick="sweetConfirm(' . $data->product_id . ')">Hapus</button><input type="hidden" name="_method" value="delete" /><input type="hidden" name="_token" value="' . csrf_token() . '"></form>';
                    return $actionBtn;
                })
                ->rawColumns(['action', 'product_code', 'select_all'])
                ->make(true);
        }
        return view('products.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $products = Product::latest()->first() ?? new Product();
        $data['product_code'] = 'P' . addNullAtForward((int)$products->product_id + 1, 6);

        $validate = Validator::make(
            $data,
            [
                'product_name' => 'required|unique:products,product_name',
                'category_id' => 'required',
                'price' => 'required',
            ]
        );

        if ($validate->fails()) {
            return response()->json([
                'error' => $validate->errors()->toArray()
            ]);
        }

        $products = Product::create($data);

        if ($products) {
            return response()->json(["error" => false, "message" => "Berhasil tambah produk!"]);
        } else {
            return response()->json(["error" => true, "message" => "Gagal tambah produk"]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $products = Product::find($id);

        if ($products) {
            return response()->json(["error" => false, "data" => $products]);
        } else {
            return response()->json(["error" => false, "message" => "Data tidak ditemukan!"]);
        }
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
        $reqData = $request->all();
        $validate = validator::make(
            $reqData,
            [
                'product_name' => 'required|unique:categories,category_name',
                'price' => 'required',
            ]
        );

        if ($validate->fails()) {
            return response()->json([
                "error" => $validate->errors()->toArray()
            ]);
        }

        $products = Product::findOrFail($id);
        $products->update($reqData);

        if ($products) {
            return response()->json(["error" => false, "message" => "Berhasil update produk! "]);
        } else {
            return response()->json(["error" => true, "message" => "Data tidak ditemukan!"]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            Product::where('product_id', $id)->delete();
        } catch (Exception $e) {
            return response()->json(["error" => true, "message" => $e->getMessage()]);
        }

        return response()->json(["error" => false, "message" => "Berhasil hapus produk!"]);
    }

    public function select()
    {
        $list_all = Categorie::all();
        $select = [];

        foreach ($list_all as $item) {
            $select[] = ["id" => $item->category_id, "text" => $item->category_name];
        }
        return response()->json(["error" => false, "data" => $select]);
    }

    /**
     * Delete a listing of the resource with select box.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function checkBoxDelete(Request $request)
    {
        foreach ($request->product_id as $id) {
            $products = Product::find($id);
            $products->delete();
        }

        return response()->json(["error" => true, "message" => "Gagal hapus produk!"]);
    }
}
