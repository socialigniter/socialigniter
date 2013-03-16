Social-Igniter Core
===================

This is the Core install of Social-Igniter. This repository contains all you need to get an install node up and running on your own *AMP webserver.

To check out a working install of Social-Igniter go to: http://social-igniter.com


General Installation Instructions
=================================

* Download or Clone this repository (core Social-Igniter)
* Place Download or Clone in your web servers directory 
* Download or Clone https://github.com/socialigniter/system (core CodeIgniter)
	* Make sure /system directory lives at the same folder level as the your app (core Social-Igniter)
	* If not you will need to edit $system_path = '../system'; in the index.php file to point to wherever your 'system' folder exists

* Create a MySQL database & database user with your hosting database tool (cPanel, Plesk, etc...)
* Navigate to the following URL at the domain you setup http://domain-name.com/install.php
* Walk through all the steps
* Voila, you should have a working install of Social-Ingiter
* Submit any bugs or fork & send a pull request =)


Apache Installation Instructions
================================

* Create a Virtual Host or route for to this folder (e.g. http://domain-name.com)
* Make sure you mod_rewrite intalled and loaded in Apache

NGINX Installation Instructions
===============================

* Install the following code in your Server Block

    server {
      listen 80;
      server_name example.dev;
      root /Users/aaronpk/Code/social-igniter;
 
      try_files $uri /index.php?$args;
 
      location ~ \.php$ {
            fastcgi_pass    unix:/tmp/php-fpm.sock;
            fastcgi_index   index.php;
            include fastcgi_params;
            fastcgi_param   SCRIPT_FILENAME $document_root$fastcgi_script_name;
      }
    }


Documentation
=============

To see documentation for Social-Igniter go to: https://github.com/socialigniter/socialigniter/wiki

REST API Methods & Calls
========================

To see documentation on the API go to: http://social-igniter.com/api

Apps
====

There are a whole bunch of modules (blog, facebook, twitter, etc...) that are in various states of construction to see a full list go to: http://social-igniter.com/pages/apps

License
=======

Copyright 2012 by Social-Igniter.com and contributors
MIT License, CodeIgniter License