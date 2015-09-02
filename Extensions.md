# Extensions #

This page lists all existing extensions and those which are planned, along with descriptions and the names of their developers.  Guidelines for all extension authors are provided at the page's bottom.

## Existing extensions ##

### Version control ###

  * [phpsvnclient](phpsvnclient.md): Interfaces with [phpsvnclient](http://code.google.com/p/phpsvnclient/) to update site modules.
    * [Jesdisciple](http://code.google.com/u/jesdisciple/)

## Planned extensions ##

### Site indexing ###

  * [waiter](waiter.md): Assembles a site-wide menu based on the filesystem; may be configured to ignore certain files and/or to use replacements for specific ones.
    * [Jesdisciple](http://code.google.com/u/jesdisciple/)

### Code display ###

  * [candid](candid.md): Displays the source of all non-restricted files, and adds a link for each page's source to the HTML when that page is requested.  Also links to the GPLv2 at every opportunity.  Will likely depend upon an [indexer](#Site_indexing.md) other than itself to avoid redundancy of expensive routines.
    * [Jesdisciple](http://code.google.com/u/jesdisciple/)

## Guidelines ##

These guidelines are intended to direct the development of all extensions in a way that benefits the project and its users.  Note that these are in addition to those listed for [developers](Developing.md) in general (although those are not as pertinent to extension developers).
  * Do not use generic names such as "database" or "cvs"; this way multiple extensions may accomplish the same task without any being labeled **the** extension for that task because of its name.  For example, the existing "phpsvnclient" probably ought to be renamed so other extensions may be developed which use the phpsvnclient library without being second-class citizens.
  * Release your code under GPLv2 and your documentation under CC-BY-SA 3.0, then publish them to Kuva's SVN repository (in /trunk/modules and /wiki).  This will keep licensing consistent, so someone who decides to use Kuva doesn't need to comply with a different license for each extension.