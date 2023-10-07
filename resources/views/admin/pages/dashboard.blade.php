@extends('admin.layout.master')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div id="order-summary" style="width: 900px; height: 500px;"></div>
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
@endsection

@section('js-custom')
    <script type="text/javascript">
        google.charts.load('current', {
            'packages': ['corechart']
        });
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {

            var data = google.visualization.arrayToDataTable(@json($arrayDatas));

            var options = {
                title: 'Order Summary',
                is3D: true,
            };

            var chart = new google.visualization.PieChart(document.getElementById('order-summary'));

            chart.draw(data, options);
        }
    </script>
@endsection
