<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use Illuminate\Http\Request;


class PenjualanController extends Controller
{

    public function create()
    {
        $penjualan = new Penjualan();
        $penjualan->id_member = null;
        $penjualan->total_item = 0;
        $penjualan->total_harga = 0;
        $penjualan->diskon = 0;
        $penjualan->bayar = 0;
        $penjualan->diterima = 0;
        $penjualan->id_user = auth()->id();
        $penjualan->save();

        session(['id_penjualan' => $penjualan->id_penjualan]);
        return redirect()->route('transaksi.index');
    }

    public function store(Request $request) {
        $penjualan = Penjualan::findOrFail($request -> id_penjualan);
        $penjualan -> id_member = $request -> id_member;
        $penjualan -> total_item = $request -> total_item;
        $penjualan -> total_harga = $request -> total_harga;
        $penjualan -> diskon = $request -> diskon;
        $penjualan -> bayar = $request -> bayar;
        $penjualan -> diterima = $request -> diterima;
        $penjualan -> update();

        $detail = PenjualanDetail::where('id_penjualan', $penjualan -> id_penjualan) -> get();
        foreach ($detail as $item) {
            $item -> diskon = $request -> diskon;
            $item -> update();

            $produk = Produk::find($item -> id_produk);
            $produk -> stok -= $item -> jumlah;
            $produk -> update();
        }
        return redirect() -> route('transaksi.selesai');
    }

    public function show() {
        $detail = PenjualanDetail::with('produk')-> where('id_penjualan', id) -> get();

        return dataTables()
        ->of($detail)
        ->addIndexColumn()
        ->addColumn('kode_produk', function ($detail) {
            return '<span class="label label-success">'. $detail->produk->kode_produk .'</span>';
        })
        ->addColumn('nama_produk', function ($detail) {
            return $detail->produk->nama_produk;
        })
        ->addColumn('harga_jual', function ($detail) {
            return 'Rp. '. format_uang($detail->harga_jual);
        })
        ->addColumn('jumlah', function ($detail) {
            return format_uang($detail->jumlah);
        })
        ->addColumn('subtotal', function ($detail) {
            return 'Rp. '. format_uang($detail->subtotal);
        })
        ->rawColumns(['kode_produk'])
        ->make(true);

    }

    public function destroy() {
        $setting = Setting::first();
        return view ('penjualan.selesai', compact('setting'));
    }

    public function notakecil() {
        $setting = Setting::first();
        $penjualan = Penjualan::find(session('id_penjualan'));
        if (! $penjualan) {
            abort(404);
        }
        $detail = PenjualanDetail::with('produk')
            ->where('id_penjualan', session('id_penjualan'))
            ->get();
        
        return view('penjualan.nota_kecil', compact('setting', 'penjualan', 'detail'));
    }

    public function notabesar() {
        $setting = Setting::first();
        $penjualan = Penjualan::find(session('id_penjualan'));
        if (! $penjualan) {
            abort(404);
        }
        $detail = PenjualanDetail::with('produk')
            ->where('id_penjualan', session('id_penjualan'))
            ->get();

        $pdf = PDF::loadView('penjualan.nota_besar', compact('setting', 'penjualan', 'detail'));
        $pdf->setPaper(0,0,609,440, 'potrait');
        return $pdf->stream('Transaksi-'. date('Y-m-d-his') .'.pdf');
    }

    
}
