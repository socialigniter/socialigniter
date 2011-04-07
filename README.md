Social-Igniter Core
===================

This is the Core install of Social-Igniter. This repository contains all you need to get an install node up and running on your own *AMP webserver.

To see check out a working install of Social-Igniter go to: http://social-igniter.com

Installation Instructions
=========================

* Click Downloads or Clone this repository
* Place Download or Clone in your web servers directory 
* Make duplicates of the following 6 files
* application/config/config.php.TEMPLATE
* application/config/custom.php.TEMPLATE
* application/config/database.php.TEMPLATE
* application/config/routes.php.TEMPLATE
* application/config/social_igniter.php.TEMPLATE
* application/helpers/custom_helper.php.TEMPLATE
* Make the file names config.php and such...
* Edit config.php value $config['base_url'] to match your server url
* Edit database.php to have match your database settings
* Install the database located at application/install/DATABASE.sql

API Documentation
================

To see documentation on the API go to: http://social-igniter.com/api

Modules
=======

There are a whole bunch of modules (blog, twitter, etc...) that are under various states of construction to see a full list go to: http://social-igniter.com/pages/modules

License
=======

Copyright 2011 by Social-Igniter.com and contributors

MIT License, CodeIgniter License