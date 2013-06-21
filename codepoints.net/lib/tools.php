<?php

/**
 * convert string to array of codepoints
 * @author Scott Reynen
 * @see <http://randomchaos.com/documents/?source=php_and_unicode>
 */
function utf8_to_unicode($str) {
    $unicode = array();
    $values = array();
    $lookingFor = 1;

    for ($i = 0; $i < strlen( $str ); $i++ ) {

        $thisValue = ord( $str[ $i ] );

        if ($thisValue < 128) {
            $unicode[] = $thisValue;
        } else {

            if (count($values) === 0) {
                $lookingFor = ( $thisValue < 224 ) ? 2 : 3;
            }
            $values[] = $thisValue;

            if (count($values) === $lookingFor) {
                $number = ( $lookingFor === 3 ) ?
                    ( ( $values[0] % 16 ) * 4096 ) + ( ( $values[1] % 64 ) * 64 ) + ( $values[2] % 64 ):
                    ( ( $values[0] % 32 ) * 64 ) + ( $values[1] % 64 );

                $unicode[] = $number;
                $values = array();
                $lookingFor = 1;
            }

        }

    }

    return $unicode;
} // utf8_to_unicode


/**
 * convert array of codepoints to UTF-8 string
 * @author Scott Reynen
 * @see <http://randomchaos.com/documents/?source=php_and_unicode>
 */
function unicode_to_utf8($str) {
    $utf8 = '';

    foreach ($str as $unicode) {
        if ($unicode < 128) {

            $utf8.= chr($unicode);

        } elseif ($unicode < 2048) {

            $utf8.= chr( 192 +  ( ( $unicode - ( $unicode % 64 ) ) / 64 ) );
            $utf8.= chr( 128 + ( $unicode % 64 ) );

        } else {

            $utf8.= chr( 224 + ( ( $unicode - ( $unicode % 4096 ) ) / 4096 ) );
            $utf8.= chr( 128 + ( ( ( $unicode % 4096 ) - ( $unicode % 64 ) ) / 64 ) );
            $utf8.= chr( 128 + ( $unicode % 64 ) );

        }
    }

    return $utf8;
} // unicode_to_utf8


/**
 * test, if a string is a possible codepoint
 *
 * Note: We don't test, if this is *really* a codepoint, i.e., connect to
 * the database
 */
function maybeCodepoint($hexstring) {
    if (strlen($hexstring) > 6) {
        return false;
    }
    if (! ctype_xdigit($hexstring)) {
        return false;
    }
    return true;
}


/**
 * Get the scheme+host part of the request
 */
function get_origin() {
    $scheme = 'http';
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] &&
        $_SERVER['HTTPS'] !== 'off') {
        $scheme .= 's';
    }

    if (isset($_SERVER['HTTP_HOST'])) {
        $host = $_SERVER['HTTP_HOST'];
    } else {
        $host = $_SERVER['SERVER_NAME'];
    }
    $host = preg_replace('/[^a-zA-Z0-9.\-:\[\]]/', '', $host);

    return "$scheme://$host/";
}


#EOF