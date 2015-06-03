function ajaxLoader(url,id) {
  if (document.getElementById) {
    var x = (window.ActiveXObject) ? new ActiveXObject("Microsoft.XMLHTTP") : new XMLHttpRequest();
  }
  if (x) {
    x.onreadystatechange = function() {
      if (x.readyState == 4 && x.status == 200) {
        el = document.getElementById(id);
        el.innerHTML = x.responseText;
      }
    }
    x.open("GET", url, true);
    x.send(null);
  }
}

function ajaxLoader2(url,id) {
  if (window.XMLHttpRequest) {
    var x = new XMLHttpRequest();
  }else{
    x = new ActiveXObject("Microsoft.XMLHTTP");
  }
    x.onreadystatechange = function() {
      if (x.readyState == 4 && x.status == 200) {
        document.getElementById(id).innerHTML = x.responseText;
      }
    }
    x.open("GET", url, true);
    x.send();
}