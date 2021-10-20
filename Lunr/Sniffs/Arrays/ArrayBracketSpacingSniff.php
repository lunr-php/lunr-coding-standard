<?php
/**
 * Ensure that there are no spaces around square brackets.
 *
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @copyright 2006-2015 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   https://github.com/squizlabs/PHP_CodeSniffer/blob/master/licence.txt BSD Licence
 */

namespace Lunr\Sniffs\Arrays;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

class ArrayBracketSpacingSniff implements Sniff
{


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return [
            T_OPEN_SHORT_ARRAY,
            T_CLOSE_SHORT_ARRAY,
        ];

    }//end register()


    /**
     * Processes this sniff, when one of its tokens is encountered.
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The current file being checked.
     * @param int                         $stackPtr  The position of the current token in the
     *                                               stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        // Open square brackets must have spaces after them.
        if ($tokens[$stackPtr]['code'] === T_OPEN_SHORT_ARRAY) {
            $nextType = $tokens[($stackPtr + 1)]['code'];
            if (!in_array($nextType, [ T_WHITESPACE, T_OPEN_SHORT_ARRAY, T_CLOSE_SHORT_ARRAY ])) {
                $expected = $tokens[$stackPtr]['content'].' '.$tokens[$stackPtr + 1]['content'];
                $found    = $tokens[$stackPtr]['content'].$tokens[$stackPtr + 1]['content'];
                $error    = 'Space missing after square bracket; expected "%s" but found "%s"';
                $data     = [
                    $expected,
                    $found,
                ];
                $fix      = $phpcsFile->addFixableError($error, $stackPtr, 'MissingSpaceAfterBracket', $data);
                if ($fix === true) {
                    $phpcsFile->fixer->addContentBefore($stackPtr + 1, ' ');
                }
            }
        }

        // Close square brackets must have spaces before them.
        if ($tokens[$stackPtr]['code'] === T_CLOSE_SHORT_ARRAY) {
            $previousType = $tokens[($stackPtr - 1)]['code'];
            if (!in_array($previousType, [ T_WHITESPACE, T_OPEN_SHORT_ARRAY, T_CLOSE_SHORT_ARRAY ])) {
                $expected = $tokens[$stackPtr - 1]['content'].' '.$tokens[$stackPtr]['content'];
                $found    = $tokens[$stackPtr - 1]['content'].$tokens[$stackPtr]['content'];
                $error    = 'Space missing before square bracket; expected "%s" but found "%s"';
                $data     = [
                    $expected,
                    $found,
                ];
                $fix      = $phpcsFile->addFixableError($error, $stackPtr, 'MissingSpaceBeforeBracket', $data);
                if ($fix === true) {
                    $phpcsFile->fixer->addContentBefore($stackPtr, ' ');
                }
            }
        }

    }//end process()


}//end class
