<!DOCTYPE html>
<html>
<head>
    <title>Edytuj Aktualność</title>
</head>
<body>
    <h1>Edytuj Aktualność</h1>
    <form method="POST">
        <label>Status:</label>
        <select name="status">
            <option value="draft" <?= $news['status'] === 'draft' ? 'selected' : ''; ?>>Szkic</option>
            <option value="published" <?= $news['status'] === 'published' ? 'selected' : ''; ?>>Opublikowane</option>
            <option value="scheduled" <?= $news['status'] === 'scheduled' ? 'selected' : ''; ?>>Zaplanowane</option>
        </select>
        <br><br>

        <label>Miniaturka (obrazek):</label>
            <input type="file" name="main_image" accept="image/*">
        <br><br>

        <?php if (!empty($news['main_image'])): ?>
            <div>
                <label>Podgląd miniaturki:</label><br>
                <img src="<?= htmlspecialchars(str_replace('/news/', '/news/thumbnails/', $news['main_image'])); ?>" alt="Miniaturka" style="width: 100px; height: auto;">
            </div>
        <?php endif; ?>
        <br><br>

        <label>Galeria:</label>
        <select name="gallery_album_id">
            <option value="">Brak</option>
            <?php foreach ($albums as $album): ?>
                <option value="<?= $album['id']; ?>" <?= $news['gallery_album_id'] == $album['id'] ? 'selected' : ''; ?>>
                    <?= htmlspecialchars($album['title']); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <br><br>

        <?php foreach ($languages as $language): ?>
            <fieldset>
                <legend><?= htmlspecialchars($language['name']); ?> (<?= htmlspecialchars($language['code']); ?>)</legend>

                <label>Tytuł:</label>
                <input type="text" name="translations[<?= $language['code']; ?>][title]" value="<?= htmlspecialchars($news['translations'][$language['code']]['title'] ?? ''); ?>">
                <br><br>

                <label>Streszczenie:</label>
                <textarea name="translations[<?= $language['code']; ?>][summary]"><?= htmlspecialchars($news['translations'][$language['code']]['summary'] ?? ''); ?></textarea>
                <br><br>

                <label>Treść:</label>
                <textarea name="translations[<?= $language['code']; ?>][content]"><?= htmlspecialchars($news['translations'][$language['code']]['content'] ?? ''); ?></textarea>
                <br><br>

                <label>Meta Tytuł:</label>
                <input type="text" name="translations[<?= $language['code']; ?>][meta_title]" value="<?= htmlspecialchars($news['translations'][$language['code']]['meta_title'] ?? ''); ?>">
                <br><br>

                <label>Meta Opis:</label>
                <textarea name="translations[<?= $language['code']; ?>][meta_description]"><?= htmlspecialchars($news['translations'][$language['code']]['meta_description'] ?? ''); ?></textarea>
                <br><br>

                <label>Słowa Kluczowe:</label>
                <input type="text" name="translations[<?= $language['code']; ?>][meta_keywords]" value="<?= htmlspecialchars($news['translations'][$language['code']]['meta_keywords'] ?? ''); ?>">
            </fieldset>
        <?php endforeach; ?>

        <button type="submit">Zapisz</button>
    </form>
</body>
</html>
