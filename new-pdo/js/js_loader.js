
$js_path = "http://localhost/traian3/new-pdo/js/";

function js_include($script){
  var script = document.createElement('script');
  script.src = $js_path + $script;
  script.type = 'text/javascript';
  var head = document.getElementsByTagName('head').item(0);
  head.appendChild(script);
}

// Enter the script files here
js_include("Placeholders.min.js");
js_include("add_remove_elements.js");
js_include("ajax_loader.js");
js_include("ajax_nav.js");
js_include("ajax_nav2.js");
js_include("delete_confirmation.js");
js_include("general.js");
js_include("javas.js");
js_include("jquery-1.7.1.min.js");
js_include("zoomText.js");
