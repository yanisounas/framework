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

    <form method="post">
        <input type="text" placeholder="username" name="username">
        <input type="text" placeholder="email" name="email">
        <input type="text" placeholder="password" name="password">
        <input type="submit" value="sub">
    </form>
</body>
</html>