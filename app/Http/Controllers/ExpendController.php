<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expend;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use Exception;

class ExpendController extends Controller
{
    public function __construct()
    {
        $this->middleware('level:admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $expends = Expend::where('status', null)
            ->orderBy('expend_id', 'desc')->get();

        if ($request->ajax()) {
            return DataTables::of($expends)
                ->addIndexColumn()
                ->addColumn('created_at', function ($expends) {
                    return indonesianDate($expends->created_at, false);
                })
                ->addColumn('nominal', function ($expends) {
                    return moneyFormat($expends->nominal);
                })
                ->addColumn('action', function ($data) {
                    $actionBtn = '<button title="Edit" type="button" data-toggle="modal" data-target="#modal-add-expend" data-id="' . $data->expend_id . '" class="edit btn btn-outline-success btn-lg" id="edit">Edit</button>
                    <button title="Hapus" type="button" data-toggle="modal" data-target="#modal-archive-expend" data-id="' . $data->expend_id . '" class="archive btn btn-outline-danger btn-lg" id="archive">Hapus</button>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('expend.index');
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
                'description' => 'required',
                'nominal' => 'required',
            ],
        );

        if ($validate->fails()) {
            return response()->json([
                'error' => $validate->errors()->toArray()
            ]);
        }

        Expend::create($reqData);
        return response()->json([
            "error" => false,
            "message" => "Berhasil tambah pengeluaran!",
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
        $expends = Expend::find($id);

        if ($expends) {
            return response()->json(["error" => false, "data" => $expends]);
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
                'description' => 'required',
                'nominal' => 'required',
            ]
        );

        if ($validate->fails()) {
            return response()->json([
                "error" => $validate->errors()->toArray()
            ]);
        }

        $expends = Expend::findOrFail($id);
        $expends->update($reqData);

        if ($expends) {
            return response()->json(["error" => false, "message" => "Berhasil update Pengeluaran! "]);
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function archive(Request $request, $id)
    {
        $reqData = $request->all();
        $validate = validator::make(
            $reqData,
            [
                'note' => 'required',
            ]
        );

        if ($validate->fails()) {
            return response()->json([
                "error" => $validate->errors()->toArray()
            ]);
        }

        $expends = Expend::findOrFail($id);
        $expends->update([
            'note' => $request->note,
            'status' => 'Archive'
        ]);

        if ($expends) {
            return response()->json(["error" => false, "message" => "Berhasil arsip! "]);
        } else {
            return response()->json(["error" => true, "message" => "Data tidak ditemukan!"]);
        }
    }

    public function indexExpend(Request $request)
    {
        $expends = Expend::whereNotNull('status')
            ->orderBy('expend_id', 'desc')->get();

        if ($request->ajax()) {
            return DataTables::of($expends)
                ->addIndexColumn()
                ->addColumn('created_at', function ($expends) {
                    return indonesianDate($expends->created_at, false);
                })
                ->addColumn('nominal', function ($expends) {
                    return moneyFormat($expends->nominal);
                })
                ->addColumn('action', function ($data) {
                    $actionBtn = '<button title="Note" type="button" data-toggle="modal" data-target="#modal-archive-expend" data-id="' . $data->expend_id . '" class="note btn btn-outline-success btn-lg" id="note">Note</button>
                    <form id="form_pulih_data" style="display:inline" class="" action="/archive-expend/pulih' . $data->expend_id . '" method="post" title="Pulihkan"><button title="Pulihkan" type="submit"  class="btn btn-outline-warning btn-lg" onclick="sweetConfirm(' . $data->expend_id . ')">Pulihkan</button><input type="hidden" name="_method" /><input type="hidden" name="_token" value="' . csrf_token() . '"></form>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('archive.expend');
    }

    public function pulihExpend($id)
    {
        $expends = Expend::findOrFail($id);
        $expends->update([
            'note' => null,
            'status' => null
        ]);

        if ($expends) {
            return response()->json(["error" => false, "message" => "Berhasil memulihkan data! "]);
        } else {
            return response()->json(["error" => true, "message" => "Data tidak ditemukan!"]);
        }
    }
}
