@extends('layouts.master')

@section('title')
    Kategori
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Kategori</li>
@endsection

@section('content')
<!-- Small boxes (Stat box) -->
<!-- Main row -->
<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border">
                <button onclick="addForm('{{ route('kategori.store') }}')" class="btn btn-success btn-xs btn-flat">
                    <i class="fa fa-plus-circle"></i> Tambah
                </button>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kategori</th>
                            <th width="15%"><i class="fa fa-cog"></i></th>
                        </tr>
                    </thead>
                </table>
            </div>
            <!-- /.box-footer -->
        </div>
        <!-- /.box -->
    </div>
    <!-- /.col -->
</div>
<!-- /.row (main row) -->

@includeif('kategori.form')

@push('scripts')
<script>
    let table;
    $(function() {
        table = $('.table').DataTable({
            processing: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('kategori.data') }}',
            },
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'nama_kategori'},
                {data: 'aksi', searchable: false, sortable: false}
            ]
        });

        $('#modal-form').validator().on('submit', function (e) {
            if (! e.preventDefault()) {
                $.post($('#modal-form').attr('action'), $('#modal-form form').serialize())
                .done((response) => {
                    $('#modal-form').modal('hide');
                    table.ajax.reload(); 
                })
                .fail((errors) => {
                    alert('Kategori sudah ada');
                });
            }
        });
    });

    function addForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Tambah Kategori');
        
        $('#modal-form form')[0].reset();
        $('#modal-form').attr('action', url);
        $('#modal-form [name=_method]').val('post');
        $('#modal-form [name=nama_kategori]').focus();
    }

    function editForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Edit Kategori');
        
        $('#modal-form form')[0].reset();
        $('#modal-form').attr('action', url);
        $('#modal-form [name=_method]').val('put');
        $('#modal-form [name=nama_kategori]').focus();
        
        $.get(url)
            .done((response) => {
                $('#modal-form [name=nama_kategori]').val(response.nama_kategori);
            })
            .fail((errors) => {
                alert('Tidak dapat menampilkan data!');
                return;
            })
    }

    function deleteData(url) {
    Swal.fire({
        title: 'Yakin ingin menghapus kategori ini?',
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
</script>
@endpush
@endsection

