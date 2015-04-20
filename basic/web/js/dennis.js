/**
 * Created by jooyum on 15-4-20.
 */
(function(){
    var W =window;

    var d = {

        /**
         * 验证数字
         * @param s
         * @returns {Array|{index: number, input: string}}
         */
        checkNum : function (s){
            var reg = /^\d+$|^\d+\.\d+$/;
            return reg.exec(s);
        },

        /**
         * 验证邮箱
         * @param s
         * @returns {Array|{index: number, input: string}}
         */
        checkEmail : function(s){
            var reg=  /^[a-z0-9]([a-z0-9_\-\.]*)@([a-z0-9_\-\.]*)(\.[a-z]{2,3}(\.[a-z]{2}){0,2})$/i;
            return reg.exec(s);
        },

        /**
         * 验证手机
         * @param s
         * @returns {Array|{index: number, input: string}}
         */
        checkMobile : function(s){
            var reg = /^(1[358][0-9]{1})[0-9]{8}$/;
            return reg.exec(s);
        }

    };

    W.dennis = d;
})();