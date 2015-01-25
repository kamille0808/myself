var interval = 0;
window.onload = function(){

    // 初始化屏幕大小
    WINDOW_HEIGHT = $(document).height();
    WINDOW_WIDTH = $(document).width();

    MARGIN_LEFT = Math.round(WINDOW_WIDTH * 0.03);
    MARGIN_TOP = Math.round(WINDOW_HEIGHT / 5);

    R = Math.round(WINDOW_WIDTH * 4/5 / (BALLS_ROW_NUM*2) ) - 2;

    // 初始化日期时间
    var startDate = new Date();
    HOURS_TEN = parseInt(startDate.getHours()/10);
    HOURS_ONE = parseInt(startDate.getHours()%10);
    MINUTES_TEN = parseInt(startDate.getMinutes()/10);
    MINUTES_ONE = parseInt(startDate.getMinutes()%10);
    SECONDS_TEN = parseInt(startDate.getSeconds()/10);
    SECONDS_ONE = parseInt(startDate.getSeconds()%10);

    // 初始化canves
    var canves = document.getElementById("clock");
    canves.height = WINDOW_HEIGHT;
    canves.width = WINDOW_WIDTH;

    var context = canves.getContext('2d');

    draw.init(context);
    render.render(context);

    // 循环
    interval = setInterval(function(){
        render.render(context);
        // 显示彩球
        ball.render();
    }, 50);
};

// 绘制
var render = {

    render : function(context){

        // 清除当前绘布内容
        context.clearRect(0, 0, WINDOW_WIDTH, WINDOW_HEIGHT);

        // 定义当前时间
        var date = new Date();
        var hours_ten = parseInt(date.getHours()/10);  // 小时十位
        var hours_one = parseInt(date.getHours()%10); // 小时个位
        var minutes_ten = parseInt(date.getMinutes()/10);
        var minutes_one = parseInt(date.getMinutes()%10);
        var seconds_ten = parseInt(date.getSeconds()/10);
        var seconds_one = parseInt(date.getSeconds()%10);

        // 绘制小时的十位
        this.digit(MARGIN_LEFT, MARGIN_TOP, lattice["digit"][hours_ten], context);
        // 小时个位
        this.digit(MARGIN_LEFT + DIGIT_WIDTH, MARGIN_TOP, lattice["digit"][hours_one], context);
        // 冒号
        this.digit(MARGIN_LEFT + 2*DIGIT_WIDTH, MARGIN_TOP, lattice["colon"], context);
        // 分钟十位
        this.digit(MARGIN_LEFT + 2*DIGIT_WIDTH + COLON_WIDTH, MARGIN_TOP, lattice["digit"][minutes_ten], context);
        // 分钟个位
        this.digit(MARGIN_LEFT + 3*DIGIT_WIDTH + COLON_WIDTH, MARGIN_TOP, lattice["digit"][minutes_one], context);
        // 冒号
        this.digit(MARGIN_LEFT + 4*DIGIT_WIDTH + COLON_WIDTH, MARGIN_TOP, lattice["colon"], context);
        // 秒十位
        this.digit(MARGIN_LEFT + 4*DIGIT_WIDTH + 2*COLON_WIDTH, MARGIN_TOP, lattice["digit"][seconds_ten], context);
        // 秒个位
        this.digit(MARGIN_LEFT + 5*DIGIT_WIDTH + 2*COLON_WIDTH, MARGIN_TOP, lattice["digit"][seconds_one], context);

        // 时间变更标记
        var flg = 0;
        // 当前时间不等于记录的时间
        if ( hours_ten != HOURS_TEN ){
            ball.createBall( MARGIN_LEFT, MARGIN_TOP, lattice.digit[HOURS_TEN] );
            flg = 1;
        }
        if ( hours_one != HOURS_ONE ){
            ball.createBall( MARGIN_LEFT + DIGIT_WIDTH, MARGIN_TOP, lattice["digit"][HOURS_ONE] );
            flg = 1;
        }
        if ( minutes_ten != MINUTES_TEN ){
            ball.createBall( MARGIN_LEFT + 2*DIGIT_WIDTH + COLON_WIDTH, MARGIN_TOP, lattice["digit"][MINUTES_TEN] );
            flg = 1;
        }
        if ( minutes_one != MINUTES_ONE ){
            ball.createBall( MARGIN_LEFT + 3*DIGIT_WIDTH + COLON_WIDTH, MARGIN_TOP, lattice["digit"][MINUTES_ONE] );
            flg = 1;
        }
        if ( seconds_ten != SECONDS_TEN ){
            ball.createBall( MARGIN_LEFT + 4*DIGIT_WIDTH + 2*COLON_WIDTH, MARGIN_TOP, lattice["digit"][SECONDS_TEN] );
            flg = 1;
        }
        if ( seconds_one != SECONDS_ONE ){
            ball.createBall( MARGIN_LEFT + 5*DIGIT_WIDTH + 2*COLON_WIDTH, MARGIN_TOP, lattice["digit"][SECONDS_ONE] );
            flg = 1;
        }

        // 当前时间赋值给记录时间
        HOURS_TEN = hours_ten;  // 记录的小时十位
        HOURS_ONE = hours_one;  // 小时个位数
        MINUTES_TEN = minutes_ten;
        MINUTES_ONE = minutes_one;
        SECONDS_TEN = seconds_ten;
        SECONDS_ONE = seconds_one;
    },

    /**
     * 绘制圆球
     * @param x 圆球中心起始X
     * @param y 圆球中心起始Y
     * @param lattice   使用的点阵
     * @param context   context对象
     */
    digit : function (x, y, lattice, context){

        // 遍历当前数字对应的点阵
        for ( var i = 0; i < lattice.length; i++ ){
            // 遍历点阵的每行
            for ( var j = 0; j < lattice[i].length; j++ ){
                // 当前坐标为1，则绘制圆球
                if( lattice[i][j] == 1 ){
                    // 将每个小球看成在一个个正方形小格子里
                    // 小格子边长 为[小球半径R加1(小球间隙)]*2
                    // 那么X轴中心位置就是：起始位置x + 点阵列数*格子边长 + 格子边长一半
                    // Y轴同理
                    var centerX = x + j*2*(R+1)+(R+1);
                    var centerY = y + i*2*(R+1)+(R+1);
                    // 绘制小球
                    var params = {
                        fillStyle : "#005588"
                    };
                    draw.ball( centerX, centerY, R, 0, 2*Math.PI, true, params );
                }
            }
        }
    }

};

