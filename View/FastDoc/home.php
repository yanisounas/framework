<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="./FastDoc/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="./FastDoc/assets/js/app.js"></script>
    <title>FastDocumentation</title>
</head>
<body>
    <div class="doc-head">
        <h1>Documentation</h1>
        <p>This documentation is auto-generated by MicroFramework</p>
    </div>
    <div class="doc-content">

        <?php foreach ($apis as $appName => $endpoint): ?>
            <h2 class="appName"><?= $appName ?></h2>

            <div class="endpoints">
                <?php $sorted = \Framework\API\Attributes\Endpoint::sortByMethod($endpoint);
                foreach ($sorted as $method => $route):
                    foreach ($route as $routePath => $endpointInstance):
                        $class = "";
                        if (array_key_first(array_slice(reset($sorted), 0, 1)) == $routePath)
                            $class = "first-endpoint";

                        if (array_key_first(array_slice(end($sorted), -1)) == $routePath)
                            $class = "last-endpoint";

                ?>
                    <div class="endpoint">
                        <div class="endpoint-header <?= $class ?>">
                            <h3 class="method <?= strtolower($method) ?>"><?= $method ?></h3>
                            <h3>/<?= $routePath ?></h3>
                            <p><?= $endpointInstance->getDescription() ?></p>
                            <div class="right">
                                <button class="btn outline-<?= strtolower($method) ?>"><i class="fa-solid fa-clipboard"></i> Copy The Path !</button>
                            </div>
                        </div>

                        <div class="endpoint-body">
                            <div class="parameters">
                                <h4 class="p-title">Parameters</h4>
                                <div class="p-body">
                                    <form class="f-params">
                                        <div class="p-section">
                                            <h5 class="param-name">id</h5>
                                            <input type="text" class="p-input" placeholder="id">
                                        </div>
                                        <button class="btn btn-try outline-<?= strtolower($method) ?>">Launch Test</button>
                                    </form>
                                </div>
                            </div>
                            <div class="result">
                                <h4 class="r-title">Result</h4>

                            </div>
                        </div>
                    </div>
                <?php endforeach;endforeach; ?>
            </div>
        <?php endforeach; ?>
        <!--
        <div class="endpoints">
            <div class="endpoint">
                <div class="endpoint-header first-endpoint">
                    <h3 class="method blue">Get</h3>
                    <h3>/endpoint/{id:integer} </h3>
                    <p>A little description (Optionnal)</p>
                    <div class="right">
                        <button class="btn outline-blue"><i class="fa-solid fa-clipboard"></i> Copy The Path !</button>
                    </div>
                </div>

                <div class="endpoint-body">
                    <div class="parameters">
                        <h4 class="p-title">Parameters</h4>
                        <div class="p-body">
                            <form class="f-params">
                                <div class="p-section">
                                    <h5 class="param-name">id</h5>
                                    <input type="text" class="p-input" placeholder="id">
                                </div>
                                <button class="btn btn-try outline-blue">Launch Test</button>
                            </form>
                        </div>
                    </div>
                    <div class="result">
                        <h4 class="r-title">Result</h4>

                    </div>
                </div>
            </div>
            <div class="endpoint">
                <div class="endpoint-header">
                    <h3 class="method green">POST</h3>
                    <h3>/endpoint/{id} </h3>
                    <p>A little description (Optionnal)</p>
                    <div class="right">
                        <button class="btn outline-green"><i class="fa-solid fa-clipboard"></i> Copy The Path !</button>
                    </div>
                </div>

                <div class="endpoint-body">
                    <div class="parameters">
                        <h4 class="p-title">Query Parameters</h4>
                        <div class="p-body">

                        </div>
                    </div>
                </div>
            </div>
            <div class="endpoint">
                <div class="endpoint-header">
                    <h3 class="method red">DELETE</h3>
                    <h3>/endpoint/{id} </h3>
                    <p>A little description (Optionnal)</p>
                    <div class="right">
                        <button class="btn outline-red"><i class="fa-solid fa-clipboard"></i> Copy The Path !</button>
                    </div>
                </div>

                <div class="endpoint-body">
                    <div class="parameters">
                        <h4 class="p-title">Query Parameters</h4>
                        <div class="p-body">

                        </div>
                    </div>
                </div>
            </div>
            <div class="endpoint">
                <div class="endpoint-header last-endpoint">
                    <h3 class="method light-blue">PUT</h3>
                    <h3>/endpoint/{id} </h3>
                    <p>A little description (Optionnal)</p>
                    <div class="right">
                        <button class="btn outline-light-blue"><i class="fa-solid fa-clipboard"></i> Copy The Path !</button>
                    </div>
                </div>

                <div class="endpoint-body">
                    <div class="parameters">
                        <h4 class="p-title">Query Parameters</h4>
                        <div class="p-body">

                        </div>
                    </div>
                </div>
            </div>
        </div>
        -->
    </div>
</body>
</html>