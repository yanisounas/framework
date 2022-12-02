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
            <?php if (!isset($_SESSION["username"])): ?>
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
                            <button class="body_error__button">Login</button>
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
                    <h2 class="category_title">Basic Settings</h2>
                    <div class="category_body">

                    </div>
                </div>
                <div class="settings_category">
                    <h2 class="category_title">Basic Settings</h2>
                    <div class="category_body">

                    </div>
                </div>
                <div class="settings_category">
                    <h2 class="category_title">Basic Settings</h2>
                    <div class="category_body">

                    </div>
                </div>
                <div class="settings_category">
                    <h2 class="category_title">Basic Settings</h2>
                    <div class="category_body">

                    </div>
                </div>
                <div class="settings_category">
                    <h2 class="category_title">Basic Settings</h2>
                    <div class="category_body">

                    </div>
                </div>
                <div class="settings_category">
                    <h2 class="category_title">Basic Settings</h2>
                    <div class="category_body">

                    </div>
                </div>
            </section>
        </div>

    </main>
</div>

<div class="login_register">
    <div class="login">
        <h2 class="form_title">Login</h2>

        <form method="POST" class="login_register__form form_login">
            <input type="hidden" name="action" value="login">
            <div class="form_group">
                <label for="username_login">Username</label>
                <input type="text" class="form_input" id="username_login" name="username_login">
            </div>

            <div class="form_group">
                <label for="password_login">Password</label>
                <input type="password" class="form_input" id="password_login" name="password_login">
            </div>
            <div class="form_group">
                <input type="submit" class="btn" value="Login">
            </div>
        </form>

        <div class="other">
            <div class="line">Or</div>
            <button class="btn">Create a new account</button>
        </div>
    </div>
    <div class="register">
        <i class="fa-solid fa-arrow-left fa-lg"></i>
        <h2 class="form_title">Register</h2>

        <form method="POST" class="login_register__form form_register">
            <input type="hidden" name="action" value="register">

            <div class="form_group">
                <label for="username_register">Username</label>
                <input type="text" class="form_input" id="username_register" name="username_register">
            </div>

            <div class="form_group">
                <label for="password_register">Password</label>
                <input type="password" class="form_input" id="password_register" name="password_register">
            </div>
            <div class="form_group">
                <label for="confirm_register">Confirm Password</label>
                <input type="password" class="form_input" id="confirm_register" name="confirm_register">
            </div>
            <div class="form_group">
                <input type="submit" class="btn" value="Register">
            </div>
        </form>
    </div>
</div>

{% endblock %}