// 彩球操作
var ball = {
    // 显示彩球
    render : function (){
        this.updateBall();
        for ( var i = 0; i < balls.length; i++ ){
            var params = {
                fillStyle : balls[i].color
            };
            draw.ball( balls[i].x, balls[i].y, R, 0, 2*Math.PI,true, params );
        }
    },

    /**
     * 创建小球
     * @param x 中心位置x
     * @param y 中心位置x
     * @param lattice 需要形成的数字的点阵
     */
    createBall : function( x, y, lattice ){
        for ( var i = 0; i < lattice.length; i++ ){
            for ( var j = 0; j < lattice[i].length; j++ ){
                if( lattice[i][j] == 1 ){
                    var centerX = x + j*2*(R+1)+(R+1);
                    var centerY = y + i*2*(R+1)+(R+1);
                    var aBall = {
                        x : centerX,
                        y : centerY,
                        r : R,
                        g : 1.5+Math.random(),
                        vx:Math.pow( -1 , Math.ceil( Math.random()*1000 ) ) * 4,
                        vy: -5,
                        color: color.addBall[ Math.floor( Math.random()*color.addBall.length ) ]
                    };
                    balls.push(aBall);
                }
            }
        }
    },

    /**
     * 更新彩球运动状态
     */
    updateBall : function (){
        var tmp_balls = []; // 临时存放彩球
        for( var i = 0 ; i < balls.length ; i ++ ){
            balls[i].x += balls[i].vx;
            balls[i].y += balls[i].vy;
            balls[i].vy += balls[i].g;
            // 下边碰撞
            if( balls[i].y >= WINDOW_HEIGHT - R ){
                balls[i].y = WINDOW_HEIGHT - R;
                balls[i].vy = - balls[i].vy*0.75;
            }

            // 如果彩球还在画布中，并且数量并未超出设定
            if( balls[i].x + R > 0 && balls[i].x - R < WINDOW_WIDTH && i <= TOTAL_BALLS ){
                tmp_balls.push(balls[i]);
            }
        }
        balls = tmp_balls;
    }

};