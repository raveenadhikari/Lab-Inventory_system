<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
</head>

<body>
    <h1>Welcome to the Lab Inventory Management System</h1>
    <p>Hello, <?= esc(session()->get('username')) ?>!</p>

    <p>Your role: <?= esc($role) ?></p>

    <a href="/logout">Logout</a>
</body>

</html>