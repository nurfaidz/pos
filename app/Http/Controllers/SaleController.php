<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\DetailSale;
use App\Models\Member;
use App\Models\Product;
use App\Models\Setting;
use Yajra\DataTables\DataTables;
use PDF;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return view('sales.index');
    }

    public function data()
    {
        $sales = Sale::with('member')->orderBy('sale_id', 'desc')->get();

        return DataTables::of($sales)
            ->addIndexColumn()
            ->addColumn('total_items', function ($sales) {
                return moneyFormat($sales->total_items);
            })
            ->addColumn('total_price', function ($sales) {
                return 'Rp. ' . moneyFormat($sales->total_price);
            })
            ->addColumn('pay', function ($sales) {
                return 'Rp. ' . moneyFormat($sales->pay);
            })
            ->addColumn('date', function ($sales) {
                return indonesianDate($sales->created_at, false);
            })
            ->addColumn('member_code', function ($data) {
                if ($data->member != null) {
                    return '
                            <span class="label label-dark">' . $data->member->member_code . '</span>';
                } else {
                    return '<span>0</span>';
                }
            })
            ->editColumn('discount', function ($sales) {
                return $sales->member->discount_member . '%';
            })
            ->editColumn('kasir', function ($sales) {
                return $sales->user->name ?? '';
            })
            ->addColumn('action', function ($data) {
                $actionBtn = '<button onclick="showDetail(`' . route('sale.show', $data->sale_id) . '`)" class="btn btn-outline-info btn-lg" title="Detail">Detail</button>
                <form id="form_delete_data" style="display:inline" class="" action="/sale/delete/' . $data->sale_id . '" method="post" title="Delete"><button title="Hapus" type="submit"  class="btn btn-outline-danger btn-lg" onclick="sweetConfirm(' . $data->sale_id . ')">Hapus</button><input type="hidden" name="_method" value="delete" /><input type="hidden" name="_token" value="' . csrf_token() . '"></form>';
                return $actionBtn;
            })
            ->rawColumns(['action', 'member_code'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $penjualan = new Sale();
        $penjualan->member_id = null;
        $penjualan->total_items = 0;
        $penjualan->total_price = 0;
        $penjualan->discount = 0;
        $penjualan->pay = 0;
        $penjualan->accepted = 0;
        $penjualan->user_id = auth()->id();
        $penjualan->save();

        session(['sale_id' => $penjualan->sale_id]);
        return redirect()->route('transaction.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $penjualan = Sale::findOrFail($request->sale_id);

        if ($request->member_id != null) {
            $member = Member::findorFail($request->member_id);

            $penjualan->member_id = $request->member_id;
            $penjualan->total_items = $request->total_item;
            $penjualan->total_price = $request->total;
            $penjualan->discount = $member->discount_member;
            $penjualan->pay = $request->bayar;
            $penjualan->accepted = $request->diterima;
            $penjualan->update();
        } else {
            $penjualan->member_id = 0;
            $penjualan->total_items = $request->total_item;
            $penjualan->total_price = $request->total;
            $penjualan->discount = 0;
            $penjualan->pay = $request->bayar;
            $penjualan->accepted = $request->diterima;
            $penjualan->update();
        }

        $detail = DetailSale::where('sale_id', $penjualan->sale_id)->get();
        foreach ($detail as $item) {
            if ($request->member_id != null) {
                $item->discount = $member->discount_member;
                $item->update();
            } else {
                $item->discount = $request->discount;
                $item->update();
            }

            $produk = Product::find($item->product_id);
            // $produk->stock -= $item->amount;
            $produk->update();
        }

        return redirect()->route('transaction.end');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $detail = DetailSale::with('produk')->where('sale_id', $id)->get();

        return DataTables::of($detail)
            ->addIndexColumn()
            ->addColumn('product_code', function ($data) {
                return '
                <span class="badge badge-dark">' . $data->produk->product_code . '</span>';
            })
            ->addColumn('product_name', function ($data) {
                return $data->produk->product_name;
            })
            ->addColumn('selling_price', function ($data) {
                return 'Rp. ' . moneyFormat($data->selling_price);
            })
            ->addColumn('amount', function ($data) {
                return moneyFormat($data->amount);
            })
            ->addColumn('subtotal', function ($data) {
                return 'Rp. ' . moneyFormat($data->subtotal);
            })
            ->rawColumns(['product_code'])
            ->make(true);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $penjualan = Sale::find($id);
        $detail = DetailSale::where('sale_id', $penjualan->sale_id)->get();
        foreach ($detail as $item) {
            $produk = Product::find($item->product_id);
            if ($produk) {
                $produk->stock += $item->amount;
                $produk->update();
            }

            $item->delete();
        }

        $penjualan->delete();

        return response()->json(["error" => false, "message" => "Berhasil hapus produk!"]);
    }

    public function end()
    {
        $setting = Setting::first();
        return view('sales.end', compact('setting'));
    }

    public function smallNote()
    {
        $setting = Setting::first();
        $penjualan = Sale::find(session('sale_id'));
        if (!$penjualan) {
            abort(404);
        }
        $detail = DetailSale::with('produk')->where('sale_id', session('sale_id'))->get();

        return view('sales.small-note', compact('setting', 'penjualan', 'detail'));
    }

    public function bigNote()
    {
        $setting = Setting::first();
        $penjualan = Sale::find(session('sale_id'));
        if (!$penjualan) {
            abort(404);
        }
        $detail = DetailSale::with('produk')->where('sale_id', session('sale_id'))->get();

        $pdf = PDF::loadView('sales.big-note', compact('setting', 'penjualan', 'detail'));
        $pdf->setPaper(0, 0, 609, 440, 'potrait');

        return $pdf->stream('Transaksi-' . date('Y-m-d-his') . '.pdf');
    }
}
