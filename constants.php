<?php

define('URL',get_site_url().DIRECTORY_SEPARATOR);

$parts = explode('/',__DIR__);
array_pop($parts); // remove this folder
array_pop($parts); // remove plugins;
array_pop($parts); // remove wp-content
$wp_dir =  implode('/',$parts);
define('DIR',$wp_dir.DIRECTORY_SEPARATOR);



define('DIR_PLUGIN',__DIR__.DIRECTORY_SEPARATOR);
define('URL_PLUGIN',URL.'wp-content/plugins/'.basename(__DIR__).DIRECTORY_SEPARATOR);

define('URL_IMAGENES',URL_PLUGIN.'images/');
define('URL_DOCS',URL_PLUGIN.'docs/');

define('DIR_IMAGENES',DIR_PLUGIN.'images/');
define('DIR_DOCS',DIR_PLUGIN.'docs/');

define('DIR_CACHE',DIR_PLUGIN.'images/cache/');
define('URL_CACHE',URL_PLUGIN.'images/cache/'); 


define('CONTACT_EMAIL','admin@example.com');

define('EMAIL_SMTP',"mail.example.com");
define('EMAIL_USER',"account@example.com");
define('EMAIL_FROM',"account@example.com");
define('EMAIL_PASS',"justapassword");



define('AJAX_URL',URL.'wp-admin/admin-ajax.php?action=');
define('URL_PLUGIN_JS',URL_PLUGIN.'views/js/');
?>