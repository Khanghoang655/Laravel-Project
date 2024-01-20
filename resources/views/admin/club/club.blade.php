<!-- resources/views/admin/club/club.blade.php -->

@extends('admin.layouts.app')

@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                @if (session('msg'))
                    <div class="alert alert-success" role="alert">
                        {{ session('msg') }}
                    </div>
                @endif
            </div><!-- /.container-fluid -->
        </div>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Competition</h3>
                                <a href="{{route('admin.update-competition')}}" class="btn btn-primary btn-lg mx-3">back</a>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                @if (isset($clubs) && count($clubs) > 0)
                                    <table class="table table-bordered" id="table-product">
                                        <thead>
                                            <tr>
                                                <th>STT</th>
                                                <th>ID</th>
                                                <th>Name</th>
                                                <th>Image</th>
                                                <th>Founded</th>
                                                <th>Coach</th>
                                                <th>Squad</th>
                                                <th>Website</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($clubs as $index => $club)
                                                @php
                                                    $coachData = json_decode($club->coach, true);
                                                    $squadData = json_decode($club->squad, true);
                                                @endphp
                                                @if ($club->competition_id == request('id') || $club->competition_id == $competitionId)
                                                    <tr>
                                                        <td class="text-center align-middle">{{ $index + 1 }}</td>
                                                        <td class="text-center align-middle">{{ $club->club_id }}</td>
                                                        <td class="text-center align-middle">{{ $club->name }}
                                                            ({{ $club->tla }})
                                                        </td>
                                                        <td class="text-center align-middle"><img src="{{ $club->crest }}"
                                                                class="img-thumbnail" style="max-width: 15%"
                                                                alt="Club Emblem"></td>
                                                        <td class="text-center align-middle">{{ $club->founded }}</td>
                                                        <td class="text-center align-middle">{{ $coachData['name'] ?? '' }}
                                                        </td>
                                                        <td class="text-center align-middle">
                                                            @if (!empty($squadData))
                                                                {{-- <button aria-expanded="false"
                                                                    class="toggle-squad-info btn btn-link"
                                                                    data-toggle="collapse"
                                                                    data-target="#squadInfo{{ $index }}">
                                                                    Show Squad Info
                                                                </button> --}}

                                                                <button class="btn btn-link" data-toggle="collapse" 
                                                                data-target="#squadInfo{{ $index }}" aria-expanded="true" aria-controls="collapseOne">
                                                                    Show Squad Info
                                                                  </button>
                                                                <div id="squadInfo{{ $index }}" class="collapse" aria-labelledby="headingTwo">
                                                                    @foreach ($squadData as $player)
                                                                        <p>
                                                                            {{ $player['name'] ?? '' }}, vị trí:
                                                                            {{ $player['position'] ?? '' }}</p>
                                                                    @endforeach
                                                                </div>
                                                            @else
                                                                <p>No squad information available</p>
                                                            @endif
                                                        </td>
                                                        <td class="text-center align-middle">
                                                            <a href="{{ $club->website }}" target="_blank">Club Website</a>
                                                        </td>
                                                    </tr>
                                                @endif
                                            @empty
                                                <tr>
                                                    <td class="text-center align-middle" colspan="9">No data</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                @else
                                    <p>No data available. Please filter to see the results.</p>
                                @endif

                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
