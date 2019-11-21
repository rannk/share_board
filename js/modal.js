
(function ($) {
    $.fn.modal = function (action) {
        var target = this;
        var s = false;
        if(action == "show") {
            $(target).offset({"top":$("#panel").height()/2-$(target).height()/2, "left": $("#panel").width()/2-$(target).width()/2});
            $(target).show();
            $("body").unbind("click");
            $("body").on("click",function (e) {
                if($(e.target).closest(target).length == 0 && s ==  true) {
                    $(target).offset({"top":0,"left":0});
                    $(target).hide();
                    $("body").unbind("click");
                }
                s = true;
            });
        }

        if(action == "hide") {
            $(target).offset({"top":0,"left":0});
            $(target).hide();
            s = false;
        }
    }
}(jQuery));
