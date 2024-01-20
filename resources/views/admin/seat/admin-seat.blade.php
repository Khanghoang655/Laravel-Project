@extends('admin.layouts.app')
@section('content')

    <body style="background-color: #fff; color: #000; padding: 50px; text-align: center;">
        <section class="content" style="margin: 0 auto; max-width: 500px;">
            <form action="{{ route('admin.seat.store') }}" method="post">
                @csrf
                <table style="width: 100%;" class="table table-bordered" id="table-product">
                    <tr>
                        <td style="width: 20%;">Seat Rows Name A-Z</td>
                        <td>
                            <select name="name" required style="width: 100%; padding: 5px;">
                                @foreach (range('A', 'Z') as $letter)
                                    <option value="{{ $letter }}" {{ old('name') == $letter ? 'selected' : '' }}>
                                        {{ $letter }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Seat number (min:10, max:1000)</td>
                        <td><input type="number" name="seat_number" value="10" min="10" max="1000" required
                                style="width: 100%; padding: 5px;"></td>
                    </tr>
                    <tr>
                        <td>Price for one seat</td>
                        <td>
                            <input type="number" name="price" value="100" min="10" required
                                style="width: 80%; padding: 5px;">
                            <span>$</span>
                        </td>

                    </tr>
                    <tr>
                        <td>Match ID</td>
                        <td>
                            <select name="match_id" required style="width: 100%; padding: 5px;">
                                @forelse ($matches as $match)
                                    @php

                                        $current_time = now()->setTimezone('Asia/Ho_Chi_Minh');
                                        $new_time = $current_time->addMinutes(30);
                                    @endphp
                                    @if ($match->date_time > $new_time)
                                        <option value={{ $match->id }}>{{ $match->home_team }} vs
                                            {{ $match->away_team }}</option>
                                    @endif
                                @empty
                                    <option>Không có trận đấu nào sắp diễn ra</option>
                                @endforelse

                            </select>
                        </td>

                    </tr>
                    <tr>
                        <td colspan="2">
                            <button type="submit"
                                style="width: 100%; background-color: #007BFF; color: #fff; padding: 10px; border: none; border-radius: 5px;">Thêm</button>
                        </td>
                    </tr>
                </table>
            </form>

            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th scope="col">Home Team</th>
                        <th scope="col">Away Team</th>
                        <th scope="col">Date Time</th>
                        <th scope="col">Current Time</th>
                        <th scope="col">Seat Row</th>
                        <th scope="col">Total Seat</th>
                        <th scope="col">Price</th>
                        <th scope="col">Total Price</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($matches as $match)
                        @if ($match->date_time > $current_time || $match->seat > 0)
                            <tr>
                                <td>{{ $match->home_team }}</td>
                                <td>{{ $match->away_team }}</td>
                                <td>{{ $match->date_time }}
                                </td>
                                <td>{{ Carbon\Carbon::now('Asia/Ho_Chi_Minh')->format('d/m/Y H:i') }}</td>
                                <td>
                                    @foreach ($match->seat_rows as $seat_row)
                                        {{ $seat_row->name }}
                                        <br>
                                        @foreach ($seat_row->seats as $seat)
                                            <small>{{ $seat->seat_number }}</small>
                                            <br>
                                        @endforeach
                                    @endforeach
                                </td>

                                <td>{{ $match->seat }}</td>
                                <td>
                                    @forelse ($match->seats as $seat)
                                        {{ $seat->seat_price }}$
                                        @if (!$loop->last)
                                        @endif
                                    @empty
                                    @endforelse
                                </td>
                                <td>
                                    @php
                                        $totalPrice = 0;
                                    @endphp
                                    @foreach ($match->seats as $seat)
                                        @php
                                            $totalPrice += $seat->seat_price;
                                        @endphp
                                    @endforeach
                                    {{ $totalPrice }}$
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        @if (!$match->seat_rows->isEmpty())
                                            @php
                                                $firstSeat = $match->seat_rows->first();
                                            @endphp
                                            <form action="{{ route('admin.seat.force.delete', ['id' => $match->id]) }}"
                                                method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-warning">Force Delete</button>
                                            </form>
                                        @else
                                            <p>No seat_rows available for this match.</p>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>

        </section>
    </body>
@endsection
