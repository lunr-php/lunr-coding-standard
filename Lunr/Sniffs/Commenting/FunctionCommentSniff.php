<?php
/**
 * Parses and verifies the doc comments for functions.
 *
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @copyright 2006-2015 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   https://github.com/squizlabs/PHP_CodeSniffer/blob/master/licence.txt BSD Licence
 */

namespace Lunr\Sniffs\Commenting;

use PHP_CodeSniffer\Standards\Squiz\Sniffs\Commenting\FunctionCommentSniff as SquizFunctionCommentSniff;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Config;
use PHP_CodeSniffer\Util\Common;

class FunctionCommentSniff extends SquizFunctionCommentSniff
{

    /**
     * The current PHP version.
     *
     * @var integer
     */
    private $phpVersion = null;


    /**
     * Process the return comment of this function comment.
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile    The file being scanned.
     * @param int                         $stackPtr     The position of the current token
     *                                                  in the stack passed in $tokens.
     * @param int                         $commentStart The position in the stack where the comment started.
     *
     * @return void
     */
    protected function processReturn(File $phpcsFile, $stackPtr, $commentStart)
    {
        $tokens = $phpcsFile->getTokens();

        // Skip constructor and destructor.
        $methodName      = $phpcsFile->getDeclarationName($stackPtr);
        $isSpecialMethod = in_array($methodName, [ 'setUp', 'tearDown' ]);
        $isTestMethod    = (substr($methodName, 0, 4) === 'test');

        if ($isSpecialMethod === true || $isTestMethod === true) {
            return;
        }

        return parent::processReturn($phpcsFile, $stackPtr, $commentStart);
    }//end processReturn()


}//end class
