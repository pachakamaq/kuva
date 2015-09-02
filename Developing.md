# Developing Kuva #

Kuva is a very young project and therefore relatively easy to hack on.  However, please follow these guidelines in the interest of maintainability:
  * Keep the format of your code consistent with existing code.  So far this means:
    * Use Java-style curly brackets and indentation.
    * Indent four spaces for each level (and a tab is not equivalent to four spaces).
    * Indent the closing bracket in CSS statements to the same level as the declarations, and an entire statement to the same level as the declarations of another statement which could be considered hierarchically 'above' it.
    * Include spaces around binary operators such as + - `*` / . && || & | and => and between the : and value in CSS declarations, but not around property accessors :: and ->, round/curly/square brackets () {} `[]`, or unary operators like ! or - (the negative sign).
  * Enclose PHP strings in single-quotes rather than double unless you intend PHP to subsitute variables into them.
  * Enclose XML and HTML attribute values in double-quotes.
  * Document variables and all functions with Javadoc-type comments, i.e.:
```
/**
 * void document(string $identifier, string $description)
 * 
 * Document your code!
 */
```
  * Additionally comment your code wherever it might be unclear, and notify fellow developers when this is necessary but they haven't done it.