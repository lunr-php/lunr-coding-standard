<?php
/**
 * Lunr Coding Standard
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Heinz Wiesinger
 */

if (class_exists( 'PHP_CodeSniffer_Standards_CodingStandard', true ) === false) {
    throw new PHP_CodeSniffer_Exception('Class PHP_CodeSniffer_Standards_CodingStandard not found');
}

/**
 * Lunr Coding Standard
 *
 * Return a selection of default sniffs, followed by everything in the Lunr directory
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Heinz Wiesinger
 */
class PHP_CodeSniffer_Standards_Lunr_LunrCodingStandard extends PHP_CodeSniffer_Standards_CodingStandard
{
    public function getExcludedSniffs()
    {
        return array(
/*            'Squiz/Sniffs/WhiteSpace/ScopeIndentSniff.php',
            'Squiz/Sniffs/WhiteSpace/ControlStructureSpacingSniff.php',
            'Squiz/Sniffs/WhiteSpace/ScopeClosingBraceSniff.php',
            'Squiz/Sniffs/WhiteSpace/LanguageConstructSpacingSniff.php',
            'Squiz/Sniffs/WhiteSpace/FunctionSpacingSniff.php',
            'Squiz/Sniffs/WhiteSpace/FunctionClosingBraceSpaceSniff.php',
            'Squiz/Sniffs/WhiteSpace/OperatorSpacingSniff.php',
            'Squiz/Sniffs/WhiteSpace/MemberVarSpacingSniff.php'*/
        );
    }

    public function getIncludedSniffs()
    {
        return array(
                'Generic/Sniffs/Files/LineLengthSniff.php',
                '/Generic/Sniffs/WhiteSpace/ScopeIndentSniff.php',

                'PEAR/Sniffs/Functions/FunctionCallSignatureSniff.php',
                'PEAR/Sniffs/Functions/FunctionCallArgumentSpacingSniff.php',
                'PEAR/Sniffs/Commenting/FunctionCommentSniff.php',
                'PEAR/Sniffs/Commenting/FileCommentSniff.php',

                'Lunr/Sniffs'
                );
    }
}

?>