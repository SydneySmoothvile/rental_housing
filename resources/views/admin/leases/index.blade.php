@extends('layouts.admin')
@section('content')
@can('lease_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.leases.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.lease.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.lease.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Lease">
            <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th>
                        {{ trans('cruds.lease.fields.id') }}
                    </th>
                    <th>
                        {{ trans('cruds.lease.fields.application') }}
                    </th>
                    <th>
                        {{ trans('cruds.lease.fields.amount_invoiced') }}
                    </th>
                    <th>
                        {{ trans('cruds.lease.fields.property') }}
                    </th>
                    <th>
                        {{ trans('cruds.lease.fields.unit') }}
                    </th>
                    <th>
                        {{ trans('cruds.lease.fields.paid') }}
                    </th>
                    <th>
                        {{ trans('cruds.lease.fields.paid_at') }}
                    </th>
                    <th>
                        {{ trans('cruds.lease.fields.status') }}
                    </th>
                    <th>
                        &nbsp;
                    </th>
                </tr>
            </thead>
        </table>
    </div>
</div>



@endsection
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('lease_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.leases.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).data(), function (entry) {
          return entry.id
      });

      if (ids.length === 0) {
        alert('{{ trans('global.datatables.zero_selected') }}')

        return
      }

      if (confirm('{{ trans('global.areYouSure') }}')) {
        $.ajax({
          headers: {'x-csrf-token': _token},
          method: 'POST',
          url: config.url,
          data: { ids: ids, _method: 'DELETE' }})
          .done(function () { location.reload() })
      }
    }
  }
  dtButtons.push(deleteButton)
@endcan

  let dtOverrideGlobals = {
    buttons: dtButtons,
    processing: true,
    serverSide: true,
    retrieve: true,
    aaSorting: [],
    ajax: "{{ route('admin.leases.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'id', name: 'id' },
{ data: 'application', name: 'applications.name' },
{ data: 'amount_invoiced_amount', name: 'amount_invoiced.amount' },
{ data: 'property_name', name: 'property.name' },
{ data: 'unit_name', name: 'unit.name' },
{ data: 'paid', name: 'paid' },
{ data: 'paid_at', name: 'paid_at' },
{ data: 'status_status', name: 'status.status' },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  };
  let table = $('.datatable-Lease').DataTable(dtOverrideGlobals);
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
});

</script>
@endsection