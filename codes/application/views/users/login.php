<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Mady Bakehouse - Login</title>

        <link rel="shortcut icon" href="/assets/images/organic_shop_favicon.ico" type="image/x-icon" />

        <script src="/assets/js/vendor/jquery.min.js"></script>
        <script src="/assets/js/vendor/popper.min.js"></script>
        <script src="/assets/js/vendor/bootstrap.min.js"></script>
        <script src="/assets/js/vendor/bootstrap-select.min.js"></script>
        <link rel="stylesheet" href="/assets/css/vendor/bootstrap.min.css" />
        <link rel="stylesheet" href="/assets/css/vendor/bootstrap-select.min.css" />

        <script src="/assets/js/global/dashboard.js"></script>
        <link rel="stylesheet" href="/assets/css/custom/global.css" />
        <link rel="stylesheet" href="/assets/css/custom/signup.css" />
        <link rel="stylesheet" href="/assets/css/custom/login.css" />
        <script src="/assets/js/global/login.js"></script>
    </head>
    <body>
        <div class="wrapper">
            <a href=""><img src="/assets/images/main_logo.svg" alt="Mady Bakehouse" /></a>
            <form action="/users/validate_login/" method="post" class="login_form">
                <h2>Login to order.</h2>
                <a href="/users/signup">New Member? Register here.</a>
                <div class="error_div">
                </div>
                <ul>
                    <li>
                        <input type="text" name="email" required value="go.cristopher@gmail.com"/>
                        <label>Email</label>
                    </li>
                    <li>
                        <input type="password" name="password" required value="12345678" />
                        <label>Password</label>
                    </li>
                </ul>
                <button type="submit" class="login_btn">Login</button>
                <input type="hidden" name="action" value="login" />
            </form>
        </div>
    </body>
</html>
