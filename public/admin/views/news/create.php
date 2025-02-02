<!DOCTYPE html>
<html>
<head>
    <title>Dodaj Aktualność</title>
</head>
<body>
    <h1>Dodaj Aktualność</h1>
    <form method="POST" enctype="multipart/form-data">
        <fieldset>
            <legend>Główne informacje</legend>
            <label>Status:
                <select name="status" required>
                    <option value="draft">Szkic</option>
                    <option value="scheduled">Zaplanowane</option>
                </select>
            </label><br>
            <label>Data publikacji:
                <input type="datetime-local" name="scheduled_date">
            </label><br>
            <label>Album galerii:
                <select name="gallery_album_id">
                    <option value="">Brak</option>
                    <?php foreach ($albums as $album): ?>
                        <option value="<?= $album['id']; ?>"><?= htmlspecialchars($album['title']); ?></option>
                    <?php endforeach; ?>
                </select>
            </label><br>
            <label>Miniaturka (obrazek):</label>
                <input type="file" name="main_image" accept="image/*"><br>
            <?php if (!empty($news['main_image'])): ?>
                <div>
                    <label>Podgląd miniaturki:</label><br>
                    <img src="<?= htmlspecialchars(str_replace('/news/', '/news/thumbnails/', $news['main_image'])); ?>" alt="Miniaturka" style="width: 100px; height: auto;">
                </div>
            <?php endif; ?>
        </fieldset>

        <fieldset>
            <legend>Tłumaczenia</legend>
            <?php foreach ($languages as $language): ?>
                <h3><?= htmlspecialchars($language['name']); ?> (<?= htmlspecialchars($language['code']); ?>)</h3>
                <label>Tytuł:
                    <input type="text" name="translations[<?= $language['code']; ?>][title]" <?= $language['is_default'] ? 'required' : ''; ?>>
                </label><br>
                <label>Wstęp:
                    <textarea name="translations[<?= $language['code']; ?>][summary]"></textarea>
                </label><br>
                <label>Treść:
                    <textarea name="translations[<?= $language['code']; ?>][content]" <?= $language['is_default'] ? 'required' : ''; ?>></textarea>
                </label><br>
                <label>Meta Tytuł:
                    <input type="text" name="translations[<?= $language['code']; ?>][meta_title]">
                </label><br>
                <label>Meta Opis:
                    <textarea name="translations[<?= $language['code']; ?>][meta_description]"></textarea>
                </label><br>
                <label>Meta Słowa Kluczowe:
                    <textarea name="translations[<?= $language['code']; ?>][meta_keywords]"></textarea>
                </label>
            <?php endforeach; ?>
        </fieldset>

        <button type="submit">Dodaj Aktualność</button>
    </form>
</body>
</html>