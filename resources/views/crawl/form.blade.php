<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>Laravel</title>
</head>
<body>
        <form method="post" action="{{ route('crawl') }}">
            <!-- CROSS Site Request Forgery Protection -->
            @csrf
            <div class="form-group">
                <label>URL #1: https://agencyanalytics.com/</label>
                <input type="text" name="url[]">
            </div>
            <div class="form-group">
                <label>URL #2: https://agencyanalytics.com/</label>
                <input type="text" name="url[]">
            </div>
            <div class="form-group">
                <label>URL #3: https://agencyanalytics.com/</label>
                <input type="text" name="url[]">
            </div>
            <div class="form-group">
                <label>URL #4: https://agencyanalytics.com/</label>
                <input type="text" name="url[]">
            </div>
            <input type="submit" value="Crawl">
        </form>
</body>
</html>