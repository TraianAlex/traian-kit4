function auth(){

   alert('You must login to see this page..');
   //window.location.assign("http://localhost/php-pdo");
   return false;
}

/*    modernizer / placeholder / webform2    */

            $(document).ready(function() {
                if (!Modernizr.input.autofocus) {
                    $("input[autofocus]").focus(); // give focus to whichever element has the autofocus attribute
                }
            });

            $(document).ready(function() {
                if (!Modernizr.input.placeholder) {
                    makePlaceholders();
                }
            });

function O(obj){
    if (typeof obj == 'object'){
        return obj;
    }else{
        return document.getElementById(obj);
    }
}

function S(obj){
    return O(obj).style;
}

/*function C(name){
  var elements = document.getElementsByTagName('*')
  var objects  = []

  for (var i = 0 ; i < elements.length ; ++i)
    if (elements[i].className == name)
      objects.push(elements[i])

  return objects
}*/


	$(document).ready(function() {
      		$("#signup_form").validate({
          	rules: {
                    password: {
                        minlength: 6
                    },
                    confirm_password: {
                  	minlength: 6,
                  	equalTo: "#password"
                    }
          	},
          	messages: {
                    password: {
                        minlength: "Passwords must be at least 6 characters"
                    },
                    confirm_password: {
                  	minlength: "Passwords must be at least 6 characters",
                  	equalTo: "Your passwords do not match."
              		}
                    }
      		});
  	});
/*ajax*/
            function checkUser(newuser) {
                if (newuser.value == '') {
                    O('info').innerHTML = ''
                    return;
                }
                params = "newuser=" + newuser.value;
                request = new ajaxRequest();
                request.open("POST", "http://localhost/traian-kit4/new-pdo/inc/Ajax_method.php", true);
                request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                request.setRequestHeader("Content-length", params.length);
                request.setRequestHeader("Connection", "close");

                request.onreadystatechange = function() {
                    if (this.readyState == 4)
                        if (this.status == 200)
                            if (this.responseText != null)
                                O('info').innerHTML = this.responseText;
                }
                request.send(params);
            }
            
            function checkAdmin(newadmin) {
                if (newadmin.value == '') {
                    O('info').innerHTML = ''
                    return;
                }
                params = "newadmin=" + newadmin.value;
                request = new ajaxRequest();
                request.open("POST", "http://localhost/traian-kit4/new-pdo/inc/Ajax_method.php", true);
                request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                request.setRequestHeader("Content-length", params.length);
                request.setRequestHeader("Connection", "close");

                request.onreadystatechange = function() {
                    if (this.readyState == 4)
                        if (this.status == 200)
                            if (this.responseText != null)
                                O('info').innerHTML = this.responseText;
                }
                request.send(params);
            }

            function ajaxRequest() {
                try {
                    var request = new XMLHttpRequest();
                }
                catch (e1) {
                    try {
                        request = new ActiveXObject("Msxml2.XMLHTTP");
                    }
                    catch (e2) {
                        try {
                            request = new ActiveXObject("Microsoft.XMLHTTP");
                        }
                        catch (e3) {
                            request = false;
                        }
                    }
                }
                return request;
            }