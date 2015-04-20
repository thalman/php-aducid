php-alucid
==========

PHP SDK for ADUCID authentication

Getting PHP SDK
---------------
Clone from github

    git clone https://github.com/thalman/php-aducid.git

Directory structure
-------------------

* doc/demos - demo application, it demonstrates use of ADUCID product, designated to PHP developers 
* src - PHP SDK source code
* tests - testing applications
* tools - helper tools like RPM spec file or Java to PHP enum conversion script 

Manual installation
-------------------

1. First check that all necessary libraries are installed ( php >= 5.1.0, php-soap, php-xml ).
2. Check what is Your php include_path (Points to /usr/share/php on CentOS) an choose the
   installation directory (/usr/share/php/aducid).
3. Copy aducid.php and aducidenums.php to this directory.
4. Now You can use aducid in Your application.

        include_once "aducid/aducid.php";
        aducidRequire(3.0);
 
Creating RPM package
--------------------

There is SPEC file in tools directory. Creating rpm from tar archive is easy.

1. Download/clone ADUCID PHP SDK into php-aducid directory.
2. Pack the directory into tar archive

        tar -czf php-aducid.tgz php-aducid

3. Build rpm package

        rpmbuild -ta php-aducid.tgz
