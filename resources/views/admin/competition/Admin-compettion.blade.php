@extends('admin.layouts.app')


@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">

                @if (session('msg'))
                    <div class="alert alert-success" role="alert">
                        {{ session('msg') }}
                    </div>
                @endif


            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Product</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table class="table table-bordered" id="table-product">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Competition Id</th>
                                            <th>Logo</th>
                                            <th>Name</th>
                                            <th>Winner</th>
                                            <th>Start time</th>
                                            <th>End time</th>
                                            <th>Created At</th>
                                            <th>Deleted At</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($competition as $index => $value)
                                            <tr>
                                                <td class="text-center align-middle">{{ $loop->iteration }}</td>
                                                <td class="text-center align-middle">{{ $value->id }}</td>
                                                <td class="text-center align-middle"><img src="{{ $value->emblem }}"
                                                        alt="Competition Emblem"></td>
                                                <td class="text-center align-middle">{{ $value->name_of_competition }}
                                                    ({{ $value->short_name }}) </td>
                                                <td class="text-center align-middle">
                                                    @php
                                                        $winner = json_decode($value->winner);
                                                    @endphp
                                                    @if ($winner->name == null || $winner->crest == null)
                                                        In progress
                                                    @else
                                                        {{ $winner->name }} <img  src="{{ $winner->crest }}">
                                                    @endif
                                                </td>
                                                <td class="text-center align-middle">{{ $value->start_date }} UTC</td>
                                                <td class="text-center align-middle">{{ $value->end_date }} UTC</td>
                                                <td class="text-center align-middle">{{ $value->created_at }}</td>
                                                <td class="text-center align-middle">{{ $value->deleted_at }}</td>
                                                <td class="text-center align-middle" style="display: flex; gap: 8px;">
                                                    <a style="text-decoration: none;" class="btn btn-primary"
                                                        href="{{ route('admin.competition.detail',['id' => $value->id])}}">Detail</a>

                                                    <form
                                                        action="{{ route('admin.competition.destroy', ['id' => $value->id]) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('delete')
                                                        <button onclick="return confirm('Are you sure ?');" type="submit"
                                                            class="btn btn-danger"
                                                            style=" background-color: #dc3545; border-color: #dc3545;">Delete</button>
                                                    </form>

                                                    {{-- @if ($value->trashed())
                                                        <form
                                                            action="{{ route('admin.competition.restore', ['id' => $value->match_id]) }}"
                                                            method="POST">
                                                            @csrf
                                                            <button type="submit" class="btn btn-success"
                                                                style=" background-color: #28a745; border-color: #28a745;">Restore</button>
                                                        </form>

                                                        <form
                                                            action="{{ route('admin.competition.force.delete', ['id' => $value->match_id]) }}"
                                                            method="POST">
                                                            @csrf
                                                            <button type="submit" class="btn btn-warning"
                                                                style=" background-color: #ffc107; border-color: #ffc107;">Force
                                                                Delete</button>
                                                        </form>
                                                    @endif --}}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td class="text-center align-middle" colspan="4">No data</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            </tr>

                            <!-- /.card-body -->
                            {<div class="card-footer clearfix">
                                <ul class="pagination pagination-sm m-0 float-right">
                                    <li class="page-item"><a class="page-link" href="#">&laquo;</a></li>
                                    @for ($i = 1; $i <= $totalPage; $i++)
                                        <li class="page-item"><a class="page-link"
                                                href="?page={{ $i }}">{{ $i }}</a></li>
                                    @endfor
                                    <li class="page-item"><a class="page-link" href="#">&raquo;</a></li>
                                </ul>
                                {{-- {{ $valuees->links() }} --}}
                            </div>


                        </div>
                        <!-- /.card -->
                    </div>
                </div>
                <form action="{{ route('admin.update-competition') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary btn-lg">Update</button>
                </form>
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
@endsection

@section('js-custom')
    <script>
        $(document).ready(function() {
            let table = new DataTable('#table-product');
        });
    </script>
@endsection
