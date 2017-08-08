<?php

class Utils
{
    /**
     * Convert a string to camel case, optionally capitalizing the first char and optionally setting which characters are
     * acceptable.
     *
     * First, take existing camel case and add a space between each word so that it is in Title Form; note that
     *   consecutive capitals (acronyms) are considered a single word.
     * Second, capture all contigious words, capitalize the first letter and then convert the rest into lower case.
     * Third, strip out all the non-desirable characters (i.e, non numerics).
     *
     * EXAMPLES:
     * $str = 'Please_RSVP: b4 you-all arrive!';
     *
     * To convert a string to camel case:
     *  strToCamel($str); // gives: PleaseRsvpB4YouAllArrive
     *
     * To convert a string to an acronym:
     *  strToCamel($str, true, 'A-Z'); // gives: PRBYAA
     *
     * To convert a string to first-lower camel case without numerics but with underscores:
     *  strToCamel($str, false, 'A-Za-z_'); // gives: please_RsvpBYouAllArrive
     *
     * @param  string  $str              text to convert to camel case.
     * @param  bool    $capitalizeFirst  optional. whether to capitalize the first chare (e.g. "camelCase" vs. "CamelCase").
     * @param  string  $allowed          optional. regex of the chars to allow in the final string
     *
     * @return string camel cased result
     *
     * @author Sean P. O. MacCath-Moran   www.emanaton.com
     */
    public static function strToCamel($str, $capitalizeFirst = true, $allowed = 'A-Za-z0-9')
    {
        return preg_replace(
            array(
                '/([A-Z][a-z])/e', // all occurances of caps followed by lowers
                '/([a-zA-Z])([a-zA-Z]*)/e', // all occurances of words w/ first char captured separately
                '/[^'.$allowed.']+/e', // all non allowed chars (non alpha numerics, by default)
                '/^([a-zA-Z])/e' // first alpha char
            ),
            array(
                '" ".$1', // add spaces
                'strtoupper("$1").strtolower("$2")', // capitalize first, lower the rest
                '', // delete undesired chars
                'strto'.($capitalizeFirst ? 'upper' : 'lower').'("$1")' // force first char to upper or lower
            ),
            $str
        );
    }
}