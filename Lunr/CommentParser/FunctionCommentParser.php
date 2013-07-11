<?php
/**
 * Parses function doc comments.
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

if (class_exists('PHP_CodeSniffer_CommentParser_FunctionCommentParser', true) === false) {
    $error = 'Class PHP_CodeSniffer_CommentParser_FunctionCommentParser not found';
    throw new PHP_CodeSniffer_Exception($error);
}

if (class_exists('PHP_CodeSniffer_CommentParser_ParameterElement', true) === false) {
    $error = 'Class PHP_CodeSniffer_CommentParser_ParameterElement not found';
    throw new PHP_CodeSniffer_Exception($error);
}

if (class_exists('PHP_CodeSniffer_CommentParser_PairElement', true) === false) {
    $error = 'Class PHP_CodeSniffer_CommentParser_PairElement not found';
    throw new PHP_CodeSniffer_Exception($error);
}

if (class_exists('PHP_CodeSniffer_CommentParser_SingleElement', true) === false) {
    $error = 'Class PHP_CodeSniffer_CommentParser_SingleElement not found';
    throw new PHP_CodeSniffer_Exception($error);
}

/**
 * Parses function doc comments.
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
class Lunr_CommentParser_FunctionCommentParser extends PHP_CodeSniffer_CommentParser_FunctionCommentParser
{

    /**
     * The covers element of this class.
     *
     * @var SingleElement
     */
    private $_covers = null;

    /**
     * The dataProvder element of this class.
     *
     * @var SingleElement
     */
    private $_dataProvider = null;

    /**
     * The depends elements of this class.
     *
     * @var array(SingleElement)
     */
    private $_depends = array();

    /**
     * The dataProvder element of this class.
     *
     * @var SingleElement
     */
    private $_expectedException = null;

    /**
     * Constructs a PHP_CodeSniffer_CommentParser_FunctionCommentParser.
     *
     * @param string               $comment   The comment to parse.
     * @param PHP_CodeSniffer_File $phpcsFile The file that this comment is in.
     */
    public function __construct($comment, PHP_CodeSniffer_File $phpcsFile)
    {
        parent::__construct($comment, $phpcsFile);

    }//end __construct()


    /**
     * Parses covers elements.
     *
     * @param array(string) $tokens The tokens that comprise this sub element.
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

    }//end parseThrows()


    /**
     * Parses covers elements.
     *
     * @param array(string) $tokens The tokens that comprise this sub element.
     *
     * @return PHP_CodeSniffer_CommentParser_PairElement
     */
    protected function parseExpectedException($tokens)
    {
        $this->_expectedException = new PHP_CodeSniffer_CommentParser_SingleElement(
            $this->previousElement,
            $tokens,
            'expectedException',
            $this->phpcsFile
        );

        return $this->_expectedException;

    }//end parseThrows()


    /**
     * Parses dataProvider elements.
     *
     * @param array(string) $tokens The tokens that comprise this sub element.
     *
     * @return PHP_CodeSniffer_CommentParser_PairElement
     */
    protected function parseDataProvider($tokens)
    {
        $this->_dataProvider = new PHP_CodeSniffer_CommentParser_SingleElement(
            $this->previousElement,
            $tokens,
            'dataProvider',
            $this->phpcsFile
        );

        return $this->_dataProvider;

    }//end parseThrows()


    /**
     * Parses the depends elements.
     *
     * @param array $tokens The tokens that comprise this tag.
     *
     * @return array(PHP_CodeSniffer_CommentParser_SingleElement)
     */
    protected function parseDepends($tokens)
    {
        $depends = new PHP_CodeSniffer_CommentParser_SingleElement(
            $this->previousElement,
            $tokens,
            'depends',
            $this->phpcsFile
        );

        $this->_depends[] = $depends;
        return $depends;

    }//end parseAuthor()

    /**
     * Returns the parameter elements that this function comment contains.
     *
     * Returns an empty array if no parameter elements are contained within
     * this function comment.
     *
     * @return array(PHP_CodeSniffer_CommentParser_ParameterElement)
     */
    public function getCovers()
    {
        return $this->_covers;

    }//end getParams()


    /**
     * Returns the return element in this function comment.
     *
     * Returns null if no return element exists in the comment.
     *
     * @return PHP_CodeSniffer_CommentParser_PairElement
     */
    public function getDataProvider()
    {
        return $this->_dataProvider;

    }//end getReturn()


    /**
     * Returns the throws elements in this function comment.
     *
     * Returns empty array if no throws elements in the comment.
     *
     * @return array(PHP_CodeSniffer_CommentParser_PairElement)
     */
    public function getDepends()
    {
        return $this->_depends;

    }//end getThrows()


    /**
     * Returns the throws elements in this function comment.
     *
     * Returns empty array if no throws elements in the comment.
     *
     * @return array(PHP_CodeSniffer_CommentParser_PairElement)
     */
    public function getExpectedException()
    {
        return $this->_expectedException;

    }//end getThrows()


    /**
     * Returns the allowed tags that can exist in a function comment.
     *
     * @return array(string => boolean)
     */
    protected function getAllowedTags()
    {
        return array(
                'param'  => false,
                'return' => true,
                'throws' => false,
                'covers' => false,
                'dataProvider' => false,
                'depends' => false,
                'expectedException' => false,
               );

    }//end getAllowedTags()


}//end class

?>
