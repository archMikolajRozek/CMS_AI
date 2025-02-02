<!DOCTYPE html>
<html>
<head>
    <title>Usuń Podstronę</title>
</head>
<body>
    <h1>Usuń Podstronę</h1>
    <p>Czy na pewno chcesz usunąć podstronę "<?= htmlspecialchars($page['title']); ?>"?</p>

    <form method="POST" action="/admin/pages/delete/<?= htmlspecialchars($page['id']); ?>">
        <button type="submit">Tak, usuń</button>
        <a href="/admin/pages">Anuluj</a>
    </form>
</body>
</html>
