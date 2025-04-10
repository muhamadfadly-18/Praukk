@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Dashboard</h3>
            </div>
            <div class="card-body">
                <p>Selamat datang di Admin Panel!</p>
                
                <!-- Chart 1: Line Chart -->
                <div id="chart-line"></div>

                <!-- Chart 2: Polar Area Chart (Diletakkan di kiri bawah) -->
                <div id="chart-container">
                    <div id="chart-polar"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<style>
    #chart-container {
        display: flex;
        justify-content: flex-start; /* Menyusun ke kiri */
        margin-top: 20px; /* Jarak dari chart pertama */
    }

    #chart-polar {
        width: 300px; /* Ukuran lebih kecil agar proporsional */
        height: 300px;
    }
</style>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    var namaProduk = @json($namaProduk);
    var jumlahKeluar = @json($jumlahKeluar);
</script>

<script>
  // Chart 1: Line Chart (Forecast/Jumlah Keluar)
var optionsLine = {
    series: [{
        name: 'Produk Keluar',
        data: jumlahKeluar
    }],
    chart: {
        height: 350,
        type: 'line',
    },
    stroke: {
        width: 5,
        curve: 'smooth'
    },
    xaxis: {
        categories: namaProduk, // X axis pakai nama produk
        tickAmount: 10,
        labels: {
            rotate: -45
        }
    },
    title: {
        text: 'Grafik Produk Keluar Hari Ini (Line)',
        align: 'left',
        style: {
            fontSize: "16px",
            color: '#666'
        }
    },
    fill: {
        type: 'gradient',
        gradient: {
            shade: 'dark',
            gradientToColors: ['#FDD835'],
            shadeIntensity: 1,
            type: 'horizontal',
            opacityFrom: 1,
            opacityTo: 1,
            stops: [0, 100, 100, 100]
        },
    }
};


    var chartLine = new ApexCharts(document.querySelector("#chart-line"), optionsLine);
    chartLine.render();

    // Chart 2: Polar Area Chart (Diletakkan di kiri bawah)
   // Chart 2: Polar Area Chart
var optionsPolar = {
    series: jumlahKeluar,
    labels: namaProduk, // <-- ini wajib supaya tahu nama produknya
    chart: {
        type: 'polarArea',
        width: 300,
        height: 300
    },
    stroke: {
        colors: ['#fff']
    },
    fill: {
        opacity: 0.8
    },
    responsive: [{
        breakpoint: 480,
        options: {
            chart: {
                width: 250,
                height: 250
            },
            legend: {
                position: 'bottom'
            }
        }
    }]
};


    var chartPolar = new ApexCharts(document.querySelector("#chart-polar"), optionsPolar);
    chartPolar.render();




    @if(session('success'))
        Swal.fire({
            title: "Berhasil!",
            text: "{{ session('success') }}",
            icon: "success",
            confirmButtonText: "OK"
        });
    @endif

    @if(session('error'))
        Swal.fire({
            title: "Gagal!",
            text: "{{ session('error') }}",
            icon: "error",
            confirmButtonText: "OK"
        });
    @endif


</script>
@endpush
