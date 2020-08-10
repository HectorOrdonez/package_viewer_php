<!DOCTYPE html>
<html lang="eng">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>AgriPlace Assignment</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <style>
        body {
            color: #5a5a5a;
        }

        header {
            padding-bottom: 1em;
        }
    </style>
</head>
<body>
<div class="">
    <header>
        <nav class="navbar navbar-dark bg-dark">
            <a class="navbar-brand" href="#">AgriPlace Assignment</a>
        </nav>
    </header>

    <div class="container">
        <h4>Packages</h4>
        <ul>
            @forelse($packageNames as $packageName)
                <li><a href="{{ url('packages/show/' . $packageName) }}">{{ $packageName }}</a></li>
            @empty
                <li>No packages!</li>
            @endforelse
        </ul>
    </div>
</div>
</body>
</html>
