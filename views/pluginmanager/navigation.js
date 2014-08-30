var hashChange = (function() {
    var timer,
        fun = function(ul, element, height) {
            for (var i = 0; i < ul.children.length; i++) {
                if (ul.children[i] !== element) {
                    ul.children[i].style.maxHeight = height + 'px';
                }
            }
        };
    return function(hash) {
        var slider = document.getElementById('slider'),
            ul = slider.children[0].children[0],
            i,
            cur,
            height;
        for (i = 0; i < ul.children.length; i++) {
            if (ul.children[i].getAttribute('name') === hash.substr(1)) {
                ul.setAttribute('pos', i);
                cur = ul.children[i];
                cur.style.maxHeight = 'none';
                height = cur.clientHeight;
                if (typeof timer !== 'undefined') {
                    clearTimeout(timer);
                }
                timer = setTimeout(function() {fun(ul, cur, height);}, 770);
                return true;
            }
        }
        return false;
    };
})();
if ("onhashchange" in window) {
    window.onhashchange = function() {
        hashChange(window.location.hash);
    };
} else {
    (function() {
        var storedHash = window.location.hash;
        window.setInterval(function () {
            if (window.location.hash !== storedHash) {
                storedHash = window.location.hash;
                hashChange(storedHash);
            }
        },
        100); 
    })();
}
window.addEventListener('load', function() {hashChange(window.location.hash);});

function initialize() {
    var divs = document.getElementsByClassName('map'),
        i,
        lat,
        lng,
        title,
        map,
        marker,
        zoom;
    console.log(divs);
    for (i = 0; i < divs.length; i++) {
        lat = parseFloat(divs[i].getAttribute('lat'));
        lng = parseFloat(divs[i].getAttribute('lng'));
        zoom = parseInt(divs[i].getAttribute('zoom'));
        title = divs[i].getAttribute('title');
        console.log(lat, lng, zoom);
        if (lat !== null && lng !== null) {
            myLatLng = new google.maps.LatLng(lat, lng);
            map = new google.maps.Map(divs[i],{center: myLatLng,zoom: zoom});
            marker = new google.maps.Marker({position: myLatLng, map: map, title:title});
        }   
    }
}

window.addEventListener('load', function() {
    var lists = document.getElementById('tour').children,
        i,
        cur = null,
        fun = function() {
            if (this !== cur) {
                this.setAttribute('active', 'true');
                if (cur !== null) {
                    cur.setAttribute('active', 'false');
                }
                cur = this;
            }
        };
    for (i = 0; i < lists.length; i++) {
        if (lists[i].tagName === 'UL') {
            lists[i].children[0].onclick = fun;
            if (lists[i].getAttribute('active') === 'true') {
                if (cur !== null) {
                    cur.setAttribute('active', 'false');
                }
                lists[i].setAttribute('active', 'true')
                cur = lists[i];
            }
        }
    }
}, false);

google.maps.event.addDomListener(window, 'load', initialize);
