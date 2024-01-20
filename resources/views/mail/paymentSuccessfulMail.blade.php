<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Concert Ticket</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
        .ticket-wrap {
            text-align: center;
            margin-top: 2rem;
        }

        .ticket {
            display: inline-block;
            margin: 0 auto;
            border: 2px solid #9facbc;
            font-family: "Variable Bahnschrift", "FF DIN", "Franklin Gothic", "Helvetica Neue", sans-serif;
            font-feature-settings: "kern" 1;
            background: #fff;
        }

        .ticket__header {
            margin: 0;
            padding: 1.5em;
            background: #f4f5f6;
        }

        .ticket__co {
            display: inline-block;
            position: relative;
            padding-left: 5em;
            line-height: 1;
            color: #5e7186;
        }

        .ticket__co-icon {
            position: absolute;
            top: 50%;
            margin-top: -2em;
            left: 0;
            width: 4em;
            height: auto;
        }

        .ticket__co-name {
            font-size: 2.5em;
            font-variation-settings: "wght" 500, "wdth" 75;
            letter-spacing: -.01em;
        }

        .ticket__co-subname {
            font-variation-settings: "wght" 700;
            color: #506072;
        }

        .ticket__body {
            padding: 2rem 1.25em 1.25em;
        }

        .ticket__route {
            font-variation-settings: "wght" 300;
            font-size: 2em;
            line-height: 1.1;
        }

        .ticket__description {
            margin-top: .5em;
            font-variation-settings: "wght" 350;
            font-size: 1.125em;
            color: #506072;
        }

        .ticket__timing {
            display: flex;
            align-items: center;
            margin-top: 1rem;
            padding: 1rem 0;
            border-top: 2px solid #9facbc;
            border-bottom: 2px solid #9facbc;
            text-align: left;
        }

        .ticket__timing p {
            margin: 0 1rem 0 0;
            padding-right: 1rem;
            border-right: 2px solid #9facbc;
            line-height: 1;
        }

        .ticket__timing p:last-child {
            margin: 0;
            padding: 0;
            border-right: 0;
        }

        .ticket__small-label {
            display: block;
            margin-bottom: .5em;
            font-variation-settings: "wght" 300;
            font-size: .875em;
            color: #506072;
        }

        .ticket__detail {
            font-variation-settings: "wght" 700;
            font-size: 1.25em;
            color: #424f5e;
        }

        .ticket__admit {
            margin-top: 2rem;
            font-size: 2.5em;
            font-variation-settings: "wght" 700, "wdth" 85;
            line-height: 1;
            color: #657990;
        }

        .ticket__fine-print {
            margin-top: 1rem;
            font-variation-settings: "wdth" 75;
            color: #666;
        }

        .ticket__barcode {
            margin-top: 1.25em;
            width: 299px;
            max-width: 100%;
        }

        @media (min-width: 36em) {
            .ticket-wrap {
                margin-bottom: 4em;
                text-align: center;
            }

            .ticket {
                margin: 0 auto;
                transform: rotate(6deg);
            }

            .ticket__header {
                margin: 0;
                padding: 2em;
            }

            .ticket__body {
                padding: 3rem 2em 2em;
            }

            .ticket__detail {
                font-size: 1.75em;
            }

            .ticket__admit {
                margin-top: 2rem;
            }
        }

        @media (min-width: 72em) {

            .ticket-info,
            .ticket-wrap {
                justify-content: center;
            }

            .ticket-wrap {
                order: 2;
                margin-bottom: 0;
            }

            .ticket-info {
                order: 1;
            }

        }
    </style>
</head>

<body>
    @php
        foreach ($match as $footballMatch) {
            $matchName = $footballMatch->home_team . ' - ' . $footballMatch->away_team;
            $matchDate = $footballMatch->date_time;
            $matchCompetition = $footballMatch->competition_name;
            $imageHome = $footballMatch->emblem_home;
            $imageAway = $footballMatch->emblem_away;
        }
       
    @endphp
    <div class="container">
        <div class="row justify-content-center">
            <div class="ticket-wrap">
                <div class="ticket">
                    <div class="ticket__header">
                        <div class="ticket__co">
                            <svg class="ticket__co-icon" xmlns="http://www.w3.org/2000/svg" width="64"
                                height="64">
                            </svg>
                            <span class="ticket__co-name">Ticket</span>
                            <span class="u-upper ticket__co-subname">New experience</span>
                        </div>
                    </div>
                    <div class="ticket__body">
                        <p class="ticket__route">{{ $matchName}}</p>
                        <p class="ticket__description">{{$matchCompetition}} football match</p>
                        <div class="ticket__timing">
                            <p>
                                <span class="u-upper ticket__small-label">Date</span>
                                <span class="ticket__detail">{{$matchDate}}</span>
                            </p>
                            <p>
                                <span class="u-upper ticket__small-label">Name</span>
                                <span class="ticket__detail">{{$order->name}}</span>
                            </p>
                        </div>
                        <div class="ticket__timing">
                            <p>
                                <span class="u-upper ticket__small-label">Seat Row</span>
                                @foreach ($orderItem as $data)
                                <span class="ticket__detail">{{$data->seat_name}}</span>
                                @endforeach
                            </p>
                        </div>
                        <p class="ticket__fine-print">This ticket cannot be transferred to another voyage</p>
                        <p class="u-upper ticket__admit">Admit one adult</p>
                        <img class="ticket__barcode"
                            src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/515428/barcode.png" alt="Fake barcode" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
