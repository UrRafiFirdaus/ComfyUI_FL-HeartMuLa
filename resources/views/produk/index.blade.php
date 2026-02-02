@extends('layouts.master')

@section('title')
    Produk
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Produk</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <div class="btn-group">
                    <button onclick="addForm('{{ route('produk.store') }}')" class="btn btn-success btn-xs btn-flat"><i class="fa fa-plus-circle"></i> Tambah</button>
                    <button onclick="deleteSelected('{{ route('produk.delete_selected') }}')" class="btn btn-danger btn-xs btn-flat"><i class="fa fa-trash"></i> Hapus</button>
                    <button onclick="cetakBarcode('{{ route('produk.cetak_barcode') }}')" class="btn btn-info btn-xs btn-flat"><i class="fa fa-barcode"></i> Cetak Barcode</button>
                </div>
            </div>
            <div class="box-body table-responsive">
                <form action="" method="post" class="form-produk">
                    @csrf
                    <table class="table table-stiped table-bordered">
                        <thead>
                            <th width="5%">
                                <input type="checkbox" name="select_all" id="select_all">
                            </th>
                            <th width="5%">No</th>
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>Kategori</th>
                            <th>Merk</th>
                            <th>Harga Beli</th>
                            <th>Harga Jual</th>
                            <th>Diskon</th>
                            <th>Stok</th>
                            <th width="15%"><i class="fa fa-cog"></i></th>
                        </thead>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>

@includeIf('produk.form')
@endsection

@push('scripts')
<script>
    let table;

    $(function () {
        table = $('.table').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('produk.data') }}',
            },
            columns: [
                {data: 'select_all', searchable: false, sortable: false},
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'kode_produk'},
                {data: 'nama_produk'},
                {data: 'nama_kategori'},
                {data: 'merk'},
                {data: 'harga_beli'},
                {data: 'harga_jual'},
                {data: 'diskon'},
                {data: 'stok'},
                {data: 'aksi', searchable: false, sortable: false},
            ]
        });

        $('#modal-form').validator().on('submit', function (e) {
            if (! e.preventDefault()) {
                $.post($('#modal-form form').attr('action'), $('#modal-form form').serialize())
                    .done((response) => {
                        $('#modal-form').modal('hide');
                        table.ajax.reload();
                    })
                    .fail((errors) => {
                        alert('Produk sudah ada');
                        return;
                    });
            }
        });

        $('[name=select_all]').on('click', function () {
            $(':checkbox').prop('checked', this.checked);
        });
    });

    function addForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Tambah Produk');

        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('post');
        $('#modal-form [name=nama_produk]').focus();
    }

    function editForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Edit Produk');

        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('put');
        $('#modal-form [name=nama_produk]').focus();

        $.get(url)
            .done((response) => {
                $('#modal-form [name=nama_produk]').val(response.nama_produk);
                $('#modal-form [name=kode_produk]').val(response.kode_produk);
                $('#modal-form [name=id_kategori]').val(response.id_kategori);
                $('#modal-form [name=merk]').val(response.merk);
                $('#modal-form [name=harga_beli]').val(response.harga_beli);
                $('#modal-form [name=harga_jual]').val(response.harga_jual);
                $('#modal-form [name=diskon]').val(response.diskon);
                $('#modal-form [name=stok]').val(response.stok);
            })
            .fail((errors) => {
                alert('Tidak dapat menampilkan data');
                return;
            });
    }

    function deleteData(url) {
    Swal.fire({
        title: 'Yakin ingin menghapus data ini?',
        text: "Data yang telah dihapus tidak dapat dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post(url, {
                '_token': $('[name=csrf-token]').attr('content'),
                '_method': 'delete'
            })
            .done((response) => {
                Swal.fire(
                    'Terhapus!',
                    'Data berhasil dihapus.',
                    'success'
                );
                table.ajax.reload();
            })
            .fail((errors) => {
                Swal.fire(
                    'Gagal!',
                    'Tidak dapat menghapus data.',
                    'error'
                );
            });
        }
    });
}


function deleteSelected(url) {
    let checkedInputs = $('.form-produk input:checkbox:checked');

    if (checkedInputs.length > 0) {
        Swal.fire({
            title: 'Yakin ingin menghapus produk terpilih?',
            text: "Data yang telah dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post(url, $('.form-produk').serialize())
                    .done((response) => {
                        Swal.fire(
                            'Terhapus!',
                            'Produk berhasil dihapus.',
                            'success'
                        );
                        table.ajax.reload();
                    })
                    .fail((errors) => {
                        Swal.fire(
                            'Gagal!',
                            'Tidak dapat menghapus data.',
                            'error'
                        );
                    });
            }
        });
    } else {
        // Tampilkan pesan jika tidak ada elemen yang dipilih
        Swal.fire(
            'Tidak ada yang dipilih',
            'Pilih produk yang akan dihapus',
            'info'
        );
    }
}


function cetakBarcode(url) {
    var checkedCount = $('input[name="id_produk[]"]:checked').length;
    if (checkedCount < 1) {
        // Tampilkan notifikasi menggunakan SweetAlert2 jika tidak ada produk yang dipilih
        Swal.fire({
            title: 'Tidak ada yang dipilih',
            text: 'Pilih data yang akan dicetak',
            icon: 'info'
        });
        return;
    } else {
        // Tampilkan konfirmasi menggunakan SweetAlert2
        Swal.fire({
            title: 'Cetak Barcode',
            text: 'Yakin ingin mencetak barcode untuk produk terpilih?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, cetak!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Set form target dan action, lalu submit form jika pengguna menekan tombol "Ya, cetak!"
                $('.form-produk')
                    .attr('target', '_blank')
                    .attr('action', url)
                    .submit();
            }
        });
    }
}


</script>
@endpush