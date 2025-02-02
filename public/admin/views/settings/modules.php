<!DOCTYPE html>
<html>
<head>
    <title>Zarządzanie Modułami</title>
</head>
<body>
    <h1>Zarządzanie Modułami</h1>
    <table border="1">
        <thead>
            <tr>
                <th>Nazwa</th>
                <th>Opis</th>
                <th>Status</th>
                <th>Akcje</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($modules as $module): ?>
                <tr>
                    <td><?= htmlspecialchars($module['name']); ?></td>
                    <td><?= htmlspecialchars($module['description']); ?></td>
                    <td><?= htmlspecialchars($module['status']); ?></td>
                    <td>
                        <?php if (!in_array($module['name'], ['Użytkownicy', 'Moduły', 'Ustawienia'])): ?>
                            <?php if ($module['status'] === 'active'): ?>
                                <a href="/admin/settings/modules/<?= $module['id']; ?>/deactivate">Dezaktywuj</a>
                                <a href="/admin/settings/modules/<?= $module['id']; ?>/freeze">Zamroź</a>
                            <?php elseif ($module['status'] === 'inactive'): ?>
                                <a href="/admin/settings/modules/<?= $module['id']; ?>/activate">Aktywuj</a>
                            <?php elseif ($module['status'] === 'frozen'): ?>
                                <a href="/admin/settings/modules/<?= $module['id']; ?>/activate">Odblokuj</a>
                            <?php endif; ?>
                        <?php else: ?>
                            <span>Niedostępne</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
