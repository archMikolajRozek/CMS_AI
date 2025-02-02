<!DOCTYPE html>
<html>
<head>
    <title>Wersja systemu</title>
</head>
<body>
    <h1>Wersja systemu</h1>
    <p>Aktualna wersja: <?= htmlspecialchars($version); ?></p>
    <form method="POST" action="/admin/settings/version/update">
        <label>Nowa wersja: <input type="text" name="version" required></label>
        <button type="submit">Zaktualizuj</button>
    </form>
</body>
</html>
