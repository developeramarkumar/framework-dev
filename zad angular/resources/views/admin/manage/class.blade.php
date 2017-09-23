@extends('admin.layout.base')
@section('body')
<section class="content-header">
    <h1> Class List </h1>
      <ol class="breadcrumb">
      <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
      </ol>
</section>
  


@endsection
@push('links')
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css">
@endpush
@push('scripts')
{{--     <script type="text/javascript" src="//cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
    $('#dataTable').DataTable();
});
    </script> --}}
    <script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
@endpush