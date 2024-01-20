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
                                <form method="POST" action="{{ route('admin.update-matches') }}"
                                    id="competition-filter-form">
                                    @csrf
                                    <label for="competition" style="color: black;">Select Competition:</label>
                                    <select name="competition" id="competition" style="color: black;">
                                        <option value="all" {{ $selectedCompetition == 'all' ? 'selected' : '' }}>All
                                        </option>
                                        @foreach ($competitions as $competition)
                                            <option value="{{ $competition }}"
                                                {{ $competition == $selectedCompetition ? 'selected' : '' }}
                                                style="color: black;">
                                                {{ $competition }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <label for="not-played" style="color: black; margin-left: 20px;">Not Played:</label>
                                    <input type="checkbox" name="not_played" id="not-played"
                                        {{ $notPlayedChecked ? 'checked' : '' }} style="margin-left: 5px;">
                                    <button type="submit" class="btn btn-success"
                                        style="color: white; background-color: green">Filter</button>
                                    <button type="reset" class="btn btn-danger"
                                        style="color: white; background-color: rgb(128, 0, 0)">Reset</button>
                                </form>

                                <table class="table table-bordered" id="table-product">
                                    <thead>
                                        <tr><a href="{{ route('admin.matches.restorematch') }}" class="btn btn-primary">Thêm
                                                lại dữ liệu(nếu đã xóa vĩnh viễn)</a>
                                        </tr>
                                        <tr>
                                            <th>#</th>
                                            <th>Match Id</th>
                                            <th>Name</th>
                                            <th>Score</th>
                                            <th>Date time</th>
                                            <th>Competition</th>
                                            <th>Created At</th>
                                            <th>Deleted At</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($matches as $index => $match)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $match->match_id }}</td>
                                                <td>
                                                    {{ $match->home_team }} <img class="team-emblem"
                                                        src="{{ $match->emblem_home }}" alt="">
                                                    vs
                                                    {{ $match->away_team }} <img class="team-emblem"
                                                        src="{{ $match->emblem_away }}" alt="">
                                                </td>
                                                <td>
                                                    @php
                                                        $result = json_decode($match->result);
                                                    @endphp
                                                    @if ($result)
                                                        @if ($result->points_team1 !== null || $result->points_team2 !== null)
                                                            {{ $result->points_team1 ?? 0 }} -
                                                            {{ $result->points_team2 ?? 0 }}
                                                        @else
                                                            0 - 0
                                                        @endif
                                                    @endif
                                                </td>
                                                <td>{{ $match->date_time }} UTC</td>
                                                <td>{{ $match->competition_name }}</td>
                                                <td>{{ $match->created_at }}</td>
                                                <td>{{ $match->deleted_at }}</td>
                                                <td style="display: flex; gap: 8px;">
                                                    <a style="text-decoration: none;" class="btn btn-primary"
                                                        href="{{ route('admin.matches.detail', ['id' => $match->match_id]) }}">Detail</a>

                                                    <form
                                                        action="{{ route('admin.matches.destroy', ['id' => $match->match_id]) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('delete')
                                                        <button onclick="return confirm('Are you sure ?');" type="submit"
                                                            class="btn btn-danger"
                                                            style=" background-color: #dc3545; border-color: #dc3545;">Delete</button>
                                                    </form>

                                                    @if ($match->trashed())
                                                        <form
                                                            action="{{ route('admin.matches.restore', ['id' => $match->match_id]) }}"
                                                            method="POST">
                                                            @csrf
                                                            <button type="submit" class="btn btn-success"
                                                                style=" background-color: #28a745; border-color: #28a745;">Restore</button>
                                                        </form>

                                                        <form
                                                            action="{{ route('admin.matches.force.delete', ['id' => $match->match_id]) }}"
                                                            method="POST">
                                                            @csrf
                                                            <button type="submit" class="btn btn-warning"
                                                                style=" background-color: #ffc107; border-color: #ffc107;">Force
                                                                Delete</button>
                                                        </form>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4">No data</td>
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
                                {{-- {{ $matches->links() }} --}}
                            </div>


                        </div>
                        <!-- /.card -->
                    </div>
                </div>
                <form action="{{ route('admin.update-matches') }}" method="POST">
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
