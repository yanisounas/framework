<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Home | Url Shortener</title>
    </head>
    <body>
        <h1>Url shortener</h1>
        <?php
        if (isset($shorten))
        {
            var_dump($shorten);
        }
        ?>
        <form method="post">
            <input type="text" placeholder="Url (https://google.com)" name="url">
            <input type="submit" name="submit_url">
        </form>
    </body>
</html>