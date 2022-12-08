{% base "base.php" %}

{% block "title" %}URLShortener | Register{% endblock %}

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

<div class="login_register_container">
    <div class="register">
        <a href="/login"><i class="fa-solid fa-arrow-left fa-lg"></i></a>
        <h2 class="form_title">Register</h2>

        <form method="POST" class="login_register__form form_register">

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