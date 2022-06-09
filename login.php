<?php include "init.php"; ?>

<div class="container login-page">
        <h1 class="text-center">
            <span class="selected" data-class="login">Login</span> | <span class="x" data-class="signup">SignUp</span>
        </h1>
        <form class="login">
            <div class="input-container">
                <input class="form-control" type="text" name="username" placeholder="Username" autocomplete="off" required/>
            </div>
            <input class="form-control" type="password" name="password" placeholder="password" autocomplete="new-password"/>
            <input class="btn btn-primary btn-block" type="submit" value="login"/>      
        </form>
        <form class="signup">
            <input class="form-control" type="text" name="username" placeholder="Username" autocomplete="off"/>
            <input class="form-control" type="password" name="password" placeholder="password" autocomplete="new-password"/>
            <input class="form-control" type="password" name="password2" placeholder="confirm password" autocomplete="new-password"/>
            <input class="form-control" type="email" name="email" placeholder="Email" />
            <input class="btn btn-success btn-block" type="submit" value="SignUp"/>      
        </form>
    </div>

<?php include $tpl.'footer.php'; ?>