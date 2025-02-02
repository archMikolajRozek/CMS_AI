<!DOCTYPE html>
<html>
<head>
    <title>Dodaj Podstronę</title>
</head>
<body>
    <h1>Dodaj Nową Podstronę</h1>

    <?php if (!empty($errors)): ?>
        <ul class="errors">
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error); ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form method="POST" action="/admin/pages/create">
        <label>
            Tytuł:
            <input type="text" name="title" value="<?= htmlspecialchars($_POST['title'] ?? ''); ?>" required>
        </label><br>

        <label>
            Slug (URL):
            <input type="text" name="slug" value="<?= htmlspecialchars($_POST['slug'] ?? ''); ?>" required>
        </label><br>

        <label>
            Zawartość:
            <textarea name="content" required><?= htmlspecialchars($_POST['content'] ?? ''); ?></textarea>
        </label><br>

        <label>
            Język:
            <select name="language_code">
                <option value="en" <?= ($_POST['language_code'] ?? '') === 'en' ? 'selected' : ''; ?>>English</option>
                <option value="pl" <?= ($_POST['language_code'] ?? '') === 'pl' ? 'selected' : ''; ?>>Polski</option>
                <!-- Dodaj inne języki -->
            </select>
        </label><br>

        <label>
            Status:
            <select name="status">
                <option value="draft" <?= ($_POST['status'] ?? '') === 'draft' ? 'selected' : ''; ?>>Szkic</option>
                <option value="published" <?= ($_POST['status'] ?? '') === 'published' ? 'selected' : ''; ?>>Opublikowany</option>
            </select>
        </label><br>

        <label>
            Album Galerii:
            <select name="gallery_album_id">
                <option value="">Brak</option>
                <?php foreach ($albums as $album): ?>
                    <option value="<?= htmlspecialchars($album['id']); ?>" <?= ($_POST['gallery_album_id'] ?? '') == $album['id'] ? 'selected' : ''; ?>>
                        <?= htmlspecialchars($album['title']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label><br>

        <button type="submit">Dodaj Podstronę</button>
    </form>
</body>
</html>