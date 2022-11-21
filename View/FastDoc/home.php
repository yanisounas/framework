<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="./FastDoc/assets/css/style.css">
    <title>FastDocumentation</title>
</head>
<body>
    <h1>Documentation</h1>
    <?php
        foreach ($apis as $appName => $endpoints)
        {
    ?>
                <h1><?= $appName ?></h1>
                <table>
                    <tr>
                        <th>Route</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Method</th>
                    </tr>
                    <?php
                        foreach ($endpoints as $route => $endpoint)
                        {
                    ?>
                            <tr>
                                <td><?= $route ?></td>
                                <td><?= $endpoint->getEndpointName() ?></td>
                                <td><?= $endpoint->getDescription() ?></td>
                                <td><?= $endpoint->getMethod() ?></td>
                            </tr>
                    <?php
                        }
                    ?>

                </table>
    <?php
        }
    ?>
</body>
</html>