<!DOCTYPE html>
<html>
<head>
    <title>Ustawienia Kontakt</title>
</head>
<body>
    <h1>Ustawienia Kontakt</h1>

    <!-- Formularz do edycji głównych ustawień kontaktu -->
    <form method="POST" action="/admin/contact/save">
        <h2>Główne ustawienia</h2>
        <label>Adres: <textarea name="address"><?= htmlspecialchars($settings['address'] ?? ''); ?></textarea></label><br>
        <label>Telefony (oddziel przecinkami): <textarea name="phone_numbers"><?= htmlspecialchars(implode(', ', json_decode($settings['phone_numbers'] ?? '[]', true) ?: [])); ?></textarea></label><br>
        <label>Godziny pracy (oddziel przecinkami): <textarea name="working_hours"><?= htmlspecialchars(implode(', ', json_decode($settings['working_hours'] ?? '[]', true) ?: [])); ?></textarea></label><br>
        <label>Linki społecznościowe (oddziel przecinkami): <textarea name="social_links"><?= htmlspecialchars(implode(', ', json_decode($settings['social_links'] ?? '[]', true) ?: [])); ?></textarea></label><br>
        <label>Kod iframe mapy: <textarea name="map_iframe"><?= htmlspecialchars($settings['map_iframe'] ?? ''); ?></textarea></label><br>

        <h2>Tłumaczenia</h2>
        <?php foreach ($languages as $language): ?>
            <h3><?= htmlspecialchars($language['name']); ?> (<?= htmlspecialchars($language['code']); ?>)</h3>
            <label>Adres: <textarea name="translations[<?= $language['code']; ?>][address]"><?= htmlspecialchars($translations[$language['code']]['address'] ?? ''); ?></textarea></label><br>
            <label>Godziny pracy: <textarea name="translations[<?= $language['code']; ?>][working_hours]"><?= htmlspecialchars($translations[$language['code']]['working_hours'] ?? ''); ?></textarea></label><br>
            <label>Linki społecznościowe: <textarea name="translations[<?= $language['code']; ?>][social_links]"><?= htmlspecialchars($translations[$language['code']]['social_links'] ?? ''); ?></textarea></label><br>
        <?php endforeach; ?>

        <button type="submit">Zapisz ustawienia</button>
    </form>

    <!-- Formularz do edycji ustawień formularza kontaktowego -->
    <form method="POST" action="/admin/contact/save-form-settings">
        <h2>Ustawienia formularza kontaktowego</h2>
        <label>E-mail odbiorcy: <input type="email" name="email" value="<?= htmlspecialchars($formSettings['email'] ?? ''); ?>" required></label><br>
        <label>Klucz reCAPTCHA: <input type="text" name="recaptcha_key" value="<?= htmlspecialchars($formSettings['recaptcha_key'] ?? ''); ?>"></label><br>
        <label>Sekretny klucz reCAPTCHA: <input type="text" name="recaptcha_secret" value="<?= htmlspecialchars($formSettings['recaptcha_secret'] ?? ''); ?>"></label><br>
        <button type="submit">Zapisz ustawienia formularza</button>
    </form>
</body>
</html>
