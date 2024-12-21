<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>

<body>
    <h1>Register</h1>
    <?php if (session()->getFlashdata('error')): ?>
        <div style="color: red;">
            <?= implode('<br>', session()->getFlashdata('error')) ?>
        </div>
    <?php endif; ?>

    <form method="post" action="/register">
        <input type="text" name="username" placeholder="Username" required><br>
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button type="submit">Register</button>
    </form>
</body>

</html>