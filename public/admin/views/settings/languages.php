<!DOCTYPE html>
<html>
<head>
    <title>Zarządzanie Językami</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body>
    <h1>Zarządzanie Językami</h1>
    <table>
        <thead>
            <tr>
                <th>Nazwa</th>
                <th>Kod</th>
                <th>Status</th>
                <th>Główny język</th>
                <th>Akcje</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($languages as $language): ?>
                <tr>
                    <td><?= htmlspecialchars($language['name']); ?></td>
                    <td><?= htmlspecialchars($language['code']); ?></td>
                    <td><?= htmlspecialchars($language['status']); ?></td>
                    <td>
                        <?php if ($language['is_default']): ?>
                            <strong>Tak</strong>
                        <?php else: ?>
                            Nie
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if (!$language['is_default']): ?>
                            <a href="/admin/settings/languages/<?= $language['id']; ?>/set-default">Ustaw jako główny</a> |
                        <?php endif; ?>
                        <?php if ($language['status'] === 'active'): ?>
                            <a href="/admin/settings/languages/<?= $language['id']; ?>/freeze">Zamroź</a> |
                        <?php elseif ($language['status'] === 'frozen'): ?>
                            <a href="/admin/settings/languages/<?= $language['id']; ?>/activate">Aktywuj</a> |
                        <?php endif; ?>
                        <a href="/admin/settings/languages/<?= $language['id']; ?>/delete" onclick="return confirm('Na pewno usunąć?')">Usuń</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <form method="POST" action="/admin/settings/languages/add">
        <h2>Dodaj nowy język</h2>
        <label>Nazwa: <input type="text" name="name" required></label><br>
        <label>Kod: <input type="text" name="code" required></label><br>
        <button type="submit">Dodaj</button>
    </form>
</body>
</html>
