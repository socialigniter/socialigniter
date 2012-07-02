Social-Igniter Core
===================

This is the Core install of Social-Igniter. This repository contains all you need to get an install node up and running on your own *AMP webserver.

To check out a working install of Social-Igniter go to: http://social-igniter.com

Installation Instructions
=========================

* Download or Clone this repository (core Social-Igniter)
* Place Download or Clone in your web servers directory 
* Create a virtual host or route for to this folder (e.g. http://localhost.com)

* Download or Clone https://github.com/socialigniter/system (core CodeIgniter)
	* Make sure /system directory lives at the same folder level as the your app (core Social-Igniter)
	* If not you will need to edit $system_path = '../system'; in the index.php file

* Navigate to the following URL at the domain you setup http://localhost.com/install.php
* Walk through all the steps
* Voila, you should have a working install of Social-Ingiter
* Submit any bugs or fork & send a pull request =)

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