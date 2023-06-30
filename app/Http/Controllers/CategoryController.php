<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categorie;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use Exception;

class CategoryController extends Controller
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
        $categories = Categorie::orderBy('category_id', 'desc')->get();

        if ($request->ajax()) {
            return DataTables::of($categories)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    $actionBtn = '<button title="Edit" type="button" data-toggle="modal" data-target="#modal-add-category" data-id="' . $data->category_id . '" class="edit btn btn-outline-success btn-lg" id="edit">Edit</button> <form id="form_delete_data" style="display:inline" class="" action="/category/delete/' . $data->category_id . '" method="post" title="Delete"><button title="Hapus" type="submit"  class="btn btn-outline-danger btn-lg" onclick="sweetConfirm(' . $data->category_id . ')">Hapus</button><input type="hidden" name="_method" value="delete" /><input type="hidden" name="_token" value="' . csrf_token() . '"></form>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('categories.index');
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
        $reqData = $request->all();
        $validate = Validator::make(
            $reqData,
            [
                'category_name' => 'required|unique:categories,category_name',
            ],
        );

        if ($validate->fails()) {
            return response()->json([
                'error' => $validate->errors()->toArray()
            ]);
        }

        Categorie::create($reqData);
        return response()->json([
            "error" => false,
            "message" => "Berhasil tambah kategori!",
        ]);
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
        $categories = Categorie::find($id);

        if ($categories) {
            return response()->json(["error" => false, "data" => $categories]);
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
                'category_name' => 'required|unique:categories,category_name',
            ]
        );

        if ($validate->fails()) {
            return response()->json([
                "error" => $validate->errors()->toArray()
            ]);
        }

        $categories = Categorie::findOrFail($id);
        $categories->update($reqData);

        if ($categories) {
            return response()->json(["error" => false, "message" => "Berhasil update kategori! "]);
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
            Categorie::where('category_id', $id)->delete();
        } catch (Exception $e) {
            return response()->json(["error" => true, "message" => $e->getMessage()]);
        }

        return response()->json(["error" => false, "message" => "Berhasil hapus kategori!"]);
    }
}
