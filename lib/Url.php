<?php
/**
 * Url utility class
 *
 * @author Mikhail Miropolskiy <the-ms@ya.ru>
 * @package Core
 * @copyright (c) 2016. Mikhail Miropolskiy. All Rights Reserved.
 */
namespace lib;

class Url {
    /**
     * Add param to url
     * @param string $url
     * @param string $param Param name
     * @param string $value Param value
     * @return string New url
     */
    public static function addUrlParam($url, $param, $value) {
        if (strpos($url, '?')) {
            list($path, $params) = explode('?', $url);
            parse_str($params, $params);
            $params[$param] = $value;
            return $path . '?' . http_build_query($params);
        }

        return $url . '?' . $param . '=' . $value;
    }

    /**
     * Create HTML link
     * @param string $url
     * @param string $text
     * @param bool $isActive Create link or text (if active)
     * @param string $class Css class name
     * @return string
     */
    public static function createLink($url, $text, $isActive = false, $class = '') {
        return $isActive
            ? '<span class="' . $class . '">' . $text . '</span>'
            : '<a href="' . $url . '" class="' . $class . '">' . $text . '</a>';
    }
}