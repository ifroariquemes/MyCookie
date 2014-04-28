<?php

/**
 * Returs a translated string if found at domain translation table
 * @param string $message the message
 * @param string $domain the domain
 * @return string
 */
function __($message, $domain) {
    return dgettext($domain, $message);
}

/**
 * Echos a translated string if found at domain translation table
 * @param string $message the message
 * @param string $domain the domain
 */
function _e($message, $domain) {
    echo __($message, $domain);
}

/**
 * Returns the second message if $n > 1 (plural)
 * Use %d in message string to show $n
 * @param string $msgid1 the message for singular
 * @param string $msgid2 the message for plural
 * @param int $n the number
 * @param string $domain the domain
 * @return string
 */
function _n($msgid1, $msgid2, $n, $domain) {
    return dngettext($domain, $msgid1, $msgid2, $n);
}

/**
 * Echos the second message if $n > 1 (plural)
 * Use %d in message string to show $n
 * @param string $msgid1 the message for singular
 * @param string $msgid2 the message for plural
 * @param int $n the number
 * @param string $domain the domain
 * @return string
 */
function _en($msgid1, $msgid2, $n, $domain) {
    echo _n($msgid1, $msgid2, $n, $domain);
}
