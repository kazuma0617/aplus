<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">  
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    @vite('resources/css/style.css')  
    <title>index</title>
</head>
<body>
    <header class="bg-white shadow-sm">
        <div class="container d-flex align-items-center" style="height: 60px;">
            <!-- ロゴ -->
            <button class="btn" disabled
                    style="background-color:#DBA67A; color:#fff; font-weight:bold; cursor:default;">
                Aplus
            </button>

            <!-- 右側ナビゲーション -->
            <div class="d-flex align-items-center ms-auto gap-3">
                <!-- 検索アイコン -->
                <button class="btn btn-outline-secondary">
                    <i class="bi bi-search"></i>
                </button>

                <!-- マイページアイコン -->
                <button class="btn btn-outline-secondary">
                    <i class="bi bi-person"></i>
                </button>

                <!-- 投稿ボタン -->
                <a href="/create" class="btn" style="background-color:#7A5542; color:#fff; font-weight:bold;">
                    投稿
                </a>

            </div>
        </div>
    </header>




    

    <main class="container py-5" style="max-width: 900px;">
        @yield('content')
    </main>
</body>
</html>