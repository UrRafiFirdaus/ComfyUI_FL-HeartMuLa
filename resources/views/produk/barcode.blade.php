<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cetak Barcode</title>

    <style>
        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>
    <table width="100%">
        <tr>
            @foreach ($dataproduk as $produk)
                <td class="text-center" style="border: 1px solid #333;">
                    <p>{{ $produk->nama_produk }} </p>
                    <p>Rp. {{ format_uang($produk->harga_jual) }}</p>
                    <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($produk->kode_produk, 'C39') }}" 
                        alt="{{ $produk->kode_produk }}"
                        width="180"
                        height="60">
                    <br>
                    {{ $produk->kode_produk }}
                </td>
                @if ($no++ % 3 == 0)
                    </tr><tr>
                @endif
            @endforeach
        </tr>
    </table>
</body>
</html>



<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cetak Barcode</title>

    <style>
        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>
    <table width="100%">
        <tr>
            @foreach ($dataproduk as $produk)
                <td class="text-center" style="border: 1px solid #333; padding: 5px;">
                    <p style="font-size: 60px;">{{ $produk->nama_produk }} <!-- Rp. {{ format_uang($produk->harga_jual) }} --> </p>
<!--                    <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($produk->kode_produk, 'C39') }}" 
                        alt="{{ $produk->kode_produk }}"
                        width="360"
                        height="180">
                    <br>
                    <p style="font-size: 30px;">{{ $produk->kode_produk }} <!-- Rp. {{ format_uang($produk->harga_jual) }} --> </p>
                    
<!--                </td>
                @if ($no++ % 1 == 0)
                    </tr><tr>
                @endif
            @endforeach
        </tr>
    </table>
</body>
</html>