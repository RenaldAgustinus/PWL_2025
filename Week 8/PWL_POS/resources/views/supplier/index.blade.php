@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <button onclick="modalAction('{{ url('/supplier/import') }}')" class="btn btn-sm  btn-info mt-1">Import Supplier</button>
                <a href="{{ url('/supplier/export_excel') }}" class="btn btn-sm btn-primary mt-1"><i class="fa "></i>Export Supplier</a>
                <a href="{{ url('/supplier/export_pdf') }}" class="btn btn-sm btn-danger mt-1"><i class="fa fa-file- pdf"></i> Export Supplier PDF</a>
                <button onclick="modalAction('{{ url('/supplier/create_ajax') }}')" class="btn btn-sm btn-success mt-1">Tambah
                    Ajax</button>
            </div>
         </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
             @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            <table class="table table-bordered table-striped table-hover table-sm" id="table_level">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>SupplierKode</th>
                        <th>SupplierNama</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static"
    data-keyboard="false" data-width="75%" aria-hidden="true">
 @endsection
 @push('css')
 @endpush
 @push('js')
    <script>
         function modalAction(url = '') {
                $('#myModal').load(url, function() {
                    $('#myModal').modal('show');
                });
            }
        $(document).ready(function() {
            var dataUser = $('#table_level').DataTable({
                processing: true,
                serverSide: true, // Jika ingin menggunakan server-side processing
                ajax: {
                    "url": "{{ url('supplier/list') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": function(d) {
                        d.supplier_id = $('#supplier_id').val();
                    }
                },
                columns: [{
                        data: "DT_RowIndex",
                        className: "text-center",
                        orderable: false,
                        searchable: false
                    }, // Kolom nomor urut
                    {
                        data: "supplier_kode",
                        className: "",
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "supplier_nama",
                        className: "",
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "aksi",
                        className: "",
                        orderable: false,
                        searchable: false
                    } // Tombol aksi
                ]
            });
        });
    </script>
@endpush 