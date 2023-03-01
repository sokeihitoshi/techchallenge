<style>
table, th, td {
  border: 1px solid;
}
</style>
<head>
    <meta charset="utf-8">
    <title>Results Page</title>
</head>
<body>
    <h1>Crawling Results:</h1>
    <p>Pages Crawled: {{ $pages }}</p>
    <p>Unique Images: {{ $imgCount }}</p>
    <p>Unique Internal Links: {{ $iLinks }}</p>
    <p>Unique External Links: {{ $xLinks }}</p>
    <p>Average Page Load (Seconds): {{ $loadTime }}</p>
    <p>Average Word Count: {{ $wordCountAvg }}</p>
    <p>Average Title Length: {{ $titleLengthAvg }}</p>
    <table>
        <tr>
            <th>URL</th>
            <th>Response Code</th>
        </tr>
        @foreach ($results as $result )
        <tr>
            <td>{{ $result['url'] }}</td>
            <td>{{ $result['code'] }}</td>
        </tr>
        @endforeach
    </table>
    <a href="{{ route('start') }}">Back</a>
</body>
</html>