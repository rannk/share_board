/**
 * 根据点击元素的位置显示层
 */
(function ($) {
    $.fn.floatDiv = function (action, target, offset) {
        var f_top = 0, f_left = 0, f_width, w_width, w_height, d_width, d_height, float_div;

        float_div = this;

        if($(target).length > 0) {
            f_top = $(target).offset().top;
            f_left = $(target).offset().left;
        }

        f_width = $(target).width();
        w_width = window.innerWidth;
        w_height = window.innerHeight;
        d_width = $(float_div).width();
        d_height = $(float_div).height();
        click_target = $(target);


        if(typeof offset == "object") {
            if(offset.top > 0) {
                f_top += offset.top;
            }
            if(offset.left > 0) {
                f_left += offset.left;
            }
        }

        f_left = f_width + f_left;
        // 如果层超过窗口的右面，则把层放置到元素的左面
        if(f_left + d_width > w_width){
            f_left = f_left-d_width-f_width;
        }

        if(f_left < 0) {
            f_left = 0;
        }

        // 如果层超过窗口的高度，则层显示于元素的上方
        if(f_top + d_height > w_height) {
            f_top = f_top - d_height;
        }

        if(f_top < 0) {
            f_top = 0;
        }

        if(action == "show") {
            $(float_div).offset({"top":f_top,"left":f_left});
            $(float_div).show();
            $("body").unbind("click");
            $("body").on("click",function (e) {
                if($(e.target).closest(float_div).length == 0 && $(e.target).closest(click_target).length == 0) {
                    $(float_div).offset({"top":0,"left":0});
                    $(float_div).hide();
                    $("body").unbind("click");
                }
            });
        }

        if(action == "hide") {
            $(float_div).offset({"top":0,"left":0});
            $(float_div).hide();
            $("body").unbind("click");
        }
    }
}(jQuery));
