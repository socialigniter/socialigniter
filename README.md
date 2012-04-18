Social-Igniter Core
===================

This is the Core install of Social-Igniter. This repository contains all you need to get an install node up and running on your own *AMP webserver.

To check out a working install of Social-Igniter go to: http://social-igniter.com

Installation Instructions
=========================

* Click Download or Clone this repository (core Social-Igniter)
* Place Download or Clone in your web servers directory 

* Click Download or Clone https://github.com/socialigniter/system (which is CI core)
	* Make sure /system directory lives at the same folder level as the your app (core SI)
	* If not you will need to edit $system_path = '../system'; in the index.php file

* Make duplicates of the following 6 files
* application/config/config.php.TEMPLATE
* application/config/custom.php.TEMPLATE
* application/config/database.php.TEMPLATE
* application/config/routes.php.TEMPLATE
* application/helpers/custom_helper.php.TEMPLATE
* Make the file names config.php, custom.php, etc...
* Edit config.php value $config['base_url'] to match your server url
* Edit database.php to have match your database settings
* Dump the SQL located at application/install/DATABASE.sql into your database

API Documentation
================

To see documentation on the API go to: http://social-igniter.com/api

Modules
=======

There are a whole bunch of modules (blog, facebook, twitter, etc...) that are in various states of construction to see a full list go to: http://social-igniter.com/pages/apps

License
=======

Copyright 2012 by Social-Igniter.com and contributors
MIT License, CodeIgniter License