@extends('index')

@section('content')
<div class="container mt-4">
    <h1>Words Cloud</h1>
    
    <div class="d-flex flex-wrap justify-content-around mt-4">
        <!-- Positive Word Cloud Section -->
        <div class="card mb-4 flex-fill mx-2">
            <div class="card-header">
                <h3 class="text-center">Positive </h3>
            </div>
            <div class="card-body">
                <canvas id="positiveWordCloudCanvas" width="200" height="100"></canvas>
            </div>
        </div>

        <!-- Negative  Section -->
        <div class="card mb-4 flex-fill mx-2">
            <div class="card-header">
                <h3 class="text-center">Negative </h3>
            </div>
            <div class="card-body">
                <canvas id="negativeWordCloudCanvas" width="200" height="100"></canvas>
            </div>
        </div>

        <!-- Neutral  Section -->
        <div class="card mb-4 flex-fill mx-2">
            <div class="card-header">
                <h3 class="text-center">Neutral </h3>
            </div>
            <div class="card-body">
                <canvas id="neutralWordCloudCanvas" width="200" height="100"></canvas>
            </div>
        </div>
    </div>

    <h5 style="margin-top:30px; margin-bottom:30px; font-weight: normal;">Kata Kata Yang Sering Muncul</h5>
    <div class="d-flex flex-wrap justify-content-around">
        <!-- Positive Words Section -->
        <div class="card mb-4 flex-fill mx-2">
            <div class="card-header">
                <h3 class="text-center">Positive Words</h3>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Word</th>
                            <th>Occurrences</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($positiveWords as $word => $count)
                        <tr>
                            <td>{{ $word }}</td>
                            <td>{{ $count }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Negative Words Section -->
        <div class="card mb-4 flex-fill mx-2">
            <div class="card-header">
                <h3 class="text-center">Negative Words</h3>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Word</th>
                            <th>Occurrences</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($negativeWords as $word => $count)
                        <tr>
                            <td>{{ $word }}</td>
                            <td>{{ $count }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Neutral Words Section -->
        <div class="card mb-4 flex-fill mx-2">
            <div class="card-header">
                <h3 class="text-center">Neutral Words</h3>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Word</th>
                            <th>Occurrences</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($neutralWords as $word => $count)
                        <tr>
                            <td>{{ $word }}</td>
                            <td>{{ $count }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!--  Section -->
    <!-- Positive  Section -->
   

@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/wordcloud2.js/1.0.6/wordcloud2.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Positive words
    const positiveWords = [
        @foreach ($positiveWords as $word => $count)
        ['{{ $word }}', {{ $count }}],
        @endforeach
    ];

    // Negative words
    const negativeWords = [
        @foreach ($negativeWords as $word => $count)
        ['{{ $word }}', {{ $count }}],
        @endforeach
    ];

    // Neutral words
    const neutralWords = [
        @foreach ($neutralWords as $word => $count)
        ['{{ $word }}', {{ $count }}],
        @endforeach
    ];

    // Log the words arrays to ensure data is correct
    console.log('Positive Words:', positiveWords);
    console.log('Negative Words:', negativeWords);
    console.log('Neutral Words:', neutralWords);

    // Render word clouds with larger font sizes
    WordCloud(document.getElementById('positiveWordCloudCanvas'), { list: positiveWords, weightFactor: 2 });
    WordCloud(document.getElementById('negativeWordCloudCanvas'), { list: negativeWords, weightFactor: 5 });
    WordCloud(document.getElementById('neutralWordCloudCanvas'), { list: neutralWords, weightFactor: 20 });
});
</script>
@endsection
