<?php
//composer autoloader
require_once 'vendor/autoload.php';
//load controller
require_once 'controller.php';

//config file
$config = require_once 'config.php';


$controller = new Controller();

if($controller->validate_config($config)){

    switch ($config['env']) {
        case 'local':
            define("REDIRECT_URI", 'http://localhost/podio_app/');
            break;
        case 'development':
            define("REDIRECT_URI", 'http://podio-app.arsen-sargsyan.info/');
            break;

    }
    $controller->init_podio($config);

    if (!isset($_GET['code']) && !Podio::is_authenticated()) {

        // User is not being reidrected and does not have an active session
        // We just display a link to the authentication page on podio.com
        $auth_url = htmlentities(Podio::authorize_url(REDIRECT_URI));
        print "<a href='{$auth_url}'>Start authenticating</a>";

    } elseif (Podio::is_authenticated()) {

        // User already has an active session. You can make API calls here:
        print "You were already authenticated and no authentication is needed.";

    } elseif (isset($_GET['code'])) {

        // If there was a problem $_GET['error'] is set:
        if (isset($_GET['error'])) {
            print "There was a problem. The server said: {$_GET['error_description']}";
        } else {
            // Finalize authentication. Note that we must pass the REDIRECT_URI again.
            Podio::authenticate_with_authorization_code($_GET['code'], REDIRECT_URI);
            print "You have been authenticated. Try to send an email with attached word document!";

        }

    }

}else{
    echo 'Error occured! <br>';
    foreach($controller->config_errors as $error_mgs){
        printf('%s <br>',$error_mgs);
    }
}
