<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\Setting;
use PDF;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use Exception;

class MemberController extends Controller
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
        $members = Member::orderBy('member_code')->get();

        if ($request->ajax()) {
            return DataTables::of($members)
                ->addIndexColumn()
                ->addColumn('select_all', function ($data) {
                    return '
                    <input type="checkbox" name="member_id[]" value="' . $data->member_id . '">';
                })
                ->addColumn('member_code', function ($data) {
                    return '
                    <span class="badge badge-dark">' . $data->member_code . '</span>';
                })
                ->addColumn('action', function ($data) {
                    $actionBtn = '<button title="Edit" type="button" data-toggle="modal" data-target="#modal-form" data-id="' . $data->member_id . '" class="edit btn btn-outline-success btn-lg" id="edit">Edit</button> <form id="form_delete_data" style="display:inline" class="" action="/member/delete/' . $data->member_id . '" method="post" title="Delete"><button title="Hapus" type="submit"  class="btn btn-outline-danger btn-lg" onclick="sweetConfirm(' . $data->member_id . ')">Hapus</button><input type="hidden" name="_method" value="delete" /><input type="hidden" name="_token" value="' . csrf_token() . '"></form>';
                    return $actionBtn;
                })
                ->rawColumns(['action', 'member_code', 'select_all'])
                ->make(true);
        }
        return view('members.index');
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
        $members = Member::latest()->first() ?? new Member();
        $data['member_code'] = addNullAtForward((int)$members->member_id + 1, 5);

        $validate = Validator::make(
            $data,
            [
                'member_name' => 'required',
            ]
        );

        if ($validate->fails()) {
            return response()->json([
                'error' => $validate->errors()->toArray()
            ]);
        }

        if ($request->status == 2) {
            $members = Member::create([
                'member_code' => $data['member_code'],
                'member_name' => $data['member_name'],
                'email' => $data['email'],
                'discount_member' => 0.02,
                'phone' => $data['phone'],
                'status' => $data['status'],
            ]);
        } else {
            $members = Member::create($data);
        }


        if ($members) {
            return response()->json(["error" => false, "message" => "Berhasil tambah member!"]);
        } else {
            return response()->json(["error" => true, "message" => "Gagal tambah member"]);
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
        $members = Member::find($id);

        if ($members) {
            return response()->json(["error" => false, "data" => $members]);
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
                'member_name' => 'required',
            ]
        );

        if ($validate->fails()) {
            return response()->json([
                "error" => $validate->errors()->toArray()
            ]);
        }

        $members = Member::findOrFail($id);
        $members->update($reqData);

        if ($members) {
            return response()->json(["error" => false, "message" => "Berhasil update member! "]);
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
            Member::where('member_id', $id)->delete();
        } catch (Exception $e) {
            return response()->json(["error" => true, "message" => $e->getMessage()]);
        }

        return response()->json(["error" => false, "message" => "Berhasil hapus member!"]);
    }

    public function cetakMember(Request $request)
    {
        $datamember = collect(array());
        foreach ($request->member_id as $id) {
            $member = Member::find($id);
            $datamember[] = $member;
        }

        $datamember = $datamember->chunk(2);
        $setting    = Setting::first();

        $no  = 1;
        $pdf = PDF::loadView('members.card-member', compact('datamember', 'no', 'setting'));
        $pdf->setPaper(array(0, 0, 566.93, 850.39), 'potrait');
        return $pdf->stream('member.pdf');
    }
}
