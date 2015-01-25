/**
 * Created by Dennis on 15-1-18.
 */

var draw = {

    context : { },  // context对象

    init : function ( context ){
        this.context = context;
    },

    /**
     * 绘制圆球
     * @param params 配圆球置
     */
    ball : function ( x, y,redius, startAng, endAng ,anti, params ){

        //如果有配置参数
        if( params ){
            // 圆球填充色
            this.context.fillStyle = params['fillStyle'] ? params['fillStyle'] : color.arc;
        }

        anti = anti ? true : false;

        this.context.beginPath();
        this.context.arc(x, y, redius, startAng, endAng, anti);
        this.context.closePath();
        this.context.fill();
    }

};