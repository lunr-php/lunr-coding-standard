<?php
/**
 * Parses Class doc comments.
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @author    Marc McIntyre <mmcintyre@squiz.net>
 * @copyright 2006-2012 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   https://github.com/squizlabs/PHP_CodeSniffer/blob/master/licence.txt BSD Licence
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */

if (class_exists('PHP_CodeSniffer_CommentParser_ClassCommentParser', true) === false) {
    $error = 'PHP_CodeSniffer_CommentParser_ClassCommentParser not found';
    throw new PHP_CodeSniffer_Exception($error);
}

/**
 * Parses Class doc comments.
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @author    Marc McIntyre <mmcintyre@squiz.net>
 * @copyright 2006-2012 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   https://github.com/squizlabs/PHP_CodeSniffer/blob/master/licence.txt BSD Licence
 * @version   Release: 1.4.5
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */
class Lunr_CommentParser_ClassCommentParser extends PHP_CodeSniffer_CommentParser_ClassCommentParser
{

    /**
     * The backupGlobals element of this class.
     *
     * @var SingleElement
     */
    private $_backupGlobals = null;

    /**
     * The covers element of this class.
     *
     * @var SingleElement
     */
    private $_covers = null;

    /**
     * Returns the allowed tags withing a class comment.
     *
     * @return array(string => int)
     */
    protected function getAllowedTags()
    {
        return array(
                'category'   => false,
                'package'    => true,
                'subpackage' => true,
                'author'     => false,
                'copyright'  => true,
                'license'    => false,
                'version'    => true,
            'backupGlobals' => false,
            'covers'        => false,
        );

    }//end getAllowedTags()


    /**
     * Parses the backupGlobals tag of this class comment.
     *
     * @param array $tokens The tokens that comprise this tag.
     *
     * @return PHP_CodeSniffer_CommentParser_PairElement
     */
    protected function parseBackupGlobals($tokens)
    {
        $this->_backupGlobals = new PHP_CodeSniffer_CommentParser_SingleElement(
            $this->previousElement,
            $tokens,
            'backupGlobals',
            $this->phpcsFile
        );

        return $this->_backupGlobals;

    }//end parseLicense()

    /**
     * Parses the covers tag of this class comment.
     *
     * @param array $tokens The tokens that comprise this tag.
     *
     * @return PHP_CodeSniffer_CommentParser_PairElement
     */
    protected function parseCovers($tokens)
    {
        $this->_covers = new PHP_CodeSniffer_CommentParser_SingleElement(
            $this->previousElement,
            $tokens,
            'covers',
            $this->phpcsFile
        );

        return $this->_covers;

    }//end parseLicense()

    /**
     * Returns the backupGlobals of this class comment.
     *
     * @return array(PHP_CodeSniffer_CommentParser_SingleElement)
     */
    public function getBackupGlobals()
    {
        return $this->_backupGlobals;

    }//end getAuthors()


    /**
     * Returns the covers of this class comment.
     *
     * @return PHP_CodeSniffer_CommentParser_SingleElement
     */
    public function getCovers()
    {
        return $this->_covers;

    }//end getVersion()


}//end class

?>
