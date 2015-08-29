# podio_app
###############################
How make it run

1.  do composer update if vendor folder is missing
2.  create new folder temp if it is missing and chnage permission(linux) chmod -R 777 /temp
3.  create config.php, copy the same as in config_sample.php and fill parameters
4.  Adding hook (login podio > select app > settings button > Developer >  press "Add hook" button)
    In URL field  paste such url http://your-live-host.com/web_hook.php
    Type should be item.create
    press "Add hook"
    In action column press "Verify" button, status should become "active", if it's not happen then something goes wrong..
