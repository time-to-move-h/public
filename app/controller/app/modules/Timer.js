"use strict";
define ([],function() {
    return function() {
        var timerutil = {
            init: function() {},
            page_timeout: null,
            msg_timeout: null,
            startTimer: function (duration, display, page_timeout, msg_timeout) {
                this.page_timeout = page_timeout;
                this.msg_timeout = msg_timeout;
                var timer = duration, minutes, seconds;
                var refreshId = setInterval(function () {
                    minutes = parseInt(timer / 60, 10)
                    seconds = parseInt(timer % 60, 10);
                    minutes = minutes < 10 ? "0" + minutes : minutes;
                    seconds = seconds < 10 ? "0" + seconds : seconds;
                    display.textContent = minutes + ":" + seconds;
                    if (--timer < 0) {
                        clearInterval(refreshId);
                        timerutil.endTimer();
                    }
                }, 1000);
            },
            endTimer: function () {
                alert(timerutil.msg_timeout);
                //timerutil.redirect_url(timerutil.page_timeout);
                window.history.back();
            },
            redirect_url: function(surl) {
                window.location.href = surl;
            }
        };
        return timerutil;
    }
});