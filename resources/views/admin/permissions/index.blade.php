
@extends('layouts.admin')
@section('title',"Agregar permiso")
@section('library')
    @include('cdn.datatables-head')
@endsection
@section('content')
@can('CREAR_PERMISOS')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-back  border btn-radius" href="{{ route('admin.permissions.create') }}">
                <i class="fas fa-plus"></i> Agregar permiso
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        <i class="fas fa-tasks"></i> Lista de premisos
    </div>

    <div class="card-body">
        <div class="">
            <table class="table table-sm table-bordered table-hover datatable-Permission w-100">
                <thead>
                    <tr>
                        <th>
                            Permiso
                        </th>
                        <th style="width: 120px">
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>                    
                </tbody>
            </table>
        </div>
    </div>
</div>



@endsection
@section('scripts')
@parent
<script>
    $(function () {
        
    let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)

            $.extend(true, $.fn.dataTable.defaults, {                
                orderCellsTop: true,
                order: [
                    [1, 'asc']
                ],                    
                pageLength: 10,
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.permissions.index') }}",
                dataType: 'json',
                type: "POST",
                columns: [
                    {
                        data: 'title',
                        name: 'title',
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        searcheable:false,
                        orderable: false,
                        className:'text-center'
                    }
                ],  
            });
    
    let table = $('.datatable-Permission:not(.ajaxTable)').DataTable({ buttons: dtButtons })

    $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
        $($.fn.dataTable.tables(true)).DataTable()
            .columns.adjust();
    });
  
})

</script>
@endsection