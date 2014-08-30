document.addEventListener('load', function() {
    document.getElementsByTagName('A').map(function(a) {
        var href = a.href,
            start = href.indexOf('#'),
            end = href.indexOf('?'),
            method;
        if (start > -1) {
            if (start < end) {
                method = href.substr(start + 1, start - end);
            } else {
                method = href.substr(start + 1);
            }
            a.onclick = function() {
                var xml = window.XMLHttpRequest()
                        ? new XMLHttpRequest()
                        : new ActiveXObject("Microsoft.XMLHTTP");
                console.log(method);
            };
        }
    });
});