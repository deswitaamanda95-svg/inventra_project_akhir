<!DOCTYPE html>
<html>
<head>
    <title>Inventra Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background:#f3f4f6">

<nav class="navbar navbar-dark bg-dark px-4">
    <span class="navbar-brand">INVENTRA</span>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button class="btn btn-outline-light btn-sm">Logout</button>
    </form>
</nav>

<div class="container mt-4">
    @yield('content')
</div>

</body>
</html>
