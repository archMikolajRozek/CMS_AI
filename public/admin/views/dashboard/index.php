<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>
<body>
    <header>
        <h1>Witaj, <?php echo htmlspecialchars($_SESSION['user']['username']); ?>!</h1>
        <p>Twoja rola: <?php echo htmlspecialchars($_SESSION['user']['role']); ?></p>
        <a href="/admin/logout" style="color: red;">Wyloguj się</a>
    </header>
    <main>
        <h2>Aktywne Moduły</h2>
        <ul>
            <?php foreach ($modules as $module): ?>
                <li>
                    <a href="/admin/<?php echo htmlspecialchars($module['slug']); ?>">
                        <?php echo htmlspecialchars($module['name']); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </main>
</body>
</html>
