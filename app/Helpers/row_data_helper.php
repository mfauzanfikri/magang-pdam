<?php

if (!function_exists('encode_row_data')) {
    /**
     * Encode an array or object into a URL-safe string.
     *
     * @param  array|object  $data
     * @return string|null
     */
    function encode_row_data($data)
    {
        try {
            return rawurlencode(json_encode($data, JSON_UNESCAPED_UNICODE));
        } catch (Exception $e) {
            log_message('error', 'Failed to encode row data: ' . $e->getMessage());
            return null;
        }
    }
}

if (!function_exists('decode_row_data')) {
    /**
     * Decode a URL-safe encoded row string into an associative array.
     *
     * @param  string  $encoded
     * @return array|null
     */
    function decode_row_data($encoded)
    {
        try {
            return json_decode(rawurldecode($encoded), true);
        } catch (Exception $e) {
            log_message('error', 'Failed to decode row data: ' . $e->getMessage());
            return null;
        }
    }
}
