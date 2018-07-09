# Format style
## Generic
- Always use Unix line endings.
- Lines should not be longer than 150 characters.
- Don't use PHP Short open tags, except the short echo tags.
- Use 4 spaces for indentation. NO TABS.
- Every scope change has a new 4 space indentation
- Every file should have a closing PHP tag
- All PHP files must end with a non-blank line, terminated with a single LF.
- When variables are assigned on multiple consecutive lines, the equals signs should be aligned.
- There should never be a space preceding a semicolon.
- Empty lines should not have whitespace.
- There should never be multiple successive empty lines.
- Lines should never end in whitespace.

## Collections
- Arrays should always use the short syntax.
- Arrays spanning multiple lines need to have 1 value per line.
- Associative arrays spanning multiple lines needs to have their key, values and double arrows aligned. With at least one space on either side of the double arrow.
- In multi line arrays each line needs to end in a comma, including the last one.
- In multi line arrays the closing bracket needs to be on its own line, in line with the indentation of the start of the declaration.
- Single line arrays need one space separating each bracket from the values.
```php
$items  = [ 'item', 'more' ];
$config = [
   'field'   => 'fieldB',
   'search'  => '0',
   'replace' => 'b',
];
```


## Naming
- All constants names should be in all uppercase
- Use snake case naming for functions and variables, unless otherwise dictated by libraries or other external influences.
- Class naming should be in Pascal case.

## Comments
- All files should have a file comment
- All classes should have a class comment
- All functions should have a function comment with a description, a list of parameters (with type, name and description) and a return, but no type hints in the function.

## Functions
- In the argument list, there must not be a space before each comma, and there must be one space after each comma.
- Function declarations must follow the "BSD/Allman style". The function brace is on the line following the function declaration and is indented to the same column as the start of the function declaration.
- Functions should always have a scope declaration (public, protected, private)
- There must be an empty line before and after each function in a class.
- There must be a single space after the scope keyword.
- The closing bracket must be directly after the body.
- All function keywords must be lowercase.

## Control structures
- Make sure there are no spaces directly after the opening bracket or before the closing bracket.
- Each line in a multi-line IF statement must begin with a boolean operator
- First condition of a multi-line IF statement must directly follow the opening parenthesis.
- Closing parenthesis of a multi-line IF statement must be on a new line.
- Always use elseif over else if.
- All control structure keywords must be lowercase.
- Control structure bracket spacing should be according to [psr-2/#5-control-structures](https://www.php-fig.org/psr/psr-2/#5-control-structures)

## Classes
- Classes must have their opening bracket on a new line and not have it indented.
- There must be one blank line after the namespace declaration.
- There must be a single space after the scope keyword.
- All class keywords must be lowercase.
- Use declarations should be according to PRS-2  [psr-2/#3-namespace-and-use-declarations](https://www.php-fig.org/psr/psr-2/#3-namespace-and-use-declarations)

## Strings
- Always have one space preceding and succeeding the concatenation symbol.

## Operators
- There must be one space before and after a logical operator.
- All operators should have one space surrounding them.

# Code style
## PHP Function usage
- Some function aliases should not be used.
	- Use count instead of sizeof
	- Use unset instead of delete
- Functions that are deprecated in PHP should not be used.
- Only ever use include_once in conditionals and use require_once otherwise.

## Strings
- String concatenation should not be used if the string could be written as one string.
- Double quotes should only be used when required, always use single quotes instead.

## Operators
- Never use the logical "and" and "or" operators.  

## Functions
- Empty functions should contain a //NO-OP comment.
