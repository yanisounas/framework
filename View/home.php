{% base "base.php" %}

{% block "title" %}URLShortener | Home{% endblock %}

{% block "css" %}
<link rel="stylesheet" href="./assets/css/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
{% endblock %}

{% block "js" %}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="./assets/js/app.js"></script>
{% endblock %}

{% block "body" %}
<div class="overlay"></div>
<div class="container">
    <header class="container_header">
        <div class="dots"></div>
        <div class="account_menu">
            <?php
            if (!isset($_SESSION["username"])): ?>
                <img src="./assets/img/user_default.png" alt="User Image">
            <?php else: ?>
                <img src="https://xsgames.co/randomusers/assets/avatars/female/43.jpg" alt="User Image">
            <?php endif; ?>
        </div>
    </header>

    <main class="container_body">
        <aside class="side_menu">
            <ul class="side_menu__list">
                <li class="app_title">URL Shortener</li>
                <li class="menu_list__item active" id="home"><i class="fa-solid fa-house fa-thin"></i> Home</li>
                <li class="menu_list__item" id="api"><i class="fa-solid fa-book-open fa-thin"></i> API</li>
                <li class="menu_list__item" id="settings"><i class="fa-solid fa-gear fa-thin"></i> Settings</li>
            </ul>
        </aside>

        <div class="app">
            <section class="home">
                <div class="app_header">
                    <form method="POST" class="shortener_form">
                        <input type="text" id="url" name="url" placeholder="Lorem ipsum" class="form_input" autocomplete="off" <?= (is_null($url)) ? '' : "value='$url'"; ?>>
                    </form>
                </div>
                <div class="app_body">
                    <?php if (!isset($_SESSION["username"])): ?>
                        <div class="app_body__error">
                            <p class="body_error__text">You must be logged in to access this feature</p>
                            <a class="body_error__button" href="<?= Framework\Router\Router::getPathFrom("account.login") ?>">Login</a>
                        </div>
                    <?php else: ?>
                        <h1>Todo: links table</h1>
                    <?php endif; ?>
                </div>
            </section>
            <section class="api">
                <h1>api</h1>
            </section>
            <section class="settings">
                <div class="settings_category">
                    <h2 class="category_title">Account Setting</h2>
                    <div class="category_body">
                        <form action="/settings/account">
                            <input type="submit" value="dij">
                        </form>
                    </div>
                </div>
                <div class="settings_category">
                    <h2 class="category_title">Security</h2>
                    <div class="category_body">

                    </div>
                </div>
            </section>
        </div>

    </main>
</div>


{% endblock %}