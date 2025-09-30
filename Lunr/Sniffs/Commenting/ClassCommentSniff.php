<?php
/**
 * Parses and verifies the class doc comment.
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @copyright 2006-2014 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   https://github.com/squizlabs/PHP_CodeSniffer/blob/master/licence.txt BSD Licence
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */
 namespace Lunr\Sniffs\Commenting;

 use PHP_CodeSniffer\Sniffs\Sniff;
 use PHP_CodeSniffer\Files\File;
 use PHP_CodeSniffer\Util\Tokens;

/**
 * Parses and verifies the class doc comment.
 *
 * Verifies that :
 * <ul>
 *  <li>A class doc comment exists.</li>
 *  <li>There is exactly one blank line before the class comment.</li>
 *  <li>There are no blank lines after the class comment.</li>
 *  <li>Short and long descriptions end with a full stop and start with capital letter.</li>
 *  <li>There is a blank line between descriptions.</li>
 * </ul>
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @copyright 2006-2014 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   https://github.com/squizlabs/PHP_CodeSniffer/blob/master/licence.txt BSD Licence
 * @version   Release: @package_version@
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */
class ClassCommentSniff implements Sniff
{

    private $phpunit_tags = [
        '@depends'       => false,
        '@covers'        => true,
        '@backupGlobals' => false,
    ];

    private $magic_tags = [
        '@property'       => false,
        '@property-read'  => false,
        '@property-write' => false,
        '@method'         => false,
        '@mixin'          => false,
    ];

    private $generics_tags = [
        '@template'           => false,
        '@template-covariant' => false,
        '@extends'            => false,
        '@implements'         => false,
        '@deprecated'         => false,
    ];

    private $phpstan_tags = [
        '@phpstan-type'        => false,
        '@phpstan-import-type' => false,
    ];

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return [T_CLASS];

    }//end register()


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token
     *                                        in the stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        $find   = [
            T_ABSTRACT   => T_ABSTRACT,
            T_FINAL      => T_FINAL,
            T_READONLY   => T_READONLY,
            T_WHITESPACE => T_WHITESPACE,
        ];

        $commentEnd = $phpcsFile->findPrevious($find, ($stackPtr - 1), null, true);
        if ($tokens[$commentEnd]['code'] !== T_DOC_COMMENT_CLOSE_TAG
            && $tokens[$commentEnd]['code'] !== T_COMMENT
        ) {
            $phpcsFile->addError('Missing class doc comment', $stackPtr, 'Missing');
            return;
        }

        // Try and determine if this is a file comment instead of a class comment.
        // We assume that if this is the first comment after the open PHP tag, then
        // it is most likely a file comment instead of a class comment.
        if ($tokens[$commentEnd]['code'] === T_DOC_COMMENT_CLOSE_TAG) {
            $start = ($tokens[$commentEnd]['comment_opener'] - 1);
        } else {
            $start = $phpcsFile->findPrevious(T_COMMENT, ($commentEnd - 1), null, true);
        }

        $prev = $phpcsFile->findPrevious(T_WHITESPACE, $start, null, true);
        if ($tokens[$prev]['code'] === T_OPEN_TAG) {
            $prevOpen = $phpcsFile->findPrevious(T_OPEN_TAG, ($prev - 1));
            if ($prevOpen === false) {
                // This is a comment directly after the first open tag,
                // so probably a file comment.
                $phpcsFile->addError('Missing class doc comment', $stackPtr, 'Missing');
                return;
            }
        }

        if ($tokens[$commentEnd]['code'] === T_COMMENT) {
            $phpcsFile->addError('You must use "/**" style comments for a class comment', $stackPtr, 'WrongStyle');
            return;
        }

        if ($tokens[$commentEnd]['line'] !== ($tokens[$stackPtr]['line'] - 1)) {
            $error = 'There must be no blank lines after the class comment';
            $phpcsFile->addError($error, $commentEnd, 'SpacingAfter');
        }

        $commentStart = $tokens[$commentEnd]['comment_opener'];
        if ($tokens[$prev]['line'] !== ($tokens[$commentStart]['line'] - 2)) {
            $error = 'There must be exactly one blank line before the class comment';
            $phpcsFile->addError($error, $commentStart, 'SpacingBefore');
        }

        $classname  = $tokens[$stackPtr + 2]['content'];

        $is_test    = str_ends_with($classname, 'Test') || str_ends_with($classname, 'TestCase');

        $phpunit_tag_keys  = array_keys($this->phpunit_tags);
        $magic_tag_keys    = array_keys($this->magic_tags);
        $generics_tag_keys = array_keys($this->generics_tags);
        $phpstan_tag_keys  = array_keys($this->phpstan_tags);

        $handled = [];

        foreach ($tokens[$commentStart]['comment_tags'] as $tag) {
            $name = $tokens[$tag]['content'];

            if (in_array($name, $phpunit_tag_keys)) {
                if ($is_test === TRUE) {
                    $handled[] = $name;
                    continue;
                } else {
                    $error = '%s tag in non-test class';
                    $data  = array($tokens[$tag]['content']);
                    $phpcsFile->addError($error, $tag, 'TagNotAllowed', $data);
                    continue;
                }
            }
            elseif (in_array($name, $magic_tag_keys))
            {
                $handled[] = $name;
                continue;
            }
            elseif (in_array($name, $generics_tag_keys))
            {
                $handled[] = $name;
                continue;
            }
            elseif (in_array($name, $phpstan_tag_keys))
            {
                $handled[] = $name;
                continue;
            }

            $error = '%s tag is not allowed in class comment';
            $data  = array($tokens[$tag]['content']);
            $phpcsFile->addWarning($error, $tag, 'TagNotAllowed', $data);
        }

        if ($is_test === FALSE) {
            return;
        }

        $namespace_position     = $phpcsFile->findPrevious(T_NAMESPACE, $stackPtr);

        $is_halo_class             = str_contains($tokens[$namespace_position + 2]['content'], '\Halo\\') || str_ends_with($tokens[$namespace_position + 2]['content'], '\Halo');
        $is_unit_test_helper_class = str_ends_with($tokens[$namespace_position + 2]['content'], '\Helpers');

        foreach ($this->phpunit_tags as $tag => $required) {
            if ($required && !in_array($tag, $handled)) {
                if ((!$is_halo_class && !$is_unit_test_helper_class) || $tag != '@covers') {
                    $error = 'Missing %s tag in test class comment';
                    $data  = array($tag);
                    $phpcsFile->addError($error, $commentEnd, 'Missing'.ucfirst(substr($tag, 1)).'Tag', $data);
                }
            }
        }

    }//end process()


}//end class
