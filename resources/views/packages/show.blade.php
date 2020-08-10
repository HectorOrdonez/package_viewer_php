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

        .description {
            padding-bottom: 1em;
        }
    </style>
</head>
<body>
<div class="">
    <header>
        <nav class="navbar navbar-dark bg-dark">
            <a class="navbar-brand" href="{{ url('/') }}">AgriPlace Assignment</a>
        </nav>
    </header>

    <div class="container">
        <h1>Package: {{ $package['name'] }}</h1>
        <div class="description panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Description</h3>
            </div>
            <div class="panel-body">
                {{ $package['description'] }}
            </div>
        </div>

        @if(count($package['dependencies']) == 0)
            <div class="alert alert-info" role="alert">
                This package has no dependencies
            </div>
        @else
            <div class="dependencies panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title ">Dependencies</h3>
                </div>
                <div class="panel-body">
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">Name</th>
                            <th scope="col">Reference</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($package['dependencies'] as $dependency)
                            <tr>
                                <td>{{ $dependency }}</td>
                                <td><a href="bla">link</a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        @endif
    </div>
</div>
</body>
</html>
