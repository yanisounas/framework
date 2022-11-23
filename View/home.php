<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home</title>
</head>
<body>
    <h1>Hello World</h1>
    <table>
        <thead>
            <tr>
                <th>id</th>
                <th>username</th>
                <th>email</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user) { ?>
            <tr>
                <td><?= $user->id ?></td>
                <td><?= $user->username ?></td>
                <td><?= $user->email ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>

    <a href="/login">Login</a>
    <a href="/register">Register</a>
</body>
</html>