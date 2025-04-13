@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    @if (Auth::User()->role == 'admin')
        <div class="row">
            <div class="col-md-12 mt-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Dashboard</h3>
                    </div>
                    <div class="card-body">
                        <p>Selamat datang di Admin Panel!</p>

                        <!-- Chart 1: Line Chart -->
                        <!-- Wrapper untuk kedua chart -->
                        <div id="chart-wrapper">
                            <!-- Bar Chart -->
                            <div id="chart"></div>

                            <!-- Pie Chart (Polar) -->
                            <div id="chart-polar"></div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    @elseif (Auth::user()->role == 'kasir')
        <!-- Tampilkan dashboard kasir -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Dashboard Kasir</h3>
            </div>
            <div class="card-body">
                <p>Jumlah pembelian hari ini: <strong>{{ $jumlahPembelian }}</strong></p>
            </div>
        </div>
    @else
        <h1>Anda tidak memiliki akses ke dashboard ini.</h1>
    @endif
@endsection


@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <style>
        #chart-wrapper {
            display: flex;
            justify-content: space-around;
            align-items: center;
            flex-wrap: wrap;
            /* Biar responsif kalau layar kecil */
            margin-top: 20px;
        }

        #chart {
            width: 500px;
        }

        #chart-polar {
            width: 400px;
            /* Lebih besar */
            height: 400px;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <script>
        // Chart 1: Line Chart (Forecast/Jumlah Keluar)
        var options = {
            series: [{
                name: 'Servings',
                data: @json($pembelians->pluck('total_pembelian')) // Data untuk jumlah pembelian
            }],
            annotations: {
                points: [{
                    x: '2023-04-12', // Tanggal contoh, bisa diubah sesuai dengan kebutuhan
                    seriesIndex: 0,
                    label: {
                        borderColor: '#775DD0',
                        offsetY: 0,
                        style: {
                            color: '#fff',
                            background: '#775DD0',
                        },
                        text: 'This is a date annotation',
                    }
                }]
            },
            chart: {
                height: 350,
                type: 'bar',
            },
            plotOptions: {
                bar: {
                    borderRadius: 10,
                    columnWidth: '50%',
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                width: 0
            },
            grid: {
                row: {
                    colors: ['#fff', '#f2f2f2']
                }
            },
            xaxis: {
                labels: {
                    rotate: -45
                },
                categories: @json($pembelians->pluck('tanggal')) // Data untuk tanggal
                    ,
                tickPlacement: 'on'
            },
            yaxis: {
                title: {
                    text: 'Servings',
                },
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shade: 'light',
                    type: "horizontal",
                    shadeIntensity: 0.25,
                    gradientToColors: undefined,
                    inverseColors: true,
                    opacityFrom: 0.85,
                    opacityTo: 0.85,
                    stops: [50, 0, 100]
                },
            }
        };

        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();
        // Chart 2: Polar Area Chart (Diletakkan di kiri bawah)
        // Chart 2: Polar Area Chart
        // Ambil data yang sudah dikirim dari controller
        var detailPembelains = @json($detailPembelains);

        console.log(detailPembelains);

        // Extract the 'total_qty' values and product names for the chart
        var seriesData = Object.values(detailPembelains).map(function(item) {
            return item.total_qty; // Ambil nilai total_qty untuk digunakan di chart
        });

        var labelsData = Object.values(detailPembelains).map(function(item) {
            return item.produk_name; // Ambil nama produk untuk digunakan sebagai label
        });

        console.log(seriesData);
        console.log(labelsData);

        var options = {
            series: seriesData,
            chart: {
                type: 'polarArea',
            },
            stroke: {
                colors: ['#fff']
            },
            fill: {
                opacity: 0.8
            },
            labels: labelsData, // Menggunakan nama produk sebagai label
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        width: 200
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }]
        };

        var chart = new ApexCharts(document.querySelector("#chart-polar"), options);
        chart.render();




        @if (session('success'))
            Swal.fire({
                title: "Berhasil!",
                text: "{{ session('success') }}",
                icon: "success",
                confirmButtonText: "OK"
            });
        @endif

        @if (session('error'))
            Swal.fire({
                title: "Gagal!",
                text: "{{ session('error') }}",
                icon: "error",
                confirmButtonText: "OK"
            });
        @endif
    </script>
@endpush
