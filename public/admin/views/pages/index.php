<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista Podstron</title>
</head>
<body>
    <h1>Lista Podstron</h1>

    <!-- Przycisk dodawania nowej podstrony -->
    <a href="/admin/pages/create" class="btn btn-primary">Dodaj nową podstronę</a>

    <!-- Lista podstron -->
    <?php if (!empty($pages)): ?>
        <table>
            <thead>
                <tr>
                    <th>Tytuł</th>
                    <th>Slug</th>
                    <th>Status</th>
                    <th>Data utworzenia</th>
                    <th>Akcje</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pages as $page): ?>
                    <tr>
                        <td><?= htmlspecialchars($page['title']); ?></td>
                        <td><?= htmlspecialchars($page['slug']); ?></td>
                        <td><?= $page['status'] === 'published' ? 'Opublikowana' : 'Szkic'; ?></td>
                        <td><?= htmlspecialchars($page['created_at']); ?></td>
                        <td>
                            <a href="/admin/pages/edit/<?= htmlspecialchars($page['id']); ?>">Edytuj</a>
                            <a href="/admin/pages/toggle-status/<?= htmlspecialchars($page['id']); ?>">
                                <?= $page['status'] === 'published' ? 'Ukryj' : 'Publikuj'; ?>
                            </a>
                            <a href="/admin/pages/delete/<?= htmlspecialchars($page['id']); ?>" onclick="return confirm('Czy na pewno chcesz usunąć tę podstronę?');">Usuń</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Brak podstron do wyświetlenia.</p>
    <?php endif; ?>
</body>
</html>