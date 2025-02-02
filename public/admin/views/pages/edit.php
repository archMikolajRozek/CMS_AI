<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edytuj Podstronę</title>
</head>
<body>
    <h1>Edytuj Podstronę</h1>

    <?php if (!empty($errors)): ?>
        <ul class="errors">
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error); ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form method="POST" action="/admin/pages/edit/<?= htmlspecialchars($page['id']); ?>">
        <label>
            Tytuł:
            <input type="text" name="title" value="<?= htmlspecialchars($_POST['title'] ?? $page['title']); ?>" required>
        </label><br>

        <label>
            Slug (URL):
            <input type="text" name="slug" value="<?= htmlspecialchars($_POST['slug'] ?? $page['slug']); ?>" required>
        </label><br>

        <label>
            Zawartość:
            <textarea name="content" required><?= htmlspecialchars($_POST['content'] ?? $page['content']); ?></textarea>
        </label><br>

        <label>
            Język:
            <select name="language_code">
                <option value="en" <?= ($_POST['language_code'] ?? $page['language_code']) === 'en' ? 'selected' : ''; ?>>English</option>
                <option value="pl" <?= ($_POST['language_code'] ?? $page['language_code']) === 'pl' ? 'selected' : ''; ?>>Polski</option>
                <!-- Dodaj inne języki -->
            </select>
        </label><br>

        <label>
            Status:
            <select name="status">
                <option value="draft" <?= ($_POST['status'] ?? $page['status']) === 'draft' ? 'selected' : ''; ?>>Szkic</option>
                <option value="published" <?= ($_POST['status'] ?? $page['status']) === 'published' ? 'selected' : ''; ?>>Opublikowany</option>
            </select>
        </label><br>

        <label>
            Album Galerii:
            <select name="gallery_album_id">
                <option value="">Brak</option>
                <?php foreach ($albums as $album): ?>
                    <option value="<?= htmlspecialchars($album['id']); ?>" <?= ($_POST['gallery_album_id'] ?? $page['gallery_album_id']) == $album['id'] ? 'selected' : ''; ?>>
                        <?= htmlspecialchars($album['title']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label><br>

        <label>
            Meta Tytuł:
            <input type="text" name="meta_title" value="<?= htmlspecialchars($_POST['meta_title'] ?? $page['meta_title']); ?>">
        </label><br>

        <label>
            Meta Opis:
            <textarea name="meta_description"><?= htmlspecialchars($_POST['meta_description'] ?? $page['meta_description']); ?></textarea>
        </label><br>

        <label>
            Meta Słowa Kluczowe:
            <input type="text" name="meta_keywords" value="<?= htmlspecialchars($_POST['meta_keywords'] ?? $page['meta_keywords']); ?>">
        </label><br>

        <button type="submit">Zapisz Zmiany</button>
    </form>
</body>
</html>