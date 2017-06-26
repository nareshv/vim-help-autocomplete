Introduction
============

Make the vim help documentation available via web server in a simple & searchable fashion


Getting Started
===============

1. Install `php` & `php-mysql` support in your faviroute Operating System.
1. Setup mysql server & create a new database called `vim_db`
1. Generate the documentation `make all`
1. Start the webserver for testing `make server`
1. Visit http://127.0.0.1:9090/app/ & enjoy searchable vim help

VIM Help as REST API
===================

The code supports serving the VIM options via REST calls see the `app/repo.php` for more details

Author
======

Naresh

Year
====

2014

License
=======

MIT
