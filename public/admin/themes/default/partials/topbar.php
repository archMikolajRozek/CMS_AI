<div class="topbar">
    <div>
        Panel Administracyjny - CMS4Everyone
    </div>
    <div>
        <?php echo date('Y-m-d H:i:s'); ?>
        | <a href="/admin/settings">Ustawienia</a>
        | <a href="/admin/users">Moje Konto</a>
    </div>
    <header>
        <h1>Witaj, <?php echo htmlspecialchars($_SESSION['user']['username']); ?>!</h1>
        <p>Twoja rola: <?php echo htmlspecialchars($_SESSION['user']['role']); ?></p>
        <a href="/admin/logout" style="color: red;">Wyloguj się</a>
    </header>
</div>
