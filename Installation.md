# Installation #

This page details the standard process for installing Kuva.  Other methods are not supported, and Kuva will likely break if you deviate from these instructions because it is rather rigid about where things may be placed.

First you need to checkout the [core](core.md) module from Subversion to your server's webroot.  The first line in this example assumes that you have previously run the Linux command `tasksel install lamp-server` and wish to run Kuva in the resulting Apache installation.
```
cd /var/www
svn checkout http://kuva.googlecode.com/svn/trunk/core/ kuva
```

Now we should move index.php from the core module's folder to the webroot so it may process all HTTP requests (for the webroot).  This example is also designed for Linux.
```
mv kuva/index.php .
```

That's it!  Now that Kuva is installed, you should refer to the instructions on how to use [XML](XML.md) to author pages which Kuva understands.  You will probably also be interested in [extensions](Extensions.md) which offer more convenient features than the core module does by itself.