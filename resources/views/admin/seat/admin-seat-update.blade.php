@extends('admin.layouts.app')


@section('content')

<body style="background-color: #fff; color: #000; padding: 50px; text-align: center;">
    <section class="content" style="margin: 0 auto; max-width: 500px;">
        @if (session('msg'))
        <div class="alert alert-success" role="alert">
            {{ session('msg') }}
        </div>
        @endif
        <form action="{{ route('admin.seat.update', ['id' => $seat->match_id, 'area_name' => $area_name]) }}" method="POST">
            @csrf
            <table style="width: 100%;" class="table table-bordered" id="table-product">
                <tr>
                    <td style="width: 20%;">Area</td>
                    <td>
                        <select name="area_name" required style="width: 100%; padding: 5px;">
                            <option value="Normal" @if($area_name == 'Normal') selected @endif>Normal</option>
                            <option value="VIP" @if($area_name == 'VIP') selected @endif>VIP</option>
                            <option value="Special" @if($area_name == 'Special') selected @endif>Special</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Value</td>
                    
                    <td><input type="number" name="seat_number" value="{{ $seat->seat_number }}" required style="width: 100%; padding: 5px;"></td>
                </tr>
                <tr>
                    <td>Status</td>
                    <td>
                        <select name="status" required style="width: 100%; padding: 5px;">
                            <option value="available">Available</option>
                            <option value="unavailable">Unavailable</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Match ID</td>
                    <td>
                        <select name="match_id" required style="width: 100%; padding: 5px;">
                            <option value="{{ $seat->match_id }}">
                                {{ $seat->footballMatch->home_team }} vs {{ $seat->footballMatch->away_team }}

                            </option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <button type="submit" style="width: 100%; background-color: #007BFF; color: #fff; padding: 10px; border: none; border-radius: 5px;">Thêm</button>
                    </td>
                </tr>
            </table>
        </form>
    </section>
</body>

@endsection
