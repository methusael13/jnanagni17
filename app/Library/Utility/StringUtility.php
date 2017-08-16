<?php

namespace jnanagni\Library\Utility;

class StringUtility {
    protected static $TAGS_SHORT = array('[@]', '[!@]', '[$]', '[!$]');
    protected static $TAGS_HTML = array(
        '<div class="section-title">',
        '</div>',
        '<div class="section-text">',
        '</div>'
    );

    public static function capitalize($text) {
        return ucwords(strtolower(trim($text)));
    }

    public static function parseTags($text) {
        return str_replace(self::$TAGS_SHORT, self::$TAGS_HTML, $text);
    }
}
