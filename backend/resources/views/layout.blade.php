<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">  
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite('resources/css/style.css')  
    <title>index</title>
</head>
<body>
    <header>
        <a href="/" class="btn" style="background-color:#DBA67A; color:#fff; font-weight:bold;">
            Aplus
        </a>
    </header>
    

    <main>
        @yield('content')
    </main>
</body>
</html>