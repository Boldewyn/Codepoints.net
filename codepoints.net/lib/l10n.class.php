<?php


require_once __DIR__."/vendor/php-gettext/php-gettext/gettext.inc";


/**
 * lazy gettext: do nothing, translation happens later
 */
function _n($s) {
    return $s;
}


/**
 * wrapper for php_gettext
 */
class L10n {

    /**
     * the language in use, default is english
     */
    protected $lang = "en";

    /**
     * the message domain
     */
    protected $domain = "messages";

    /**
     * the cached L10n instances
     */
    protected static $instances = array();

    /**
     * construct a new l10n wrapper
     */
    public function __construct($lang=null, $domain=null) {
        $this->setLanguage($lang);
        if ($domain) {
            $this->domain = $domain;
        }
        $this->setDomain($this->domain);
    }

    /**
     * set the language in use
     */
    public function setLanguage($lang=null) {
        if (! $lang) {
            $lang = L10n::getDefaultLanguage();
        }
        $this->lang = $lang;
        _setlocale(LC_MESSAGES, $lang);
    }

    /**
     * get the language
     */
    public function getLanguage() {
        return $this->lang;
    }

    /**
     * set the domain in use
     */
    public function setDomain($domain) {
        $this->domain = $domain;
        _bindtextdomain($domain, realpath('./locale'));
        _bind_textdomain_codeset($domain, 'UTF-8');
    }

    /**
     * translate a string
     */
    public function gettext($s) {
        $l10n = _get_reader($this->domain);
        return $l10n->translate($s);
    }

    /**
     * translate a singular/plural string
     */
    public function ngettext($singular, $plural, $number) {
        $l10n = _get_reader($this->domain);
        return $l10n->ngettext($singular, $plural, $number);
    }

    /**
     * singleton getter
     */
    public static function get($domain="messages") {
        if (! array_key_exists($domain, self::$instances)) {
            self::$instances[$domain] = new self(null, $domain);
        }
        return self::$instances[$domain];
    }

    /**
     * get the language in use
     *
     * Also does persistance, ie., if GET param is found, copy to cookie
     */
    public static function getDefaultLanguage() {
        $lang = "en";
        if (isset($_GET['lang']) && ctype_alpha($_GET['lang'])) {
            $lang = $_GET['lang'];
            setcookie('lang', $lang, time()+60*60*24*3650, '/');
        } elseif (isset($_COOKIE['lang']) && ctype_alpha($_COOKIE['lang'])) {
            $lang = $_COOKIE['lang'];
        } elseif (function_exists('http_negotiate_language')) {
            $lang = http_negotiate_language(array('en', 'de', 'pl'));
        }
        return $lang;
    }

}


if (! function_exists('__')) {
    function __($s) {
        return L10n::get('messages')->gettext($s);
    }
}


//__END__
