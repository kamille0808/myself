/**
 * Created by jooyum on 15-4-20.
 */

/**
 * 登录验证
 * @returns {boolean}
 */
function checkLogin (){
    var account = $.trim($("#loginAccount").val());
    var passwd = $.trim($("#loginPassword").val());

    if( ! account || ! passwd ){
        alert("请输入账号或密码");
        return false;
    }

    if( ! dennis.checkMobile(account) && ! dennis.checkEmail(account) ){
        alert("账号格式错误");
        return false;
    }

    $.post(
        '/index.php?r=index/login',
        { account : account,passwd : passwd },
        function (json){
            if( json.status == 0 ){
                alert(json.msg);
                return false;
            }
        },
        'json'
    );
}