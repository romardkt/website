@extends('app')

@section('content')

<div class="row">
    <div class="col-xs-12 col-sm-offset-1 col-sm-10">
        <h2 class="page">CUPA Management</h2>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-sm-offset-1 col-sm-2 text-center">
        @include('manage.menu')
    </div>
    <div class="col-xs-12 col-sm-8">
        <legend>Reports</legend>

        <canvas id="userCountChart" width="400" height="200"></canvas>

        <canvas id="leagueCountChart" width="400" height="200"></canvas>

        <canvas id="volunteerCountChart" width="400" height="200"></canvas>

        <canvas id="tournamentCountChart" width="400" height="200"></canvas>

    </div>
</div>
@endsection

@section('page-scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.bundle.min.js"></script>
<script>
    $.get('{{route('manage_reports_data')}}?type=league_counts_by_year').then(function(response) {
        new Chart(document.getElementById("userCountChart"), response);
    });

    $.get('{{route('manage_reports_data')}}?type=leagues_by_year').then(function(response) {
        new Chart(document.getElementById("leagueCountChart"), response);
    });

    $.get('{{route('manage_reports_data')}}?type=volunteers_by_year').then(function(response) {
        new Chart(document.getElementById("volunteerCountChart"), response);
    });

    $.get('{{route('manage_reports_data')}}?type=tournaments_by_year').then(function(response) {
        new Chart(document.getElementById("tournamentCountChart"), response);
    });
</script>
@endsection