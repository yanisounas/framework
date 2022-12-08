{% base "base.php" %}

{% block "title" %}URLShortener | Login{% endblock %}

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
<?php
if (isset($errors) && !empty($errors))
{
    ?>
    <div class="alert alert-danger">
        <div class="close"><i class="fa-solid fa-xmark"></i></div>
        <p class="alert-message"><i class="fa-solid fa-triangle-exclamation"></i>
            <?php
            foreach ($errors as $error)
                echo $error;
            ?></p>
    </div>
    <?php
}
?>
<div class="login_register_container">
    <div class="login">
        <h2 class="form_title">Login</h2>

        <form method="POST" class="login_register__form form_login">
            <div class="form_group">
                <label for="username">Username</label>
                <input type="text" class="form_input" id="username" name="username">
            </div>

            <div class="form_group">
                <label for="password">Password</label>
                <input type="password" class="form_input" id="password" name="password">
            </div>
            <div class="form_group">
                <input type="submit" class="btn" value="Login" name="submit">
            </div>
        </form>

        <div class="other">
            <div class="line">Or</div>
            <a class="btn" href="/register">Create a new account</a>
        </div>
    </div>
</div>

{% endblock %}