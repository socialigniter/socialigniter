Social-Igniter Core
===================

This is the Core install of Social-Igniter. This repository contains all you need to get an install node up and running on your own *AMP webserver.

To see check out a working install of Social-Igniter go to: http://social-igniter.com

To see documentation on the API go to: http://social-igniter.com/api

Installation Instructions
=========================

* Click Downloads or Clone this repository
* Place Download or Clone in your web servers directory 
* Make duplicates of the following 4 files located in application/config/
*
* config.php.TEMPLATE
* database.php.TEMPLATE
* email.php.TEMPLATE
* social_igniter.php.TEMPLATE
*
* Make the file names config.php and such...
* Edit config.php value $config['base_url'] to match your server url
* Edit database.php to have match your database settings
* Install the database located at application/install/DATABASE.sql

License
=======

Copyright 2011 by Social-Igniter.com and contributors

See LICENSE