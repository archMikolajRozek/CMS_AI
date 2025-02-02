<!DOCTYPE html>
<html>
<head>
    <title>Edit User</title>
</head>
<body>
    <h1>Edit User</h1>
    <form method="POST" action="/users/edit?id=<?php echo $_GET['id']; ?>">
        <label>Username: <input type="text" name="username" value="Mock User" required></label><br>
        <label>Email: <input type="email" name="email" value="mock@example.com" required></label><br>
        <button type="submit">Update</button>
    </form>
</body>
</html>
