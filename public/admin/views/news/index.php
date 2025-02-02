<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista Aktualności</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f9;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .news-list-container {
            margin: 20px auto;
            max-width: 1200px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        .news-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .news-header button {
            background-color: #007BFF;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
        }
        .news-header button:hover {
            background-color: #0056b3;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #007BFF;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .status {
            padding: 5px 10px;
            border-radius: 3px;
            font-size: 12px;
        }
        .status.published {
            background-color: #28a745;
            color: white;
        }
        .status.draft {
            background-color: #ffc107;
            color: black;
        }
        .status.scheduled {
            background-color: #17a2b8;
            color: white;
        }
        .actions a {
            text-decoration: none;
            color: white;
            margin: 0 5px;
            padding: 5px 10px;
            border-radius: 3px;
        }
        .actions .edit {
            background-color: #007BFF;
        }
        .actions .publish {
            background-color: #28a745;
        }
        .actions .freeze {
            background-color: #ffc107;
            color: black;
        }
        .actions .delete {
            background-color: #dc3545;
        }
        .actions a:hover {
            opacity: 0.8;
        }
    </style>
</head>
<body>
    <h1>Lista Aktualności</h1>
    <div class="news-list-container">
        <div class="news-header">
            <h2>Aktualności</h2>
            <button onclick="location.href='/admin/news/create'">Dodaj Aktualność</button>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Tytuł</th>
                    <th>Data Utworzenia</th>
                    <th>Utworzył</th>
                    <th>Zaakceptował</th>
                    <th>Data Publikacji</th>
                    <th>Status</th>
                    <th>Akcje</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($newsList as $news): ?>
                    <tr>
                        <td><?= htmlspecialchars($news['title'] ?? 'Brak tytułu'); ?></td>
                        <td><?= htmlspecialchars($news['created_at'] ?? 'Nieznana'); ?></td>
                        <td><?= htmlspecialchars($news['created_by_user'] ?? 'Nieznany'); ?></td>
                        <td><?= htmlspecialchars($news['accepted_by_user'] ?? 'Brak'); ?></td>
                        <td><?= htmlspecialchars($news['publish_date'] ?? 'Brak daty'); ?></td>
                        <td>
                            <span class="status <?= htmlspecialchars($news['status']); ?>">
                                <?= htmlspecialchars(ucfirst($news['status'])); ?>
                            </span>
                        </td>
                        <td class="actions">
                            <a href="/admin/news/edit/<?= $news['id']; ?>" class="edit">Edytuj</a>
                            <?php if ($_SESSION['user']['role'] === 'Admin' || $_SESSION['user']['role'] === 'Editor'): ?>
                                <a href="/admin/news/toggle-status/<?= $news['id']; ?>" class="publish">
                                    <?= $news['status'] === 'published' ? 'Ukryj' : 'Publikuj'; ?>
                                </a>
                                <a href="/admin/news/toggle-freeze/<?= $news['id']; ?>" class="freeze">
                                    Zamroź
                                </a>
                            <?php endif; ?>
                            <?php if ($_SESSION['user']['role'] === 'Admin'): ?>
                                <a href="/admin/news/delete/<?= $news['id']; ?>" class="delete" onclick="return confirm('Czy na pewno chcesz usunąć tę aktualność?');">
                                    Usuń
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
