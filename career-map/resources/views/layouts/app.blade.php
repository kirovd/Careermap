<!DOCTYPE html>
<html>
<head>
    <title>Careermap Test</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>

<div class="container">
    @yield('content')
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
@yield('scripts')

</body>
</html>
