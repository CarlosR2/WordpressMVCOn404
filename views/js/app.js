
var loading ='<img class="loading" src="/css/images/ajax-loader.gif">';



function setCookie(c_name,value,exdays){
	var exdate=new Date();
	exdate.setDate(exdate.getDate() + exdays);
	var c_value=escape(value) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString());
	document.cookie=c_name + "=" + c_value;
}



function getCookie(c_name)
{
	var c_value = document.cookie;
	var c_start = c_value.indexOf(" " + c_name + "=");
	if (c_start == -1){
		c_start = c_value.indexOf(c_name + "=");
	}
	if (c_start == -1){
		c_value = null;
	}else{
		c_start = c_value.indexOf("=", c_start) + 1;
		var c_end = c_value.indexOf(";", c_start);
		if (c_end == -1){
			c_end = c_value.length;
		}
		c_value = unescape(c_value.substring(c_start,c_end));
	}
	return c_value;
}




function validate_email(email){
	var x = email;
	var atpos=x.indexOf("@");
	var dotpos=x.lastIndexOf(".");
	if (atpos<1 || dotpos<atpos+2 || dotpos+2>=x.length){
		return false;
	}
	return true;
}



function isInt(x) {
	var y=parseInt(x);
	if (isNaN(y)) return false;
	return x==y && x.toString()==y.toString();
}




var Events = function(app){

	var init = function(){

	}

	return{
		init:init

	}

}


var View = function(app){



	return{


	}
}



var Data = function(app){



	set_cookie= function(name,value){
		days = 1;
		if (days) {
			var date = new Date();
			date.setTime(date.getTime()+(days*24*60*60*1000));
			var expires = "; expires="+date.toGMTString();
		}
		else var expires = "";
		document.cookie = name+"="+value+expires+"; path=/";
	}

	get_cookie = function(name){
		var nameEQ = name + "=";
		var ca = document.cookie.split(';');
		for(var i=0;i < ca.length;i++) {
			var c = ca[i];
			while (c.charAt(0)==' ') c = c.substring(1,c.length);
			if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
		}
		return null;
	}

	delete_cookie = function(name){
		createCookie(name,"",-1);
	}



	return{
	}

}



var Services=function(app){




	var _get = function(func,params,callback,callback_false){
		jQuery.getJSON(
			ajax_url+func,  //'/wp-content/themes/reflection/_ajax.php?func='
			params,
			function(response){
				if(response.status == 'true'){
					if(response.info)
						callback(response.info);
					else
						callback();
					//app.View.showFilter(data);
				}else{
					if(response.info){
						if(response.info=='desconectado'){
							//recargar pagina
							window.location.reload()
						} else callback_false(response.info);
					}
					else if(response.result){
						if(response.result=='desconectado'){
							//recargar pagina
							window.location.reload()
						} else callback_false(response.result);
					}
					else
						callback_false();
				}
			});
	}


	var _post = function(func,params,callback,callback_false){
		jQuery.post(
			ajax_url+func, //'/wp-content/themes/reflection/_ajax.php?func='
			params,
			function(response){
				if(response.status == 'true'){
					if(response.info)
						callback(response.info);
					else
						callback();
					//app.View.showFilter(data);
				}else{
					if(response.info){
						if(response.info=='desconectado'){
							//recargar pagina
							window.location.reload()
						}else callback_false(response.info);
					}else if(response.result){
						if(response.result=='desconectado'){
							//recargar pagina
							window.location.reload()
						} else callback_false(response.result);
					}      
					else
						callback_false();
				}
			},'json');
	}

	var _post_wp = function(func,params,callback,callback_false){
		jQuery.post(
			ajax_url+func,
			params,
			function(response){
				if(response.status == 'true'){
					if(response.info)
						callback(response.info);
					else
						callback();
					//app.View.showFilter(data);
				}else{
					if(response.info){
						if(response.info=='desconectado'){
							//recargar pagina
							window.location.reload()
						}else callback_false(response.info);
					}else if(response.result){
						if(response.result=='desconectado'){
							//recargar pagina
							window.location.reload()
						} else callback_false(response.result);
					}      
					else
						callback_false();
				}
			},'json');
	}


	var _get_wp = function(func,params,callback,callback_false){
		jQuery.getJSON(
			ajax_url+func,
			params,
			function(response){
				if(response.status == 'true'){
					if(response.info)
						callback(response.info);
					else
						callback();
					//app.View.showFilter(data);
				}else{
					if(response.info){
						if(response.info=='desconectado'){
							//recargar pagina
							window.location.reload()
						} else callback_false(response.info);
					}
					else if(response.result){
						if(response.result=='desconectado'){
							//recargar pagina
							window.location.reload()
						} else callback_false(response.result);
					}
					else
						callback_false();
				}
			});
	}


	return {
		_post:_post,
		_get:_get,
		_post_wp:_post_wp,
		_get_wp:_get_wp        
	}


}



var app = function(){

	var myApp = {};
	myApp.Services =Services(myApp);
	myApp.Data =Data(myApp);
	myApp.View = View(myApp);
	myApp.Events =Events(myApp);


	return{
		init:myApp.Events.init,
		get:myApp.Services._get,
		post:myApp.Services._post,
		post_wp:myApp.Services._post_wp,
		get_wp:myApp.Services._get_wp            
	}

}();