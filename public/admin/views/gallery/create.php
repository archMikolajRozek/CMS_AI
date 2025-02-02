<!DOCTYPE html>
<html>
<head>
    <title>Create Gallery</title>
</head>
<body>
    <h1>Create Gallery Item</h1>
    <form method="POST" action="/gallery/create">
        <label>Filename: <input type="text" name="filename" required></label><br>
        <label>Title: <input type="text" name="title" required></label><br>
        <label>Description: <textarea name="description"></textarea></label><br>
        <button type="submit">Save</button>
    </form>
</body>
</html>
