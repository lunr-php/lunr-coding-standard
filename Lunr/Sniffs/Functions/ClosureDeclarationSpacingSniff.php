<?php
/**
 * Checks that closure declarations are spaced correctly.
 *
 * @author    Heinz Wiesinger <heinz.wiesinger@moveagency.com>
 * @copyright 2023 Move Agency Group B.V., Zwolle, The Netherlands
 * @license   https://github.com/squizlabs/PHP_CodeSniffer/blob/master/licence.txt BSD Licence
 */

namespace Lunr\Sniffs\Functions;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Util\Tokens;

class ClosureDeclarationSpacingSniff implements Sniff
{

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return [
            T_CLOSURE,
            T_FN,
        ];

    }//end register()

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token
     *                                               in the stack.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        if (isset($tokens[$stackPtr]['parenthesis_opener']) === false
            || isset($tokens[$stackPtr]['parenthesis_closer']) === false
            || $tokens[$stackPtr]['parenthesis_opener'] === null
            || $tokens[$stackPtr]['parenthesis_closer'] === null
        ) {
            return;
        }

        $openBracket  = $tokens[$stackPtr]['parenthesis_opener'];
        $closeBracket = $tokens[$openBracket]['parenthesis_closer'];

        if ($tokens[$stackPtr]['code'] === T_CLOSURE) {
            $error = 'Expected 1 space(s) between keyword and opening parenthesis of closure declaration; %s found';
            $this->processSpacing($phpcsFile, $stackPtr, $openBracket, $error);

            $openCurly  = $phpcsFile->findNext(T_OPEN_CURLY_BRACKET, ($stackPtr + 1));

            if ($openCurly !== false) {
                $use = $phpcsFile->findNext(T_USE, ($closeBracket + 1), $openCurly);

                if ($use === false) {
                    $error = 'Expected 1 space(s) between closing parenthesis and opening bracket of closure declaration; %s found';
                    $this->processSpacing($phpcsFile, $closeBracket, $openCurly, $error);
                } else {
                    $error = 'Expected 1 space(s) between closing parenthesis and "use" keyword of closure declaration; %s found';
                    $this->processSpacing($phpcsFile, $closeBracket, $use, $error);

                    $use_parenthesis = $phpcsFile->findNext(T_OPEN_PARENTHESIS, ($use + 1), $openCurly);

                    // covered by 'Language constructs must be followed by a single space'
                    // $error = 'Expected 1 space(s) between "use" keyword and opening parenthesis of closure declaration; %s found';
                    // $this->processSpacing($phpcsFile, $use, $use_parenthesis, $error);

                    $error = 'Expected 1 space(s) between closing parenthesis and opening bracket of closure declaration; %s found';
                    $this->processSpacing($phpcsFile, $tokens[$use_parenthesis]['parenthesis_closer'], $openCurly, $error);
                }

                $closeCurly = $tokens[$openCurly]['bracket_closer'];

                $content_start = $phpcsFile->findNext(T_WHITESPACE, ($openCurly + 1), $closeCurly, true);

                if ($content_start !== false) {
                    $error = 'Expected 1 space(s) or linebreak between opening bracket and closure body of closure declaration; %s found';
                    $this->processSpacing($phpcsFile, $openCurly, $content_start, $error, true);

                    $content_end = $phpcsFile->findPrevious(T_WHITESPACE, ($closeCurly - 1), $openCurly, true);

                    $error = 'Expected 1 space(s) or linebreak between closure body and closing bracket of closure declaration; %s found';
                    $this->processSpacing($phpcsFile, $content_end, $closeCurly, $error, true);
                } else {
                    $error = 'Expected 0 space(s) in empty closure body; %s found';
                    $this->processZeroSpacing($phpcsFile, $openCurly, $closeCurly, $error);
                }
            }

            // No params, so we don't check normal spacing rules.
            return;
        }

        if ($tokens[$stackPtr]['code'] === T_FN) {
            $error = 'Expected 0 space(s) between keyword and opening parenthesis of arrow function declaration; %s found';
            $this->processZeroSpacing($phpcsFile, $stackPtr, $openBracket, $error);

            $arrow = $phpcsFile->findNext(T_FN_ARROW, ($stackPtr + 1), $tokens[$stackPtr]['scope_closer']);

            $error = 'Expected 1 space(s) between closing parenthesis and arrow of arrow function declaration; %s found';
            $this->processSpacing($phpcsFile, $closeBracket, $arrow, $error);

            $content_start = $phpcsFile->findNext(T_WHITESPACE, ($arrow + 1), $tokens[$stackPtr]['scope_closer'], true);

            $error = 'Expected 1 space(s) between arrow and body of arrow function declaration; %s found';
            $this->processSpacing($phpcsFile, $arrow, $content_start, $error);
        }

    }//end process()

    private function processSpacing(File $phpcsFile, $left, $right, $error, $accept_newline = false)
    {
        $tokens = $phpcsFile->getTokens();
        $next   = $phpcsFile->findNext(T_WHITESPACE, ($left + 1), $right);

        if ($next === false || $tokens[$next]['length'] != 1) {
            if ($tokens[$left]['line'] !== $tokens[$right]['line']) {
                $found = 'newline';
            } elseif ($next === false) {
                $found = 0;
            } else {
                $found = $tokens[$next]['length'];
            }

            if ($found != 'newline' || $accept_newline === false)
            {
                $data  = [$found];
                $fix   = $phpcsFile->addFixableError($error, $right, 'SpacingBetween', $data);
                if ($fix === true) {
                    if ($next === false) {
                        $phpcsFile->fixer->addContentBefore($right, ' ');
                    } else {
                        $phpcsFile->fixer->replaceToken(($left + 1), ' ');
                    }
                }
            }
        }
    }

    private function processZeroSpacing(File $phpcsFile, $left, $right, $error)
    {
        $tokens = $phpcsFile->getTokens();
        $next   = $phpcsFile->findNext(T_WHITESPACE, ($left + 1), $right);

        if ($next !== false) {
            if ($tokens[$left]['line'] !== $tokens[$right]['line']) {
                $found = 'newline';
            } elseif ($next === false) {
                $found = 0;
            } else {
                $found = $tokens[$next]['length'];
            }

            $data  = [$found];
            $fix   = $phpcsFile->addFixableError($error, $right, 'SpacingBetween', $data);
            if ($fix === true) {
                $phpcsFile->fixer->replaceToken(($left + 1), '');
            }
        }
    }

}
