<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Industry Supervisor Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-success">
    <div class="container">
        <a class="navbar-brand" href="#">Industry Supervisor Dashboard</a>
        <form method="POST" action="{{ route('logout') }}" class="d-flex ms-auto">
            @csrf
            <button class="btn btn-outline-light">Logout</button>
        </form>
    </div>
</nav>
<div class="container mt-5">
    <div class="alert alert-success">
        Welcome, <strong>{{ auth()->user()->name }}</strong>!<br>
        You are logged in as <strong>Industry Supervisor</strong>.
    </div>
    <p>This is your industry supervisor dashboard. Add your features here.</p>
</div>
</body>
</html>