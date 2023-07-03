<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Sale;
use App\Models\DetailSale;
use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;

class SaleDetailController extends Controller
{
    public function index()
    {
        $produk = Product::join('categories', 'categories.category_id', '=', 'products.category_id')
            ->where('category_name', '!=', 'Barang')
            ->orderBy('product_name')->get();
        $member = Member::orderBy('member_name')->get();
        $diskon = Setting::first()->discount ?? 0;

        if ($sale_id = session('sale_id')) {
            $penjualan = Sale::find($sale_id);
            $memberSelected = $penjualan->member ?? new Member();

            return view('sale-detail.index', compact('produk', 'member', 'diskon', 'sale_id', 'penjualan', 'memberSelected'));
        } else {
            if (auth()->user()->role == User::rAdmin) {
                return redirect()->route('transaction.new');
            } else {
                return redirect('dashboard');
            }
        }
    }

    public function data($id)
    {
        $detail = DetailSale::with('produk')
            ->where('sale_id', $id)
            ->get();

        $data = array();
        $total = 0;
        $total_item = 0;
        foreach ($detail as $item) {
            $row = array();
            $row['product_code'] = '
            <span class="badge badge-dark">' . $item->produk['product_code'] . '</span';
            $row['product_name'] = $item->produk['product_name'];
            $row['selling_price'] = 'Rp. ' . moneyFormat($item->selling_price);
            $row['amount'] = '<input type="number" class="form-control input-sm quantity" data-id="' . $item->detail_sale_id . '" value="' . $item->amount . '">';
            $row['discount'] = $item->discount . '%';
            $row['subtotal'] = 'Rp. ' . moneyFormat($item->subtotal);
            $row['action'] = '<form id="form_delete_data" style="display:inline" class="" action="/transaction/delete/' . $item->detail_sale_id . '" method="post" title="Delete"><button title="Hapus" type="submit"  class="btn btn-outline-danger btn-lg" onclick="sweetConfirm(' . $item->detail_sale_id . ')">Hapus</button><input type="hidden" name="_method" value="delete" /><input type="hidden" name="_token" value="' . csrf_token() . '"></form>';
            $data[] = $row;
            $total += $item->selling_price * $item->amount - (($item->discount * $item->amount) / 100 * $item->selling_price);;
            $total_item += $item->amount;
        }

        $data[] = [
            'product_code' => '
            <div class="total hide  d-none">' . $total . '</div>
            <div class="total_item hide  d-none">' . $total_item . '</div>',
            'product_name' => '',
            'selling_price' => '',
            'amount' => '',
            'discount' => '',
            'subtotal' => '',
            'action' => '',
        ];

        return datatables()
            ->of($data)
            ->addIndexColumn()
            ->rawColumns(['action', 'product_code', 'amount'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $produk = Product::where('product_id', $request->product_id)->first();

        if (!$produk) {
            return response()->json(["error" => true, "message" => "Data gagal disimpan!"]);
        }

        $detail = new DetailSale();
        $detail->sale_id = $request->sale_id;
        $detail->product_id = $produk->product_id;
        $detail->selling_price = $produk->price;
        $detail->amount = 1;
        $detail->discount = $produk->discount;
        $detail->subtotal = $produk->selling_price - ($produk->discount / 100 * $produk->selling_price);;
        $detail->save();
    }

    public function update(Request $request, $id)
    {
        $detail = DetailSale::find($id);
        $detail->amount = $request->amount;
        $detail->subtotal = (int)$detail->selling_price * (int)$request->amount - (((int)$detail->discount * (int)$request->amount) / 100 * (int)$detail->selling_price);;
        $detail->update();
    }

    public function destroy($id)
    {
        $detail = DetailSale::find($id);
        $detail->delete();

        if ($detail) {
            return response()->json(["error" => false, "data" => $detail]);
        } else {
            return response()->json(["error" => false, "message" => "Data tidak ditemukan!"]);
        }
    }

    public function loadForm($diskon = 0, $total = 0, $diterima = 0)
    {
        $bayar   = $total - ($diskon * $total);
        $kembali = ($diterima != 0) ? $diterima - $bayar : 0;
        $data    = [
            'totalrp' => moneyFormat($total),
            'bayar' => $bayar,
            'bayarrp' => moneyFormat($bayar),
            'terbilang' => ucwords(counted($bayar) . ' Rupiah'),
            'kembalirp' => moneyFormat($kembali),
            'kembali_terbilang' => ucwords(counted($kembali) . ' Rupiah'),
        ];

        return response()->json($data);
    }
}
