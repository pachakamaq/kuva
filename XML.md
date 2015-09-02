# XML #

XML is used to specify various data, including what would be in a [database](DatabaseIntegration.md) if one were available.  Pages, templates, and configurations are all expressed in XML, and PHP's standard [DOM](http://www.php.net/manual/en/book.dom.php) extension is used to 'understand' them and assemble HTML documents for delivery to the client.

XML files may only contain one level of elements below the root, and any contents of them must be wrapped with <![CDATA[ ]]> tags.  Full XML support is planned, i.e. content which is valid XML should eventually be allowed naked within children of the root, and simply copied over to the new page via PHP's DOM facilities.

## Pages ##
Pages may include `title` and `content` elements; the value of the resulting TITLE variable will be placed in the corresponding HTML tag on the delivered page, while the current template is responsible for placing the CONTENT.  In addition, the following elements may be specified multiple times:
  * `style`: Supplies some CSS to make the delivered page pretty.  Internal styles must be wrapped in `<![CDATA[ ]]>` tags.
    * `src`: This optional attribute, if present, indicates the URL of a CSS file, and causes the XML element to be represented by the HTML `link` element.  Otherwise the HTML appears identical to the XML, except for the `type="text/css"` which is added for both external and internal styles for compatibility with pre-HTML5 browsers.
    * `media`: This optional attribute will be added directly to the corresponding HTML element.

These planned elements will also be allowed multiple instances:
  * `script`: Identical to the HTML element by the same name, except that its contents must be wrapped in a `<![CDATA[ ]]>` tag and the attribute `type="text/javascript"` is added for compatibility with pre-HTML5 browsers.
    * `src`: This optional attribute will be added directly to the corresponding HTML element.

  * `meta`: Identical to the HTML element by the same name, including all attributes.  Values which correspond to HTTP headers will be sent to the browser via HTTP in addition to the HTML elements.

## Templates ##
_Primary documentation: [templates](templates.md)_

The default template specifies, via attributes on its root element, that it uses the MENU and CONTENT variables and is indexable by code-viewers (see [candid](candid.md)) but implicitly not the site menu (see [waiter](waiter.md)).  It also sets the STYLE and BODY variables by including elements of the same names, and elements which may be included multiple times in pages (see above) are also valid in templates.  Note that all variable names are lowercase in XML, as both attribute values and element names.