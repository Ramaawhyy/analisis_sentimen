@extends('index')

@section('content')
<div class="container mt-4">
    <h1 class="text-center mb-4">Grafik Analisis Sentimen</h1>
    
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h4 class="text-center">Bar Chart</h4>
                            <canvas id="barChart"></canvas>
                        </div>
                        <div class="col-md-6">
                            <h4 class="text-center">Pie Chart</h4>
                            <canvas id="pieChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow">
                <div class="card-body">
                    <h4 class="text-center">Detail Ulasan</h4>
                    <table class="table table-striped mt-3">
                        <thead>
                            <tr>
                                <th>Sentimen</th>
                                <th>Jumlah</th>
                                <th>Persentase</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Positif</td>
                                <td>{{ $sentiments['positive'] }}</td>
                                <td>{{ number_format($percentages['positive'], 2) }}%</td>
                            </tr>
                            <tr>
                                <td>Negatif</td>
                                <td>{{ $sentiments['negative'] }}</td>
                                <td>{{ number_format($percentages['negative'], 2) }}%</td>
                            </tr>
                            <tr>
                                <td>Netral</td>
                                <td>{{ $sentiments['neutral'] }}</td>
                                <td>{{ number_format($percentages['neutral'], 2) }}%</td>
                            </tr>
                        </tbody>
                    </table>
                    <p class="text-center mt-4"><strong>Jumlah Total Ulasan: {{ $summary['total_reviews'] }}</strong></p>

                    @php
                        function getSatisfactionLevel($percentage) {
                            if ($percentage >= 0 && $percentage < 25) {
                                return [
                                    'level' => 'Sangat Tidak Puas',
                                    'description' => 'Persentase ini menunjukkan bahwa sebagian besar ulasan wisatawan didominasi sentimen negatif yang kuat. Wisatawan sangat kecewa dengan pengalaman mereka di DIY dan tidak merekomendasikan tempat wisata tersebut kepada orang lain.',
                                    'details' => [
                                        'Fasilitas yang tidak memadai atau tidak terawat',
                                        'Layanan yang buruk atau tidak ramah',
                                        'Kurangnya informasi atau panduan',
                                    ]
                                ];
                            } elseif ($percentage >= 25 && $percentage < 50) {
                                return [
                                    'level' => 'Tidak Puas',
                                    'description' => 'Persentase ini menandakan bahwa ulasan wisatawan masih mengandung sentimen negatif, meskipun tidak sekuat kategori "Sangat Tidak Puas". Wisatawan mungkin cukup kecewa dengan beberapa aspek wisata, namun masih menemukan beberapa hal yang positif.',
                                    'details' => []
                                ];
                            } elseif ($percentage >= 50 && $percentage < 75) {
                                return [
                                    'level' => 'Puas',
                                    'description' => 'Persentase ini menunjukkan bahwa ulasan wisatawan berimbang antara sentimen positif dan negatif. Secara keseluruhan, wisatawan cukup puas dengan pengalaman mereka di DIY, namun masih ada beberapa hal yang perlu diperbaiki.',
                                    'details' => []
                                ];
                            } else {
                                return [
                                    'level' => 'Sangat Puas (Angka 75 % - 100 %)',
                                    'description' => 'Persentase ini menandakan bahwa ulasan wisatawan didominasi sentimen positif yang kuat. Wisatawan sangat puas dengan pengalaman mereka di DIY dan sangat merekomendasikan tempat wisata tersebut kepada orang lain.',
                                    'details' => []
                                ];
                            }
                        }

                        $satisfaction = getSatisfactionLevel($percentages['positive']);
                    @endphp
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                            <th><b>$ Jumlah Skor</b></th>
                            <th><b>Kriteria</b></th>
                        </tr>
                        </thead>
                        <tbody>
                            <tr>
                        <td>20% - 36%</td>
                        <td>Tidak Baik</td>
                        </tr>
                        <tr>
                        <td>36,01% - 52%</td>
                        <td>Kurang Baik</td>
                        </tr>
                        <tr>
                        <td>52,01% - 68%</td>
                        <td>Cukup</td>
                        </tr>
                        <tr>
                        <td>68,01% - 84%</td>
                        <td>Baik</td>
                        </tr>
                        <tr>
                        <td>84,01% - 100%</td>
                        <td>Sangat Baik</td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="mt-4">
                        <h5>Keterangan:</h5>
                        <p><strong>{{ $satisfaction['level'] }}</strong></p>
                        <p>{{ $satisfaction['description'] }}</p>
                        @if (!empty($satisfaction['details']))
                            <ul>
                                @foreach ($satisfaction['details'] as $detail)
                                    <li>{{ $detail }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const barData = {
        labels: ['Positif', 'Negatif', 'Netral'],
        datasets: [{
            label: 'Jumlah Ulasan',
            data: [@json($sentiments['positive']), @json($sentiments['negative']), @json($sentiments['neutral'])],
            backgroundColor: ['rgba(75, 192, 192, 0.7)', 'rgba(255, 99, 132, 0.7)', 'rgba(255, 205, 86, 0.7)'],
            borderColor: ['rgba(75, 192, 192, 1)', 'rgba(255, 99, 132, 1)', 'rgba(255, 205, 86, 1)'],
            borderWidth: 1
        }]
    };

    const maxDataValue = Math.max(...barData.datasets[0].data);
    const maxBarValue = maxDataValue + (maxDataValue * 0.1); // Add 10% headroom above the max data value

    const barOptions = {
        responsive: true,
        plugins: {
            legend: {
                position: 'top',
            },
            tooltip: {
                callbacks: {
                    label: function(tooltipItem) {
                        return tooltipItem.label + ': ' + tooltipItem.raw;
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                max: maxBarValue, // Adjust y-axis to fit the data
                ticks: {
                    stepSize: 1
                }
            }
        }
    };

    const pieData = {
        labels: ['Positif', 'Negatif', 'Netral'],
        datasets: [{
            label: 'Persentase Ulasan',
            data: [@json($percentages['positive']), @json($percentages['negative']), @json($percentages['neutral'])],
            backgroundColor: ['rgba(75, 192, 192, 0.7)', 'rgba(255, 99, 132, 0.7)', 'rgba(255, 205, 86, 0.7)'],
            borderColor: ['rgba(75, 192, 192, 1)', 'rgba(255, 99, 132, 1)', 'rgba(255, 205, 86, 1)'],
            borderWidth: 1
        }]
    };

    const pieOptions = {
        responsive: true,
        plugins: {
            legend: {
                position: 'top',
            },
            tooltip: {
                callbacks: {
                    label: function(tooltipItem) {
                        return tooltipItem.label + ': ' + tooltipItem.raw.toFixed(2) + '%';
                    }
                }
            }
        }
    };

    new Chart(document.getElementById('barChart'), {
        type: 'bar',
        data: barData,
        options: barOptions
    });

    new Chart(document.getElementById('pieChart'), {
        type: 'pie',
        data: pieData,
        options: pieOptions
    });
});
</script>
@endsection
