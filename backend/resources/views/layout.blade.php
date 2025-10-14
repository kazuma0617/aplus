<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/style.css')    
    <title>index</title>
</head>
<body>
    <header>
        <h1>Aplus</h1>
    </header>

    <main>
        @yield('content')
    </main>
</body>
</html>