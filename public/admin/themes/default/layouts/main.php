<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/admin/themes/default/css/admin.css">
    <script src="/admin/themes/default/js/admin.js" defer></script>
    <title>Panel Administracyjny</title>
</head>
<body>
    <?php include __DIR__ . '/../partials/topbar.php'; ?>
    <div class="layout">
        <?php include __DIR__ . '/../partials/sidebar.php'; ?>
        <div class="content">
            <?php echo $content; ?>
        </div>
    </div>
</body>
</html>