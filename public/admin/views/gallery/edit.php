<!DOCTYPE html>
<html>
<head>
    <title>Edit Gallery</title>
</head>
<body>
    <h1>Edit Gallery Item</h1>
    <form method="POST" action="/gallery/edit?id=<?php echo $_GET['id']; ?>">
        <label>Filename: <input type="text" name="filename" value="Mock Filename" required></label><br>
        <label>Title: <input type="text" name="title" value="Mock Title" required></label><br>
        <label>Description: <textarea name="description">Mock Description</textarea></label><br>
        <button type="submit">Update</button>
    </form>
</body>
</html>
