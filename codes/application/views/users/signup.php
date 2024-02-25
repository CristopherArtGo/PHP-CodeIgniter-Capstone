<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Mady Bakehouse - Singup</title>

        <link rel="shortcut icon" href="assets/images/organic_shop_favicon.ico" type="image/x-icon" />

        <script src="/assets/js/vendor/jquery.min.js"></script>
        <script src="/assets/js/vendor/popper.min.js"></script>
        <script src="/assets/js/vendor/bootstrap.min.js"></script>
        <script src="/assets/js/vendor/bootstrap-select.min.js"></script>
        <link rel="stylesheet" href="/assets/css/vendor/bootstrap.min.css" />
        <link rel="stylesheet" href="/assets/css/vendor/bootstrap-select.min.css" />

        <script src="/assets/js/global/global.js"></script>
        <link rel="stylesheet" href="/assets/css/custom/global.css" />
        <link rel="stylesheet" href="/assets/css/custom/signup.css" />
        <script src="/assets/js/global/login.js"></script>
    </head>
    <body>
        <div class="wrapper">
            <a href=""><img src="/assets/images/main_logo.svg" alt="Mady Bakehouse" /></a>
            <form action="/users/validate_signup" method="post">
                <h2>Signup to order.</h2>
                <a href="/users/login">Already a member? Login here.</a>
                <div class="error_div">
                </div>
                <ul>
                    <li>
                        <input type="text" name="first_name" required />
                        <label>First Name</label>
                    </li>
                    <li>
                        <input type="text" name="last_name" required />
                        <label>Last Name</label>
                    </li>
                    <li>
                        <input type="text" name="email" required />
                        <label>Email</label>
                    </li>
                    <li>
                        <input type="password" name="password" required />
                        <label>Password</label>
                    </li>
                    <li>
                        <input type="password" name="confirm_password" required />
                        <label>Confirm Password</label>
                    </li>
                </ul>
                <button class="signup_btn" type="submit">Signup</button>
                <input type="hidden" name="action" value="signup" />
            </form>
        </div>
    </body>
</html>
