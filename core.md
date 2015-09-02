# core #

The ''core'' module handles all of Kuva's essential business, and consists of two files, index.php and config.xml, and a directory [templates](templates.md).  The directory containing these files should be placed in your webroot and named "kuva" (e.g., example.com/kuva) and index.php should be moved to the webroot as well.

To tell index.php how to handle a request, you must craft the URL such that the query-string specifies which part of the site to access, e.g. example.com/index.php?/path/to/file.php.  But index.php is implied as the requested file when none is specified on most servers, so the prettiest type of URL places the ? directly after the first slash as if it were a directory name: example.com/?/path/to/file.php.  The first slash after the ? is ignored but, in the author's opinion, looks better.  If you can use a feature such as Apache's mod\_rewrite, you may make your site appear as an ordinary directory structure to the outside world.

index.php takes the specified path, replaces any _.php_ file extension with _.xml_, and passes the contents through the PHP parser.  The output is loaded as XML and certain parts are located and placed at the appropriate positions within the resulting HTML document.

This placement is the phase where [templates](templates.md) become relevant, and you should understand how they work if you plan to administer a site with Kuva.  Content-authors need not be concerned about templates for the most part, although knowledge of [XML](XML.md) is essential if they will be editing pages directly.