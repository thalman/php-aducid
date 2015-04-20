ADUCID PHP SDK demo applications
================================

This directory contains demo applications. The main purpose
of those applications is to show how to use ADUCID in the most simple
cases.

Applications are as simple as possible, just to show ADUCID and not to
bother with other details.

Installation
------------

1. Install WEB server with PHP support (Apache HTTPd)
2. Install ADUCID PHP SDK on this WEB server, including dependencies ( PHP >= 5.1.0, php-soap, php-xml ).
3. Install ADUCID AIM server.
4. Copy this application under your DOCUMENT_ROOT

        mkdir /var/www/html/aducid-demo
        cp -R * /var/www/html/aducid-demo/

5. Make sure that webserver can read those files.
6. Edit the config.php file and set AIM address.
7. Access the application in Your browser ( http://your.server/aducid-demo/ )

