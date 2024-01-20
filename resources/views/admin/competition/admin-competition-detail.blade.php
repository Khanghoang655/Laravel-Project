@extends('admin.layouts.app')


@section('content')

    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <!-- general form elements -->
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Competition Create</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            {{ $errors ?? dd($errors->all()) }}
                            <form role="form" method="POST"
                                action="{{ route('admin.competition.update', ['id' => $competition->id]) }}">
                                @csrf
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="name_of_competition">Competition Name</label>
                                        <input value="{{ $competition->name_of_competition }}" type="text"
                                            name="name_of_competition" class="form-control" id="name_of_competition"
                                            placeholder="Enter Competition name">
                                        @error('name_of_competition')
                                            <span style="color: red">{{ $message }}</span>
                                        @enderror
                                        <label for="short_name">Short Name</label>
                                        <input value="{{ $competition->short_name }}" type="text" name="short_name"
                                            class="form-control" id="short_name" placeholder="Enter Short Name">
                                        @error('short_name')
                                            <span style="color: red">{{ $message }}</span>
                                        @enderror
                                        <label for="start_date">Start day</label>
                                        <input value="{{ $competition->start_date }}" type="date" name="start_date"
                                            class="form-control" id="start_date" placeholder="Enter Start date">
                                        @error('start_date')
                                            <span style="color: red">{{ $message }}</span>
                                        @enderror
                                        <label for="end_date">End Date</label>
                                        <input value="{{ $competition->end_date }}" type="date" name="end_date"
                                            class="form-control" id="end_date" placeholder="Enter away team slug">
                                        @error('end_date')
                                            <span style="color: red">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </form>


                        </div>
                        <!-- /.card -->
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('js-custom')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#name_of_competition').on('input', function() {
                updateSlug('name_of_competition', 'short_name');
            });

            $('#start_date').on('input', function() {
                updateSlug('start_date', 'end_date');
            });

            function updateSlug(nameInputId, slugInputId) {
                var nameValue = $('#' + nameInputId).val();
                $.ajax({
                    method: 'POST',
                    url: '{{ route('admin.competition.createSlug') }}',
                    data: {
                        name: nameValue,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        $('#' + slugInputId).val(response.slug);
                    }
                });
            }
        });
    </script>
@endsection
