<link rel="stylesheet" href="/css/login.css">
<script src="/js/function.js"></script>
<div class="container">
    <form class="form-signin">
        <input type="text" id="loginAccount" name="account" class="form-control" placeholder="邮箱/手机" required="" autofocus="">
        <input type="password" id="loginPassword" name="password" class="form-control" placeholder="密码" >
        <!--
        <div class="checkbox">
            <label>
                <input type="checkbox" value="remember-me"> Remember me
            </label>
        </div>
        -->
        <button class="btn btn-lg btn-primary btn-block login-btn" type="button">登录</button>
    </form>
</div>
<script>
    $(function(){$(".login-btn").click(function(){checkLogin()})});
</script>