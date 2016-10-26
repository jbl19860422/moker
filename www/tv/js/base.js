(function(){
    var YC = {/*本地控制版本号*/
        version: '1.0.0',
        /*基本配置*/
        config: {
            giftTime: 6000,  /*礼物删除时间*/
            barrageTime: 10000, /*弹幕删除时间*/
            maxTalks: 100,    /*聊天消息最多显示多少条*/
            barrageNum: 0,  /*弹幕消息轨道，从0开始*/
            maxbarrageNum: 5    /*弹幕消息轨道最大值,允许最大值9条,当前5条*/
        },
        /*基本参数*/
        params: {
            alertTime: null, /*弹出toast*/
            els: {},
        },
        /*基本事件*/
        EVENT: {
            sendGift: 'sendGift', /*发送礼物*/
            sendBarrage: 'sendBarrage', /*发送弹幕*/
            sendMessage: 'sendMessage' /*发送消息*/
        },
        /*
        * 返回模板
        * */
        template: {
            getBarrage: function(msg){
                /*
                * <div class="barrage-item barrage-in-out">
                *   <img src="image/gift/kiss.png" class="barrage-header-img"/>
                *   <span class="color-ffdb35">杨超-第一桌：</span>
                *   <span class="color-ffffff">再来一首</span>
                * </div>
                */
                var barrageNumClass = '';
                if(YC.config.barrageNum == YC.config.maxbarrageNum){
                    YC.config.barrageNum = 0;
                }
                barrageNumClass = YC.config.barrageNum?'p'+YC.config.barrageNum:'';
                YC.config.barrageNum++;
                var html = '<div class="barrage-item barrage-in-out '+barrageNumClass+'">' +
                                '<img src="'+msg.headimg+'" class="barrage-header-img"/>'+
                                '<span class="color-ffdb35">'+msg.nickname+'-第'+msg.desk_id+'桌：</span>' +
                                '<span class="color-ffffff">'+msg.content+'</span>'+
                           '</div>';
                return html;
            },
            getMessage: function(msg){
                /**
                 * <div class="talk-item animated bounceInLeft">
                 *  <div class="talk-level"><i class="level-sign star"></i>3</div>
                 *  <span class="color-ffdb35">杨超-第二桌：</span>
                 *  <span class="color-ffffff">很好不错</span>
                 * </div>
                 * */
                var html = '<div class="talk-item animated bounceInLeft">'+
                    '<div class="talk-level"><i class="level-sign star"></i>3</div>'+
                    '<span class="color-ffdb35">'+msg.nickname+'-第'+msg.desk_id+'桌：</span>'+
                    '<span class="color-ffffff">'+msg.content+'</span>'+
                    '</div>';
                return html;
            },
            getGift: function(giftMsg){
                var html = '<div class="gift-item msg-in-out">'+
                    '<img class="gift-header-img" src="'+giftMsg.headimg+'"/>'+
                    '<div class="gift-info">'+
                    '<p class="color-ffdb35 text-warp">'+giftMsg.nickname+'-第'+giftMsg.desk_id+'桌</p>'+
                    '<p class="color-2ed0d6">'+giftMsg.item_name+'</p>'+
                    '</div>'+
                    '<div class="gift-goods animated bounceInLeft delay-1">'+
                    '<img src="'+giftMsg.item_img+'" class="gift-img"/>'+
                    '<em class="gift-num animated bounceIn delay-2">x'+'1'+'</em>'+
                    '</div>'+
                    '</div>';
                return html;
            }
        },
        /*初始化*/
        init: function(){
            /*添加事件监听*/
            $.each(YC.EVENT,function(key,value){
                $(document).on(key,function(e,data){
                    if(typeof YC[value]== 'function') {
                        YC[value](data);
                    }
                });
                YC.params.els = {
                    giftSpace: $('.send-gift-list'),/*礼物区域*/
                    barrageSpace: $('.barrage-space'),/*弹幕区域*/
                    talkSPace: $('.talk-list') /*聊天区域*/
                };
            });
        },
        /**
         * 发送礼物
         * */
        sendGift: function(params){
            /*模拟发送礼物个数*/
            var giftNum = parseInt(20 * Math.random() + 1);
            var giftEl = $(YC.template.getGift());
            YC.params.els.giftSpace.append(giftEl);
            if(giftNum > 1){
                setTimeout(function(){
                    giftEl.removeClass('msg-in-out');
                    var numEl = giftEl.find('em.gift-num'),num=1,inT;
                    numEl.removeClass('bounceIn delay-2'),reNum = function(){
                        numEl.removeClass('bounceIn');
                        num++;
                        if(num > giftNum){
                            clearInterval(inT);
                            giftEl.addClass('msg-out');
                            YC.timeToRemove(giftEl, YC.config.giftTime);
                        } else {
                            setTimeout(function(){
                                numEl.text('x'+num);
                                numEl.addClass('bounceIn');
                            },100);
                        }
                    };
                    reNum();
                    inT = setInterval(reNum,1000);
                },3100);
            } else {
                YC.timeToRemove(giftEl,YC.config.giftTime);
            }
            YC.scrollBottom(YC.params.els.giftSpace);
        },
        /**
         * 发送弹幕
         * */
        sendBarrage: function(params){
            var barrageEl = $(YC.template.getBarrage());
            YC.params.els.barrageSpace.append(barrageEl);
            YC.timeToRemove(barrageEl,YC.config.barrageTime);
        },
        /**
         * 发送消息
         * */
        sendMessage: function(params){
            var messageEl = $(YC.template.getMessage());
            YC.params.els.talkSPace.append(messageEl);
            var talks = YC.params.els.talkSPace.find('.talk-item');
            if(talks.length > YC.config.maxTalks){
                talks.eq(0).remove();
            }
            YC.scrollBottom(YC.params.els.talkSPace);
        },
        /**
         * 滑动到底端
         * */
        scrollBottom: function(el){
            var scrollBar = el.closest('.scrollbar');
            scrollBar[0].scrollTop = scrollBar[0].scrollHeight;
        },
        /**
         * 时间结束后移除元素
         * */
        timeToRemove: function(el,time){
            setTimeout(function(){
                el.remove();
            },time);
        }
    };
    YC.extend = function (a, b) {
        if (typeof a == 'string') {
            YC[a] = b;
        } else {
            for (var funcName in a) {
                YC[funcName] = a[funcName];
            }
        }
    };
    /* *
     * 使用继承方法完成基本功能方法定义
     * */
    YC.extend({
        /* *
         * toast 弹出提示，仿照app
         * @param info  信息提示内容
         * @param time  信息提示显示时间
         * */
        alert: function(info,time){
            info = info?info:'this alert info!';
            time = time?time:2000;
            var toast = $('.toast');
            if(toast.length){
                clearTimeout(YC.params.alertTime);
                toast.text(info);
            } else {
                $('body').append('<div class="toast">'+info+'</div>');
            }
            YC.params.alertTime = setTimeout(function(){
                $('.toast').remove();
            },time);
        },
        /* *
         * 显示幕布
         * @function show 显示幕布
         *      @param html  幕布中的内容，可选
         * @function hide 隐藏幕布
         * */
        backdrop: {
            show: function(html){
                html = html?html:'';
                var backdrop = $('.backdrop');
                if(backdrop.length){
                    backdrop.html(html);
                } else {
                    $('body').append('<div class="backdrop">'+html+'</div>');
                }
            },
            hide: function(){
                $('.backdrop').remove();
            }
        },
        /* *
         * 显示加载
         * @function show 是否加载
         *      @param info  显示加载中的信息提示
         * @function hide 隐藏加载
         * */
        loading: {
            show: function(info){
                info = info?info:'加载中，请稍候！';
                var loading = $('.loading');
                if(loading.length){
                    loading.text(info);
                } else {
                    YC.backdrop.show('<div class="loading">'+info+'</div>');
                }
            },
            hide: function(){
                YC.backdrop.hide();
            }
        }
    });
    /* *
     * 加载完成后初始化系统
     * */
    $(function(){
        YC.init();
    });
    /* *
     * 定义window全局变量YC和Y
     * */
    window.YC = window.Y = YC;
})()