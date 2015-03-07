ADUCID PHP SDK testing application
==================================

This directory contains an application, whitch allows to test several ADUCID operations
like identity use or transaction. It suppose to be used for ADUCID PHP SDK testing,
but of course, can be used as an example of ADUCID use.

Installation
------------

1. Install webserver with php support (Apache HTTPd)
2. Install ADUCID PHP SDK on this webserver, including dependecies ( php >= 5.1.0, php-soap, php-xml ).
3. Install ADUCID AIM server.
4. Copy this application under your DOCUMENT_ROOT

        mkdir /var/www/html/demo
        cp -R * /var/www/html/demo/

5. Make sure that webserver can read those files.
6. Edit the config.php file and set AIM address.
7. Access the application in Your browser ( http://your.server/demo/ )

