/* Ultimater's edited version of:
     http://jibbering.com/2002/4/httprequest.html
     to serve IE7 with XMLHttpRequest instead of ActiveX */

    var xmlhttp = false;
    if (!xmlhttp && typeof XMLHttpRequest != 'undefined') {
        try {
            xmlhttp = new XMLHttpRequest();
        } catch (e) {
            xmlhttp = false;
        }
    }

    /*@cc_on @*/
    /*@if (@_jscript_version >= 5)
     // JScript gives us Conditional compilation, we can cope with old IE versions.
     // and security blocked creation of the objects.
     if (!xmlhttp){
     try {
     xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
     } catch (e) {
     try {
     xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
     } catch (E) {
     xmlhttp = false;
     }
     }
     }
     @end @*/

    if (!xmlhttp && window.createRequest) {
        try {
            xmlhttp = window.createRequest();
        } catch (e) {
            xmlhttp = false;
        }
    }

    /* Ultimater's edited version of:
     http://www.javascriptsource.com/ajax/ajax-navigation.html */

    var please_wait = "Please wait...";

    function open_url(url, targetId) {
        if (!xmlhttp)
            return false;
        var e = document.getElementById(targetId);
        if (!e)
            return false;
        if (please_wait)
            e.innerHTML = please_wait;
        xmlhttp.open("GET", url, true);
        xmlhttp.onreadystatechange = function () {
            response(url, e);
        }
        try {
            xmlhttp.send(null);
        } catch (l) {
            while (e.firstChild)
                e.removeChild(e.firstChild);//e.innerHTML="" the standard way
            e.appendChild(document.createTextNode("request failed"));
        }
    }

    function response(url, e) {
        if (xmlhttp.readyState != 4)
            return;
        var tmp = (xmlhttp.status == 200 || xmlhttp.status == 0) ? xmlhttp.responseText : "Ooops!! A broken link! Please contact the webmaster of this website ASAP and give him the following error code: " + xmlhttp.status + " " + xmlhttp.statusText;
        var d = document.createElement("div");
        d.innerHTML = tmp;
        setTimeout(function () {
            while (e.firstChild)
                e.removeChild(e.firstChild);//e.innerHTML="" the standard way
            e.appendChild(d);
        }, 10)
    }