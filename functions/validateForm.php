<?php

namespace Mizner\VFT\Validate;

function isEmptyNullWhitespace($value)
{
    return (empty($value) || ctype_space($value));
}

function date($value)
{

    return;
}

function isOver21($value)
{
    return;
}

function zip($value)
{
    return (bool)preg_match('#^\d{5}([\-]?\d{4})?$#', $value);
}

function getNearbyZipCodes($zip)
{
    // https://www.zipcodeapi.com/rest/DxeNzBDUm2Py16YKIrdt7r5MfcP9NBJLwsuFqZ5E2WmrRw9YKHSnAogHvXy4aYKQ/radius.json/37919/5/mile
    $base_uri = 'https://zipcodeapi.com/rest';
    $api_key = 'DxeNzBDUm2Py16YKIrdt7r5MfcP9NBJLwsuFqZ5E2WmrRw9YKHSnAogHvXy4aYKQ';
    $radius = '5';
    $unit = 'mile';
    $option = 'radius';
    $format = 'json';
    $full_uri = sprintf(
        '%s/%s/%s.%s/%s/%s/%s',
        $base_uri,
        $api_key,
        $option,
        $format,
        $zip,
        $radius,
        $unit
    );

    _log($full_uri);
    // Get cURL resource
    $curl = curl_init();
// Set some options - we are passing in a useragent too here
    curl_setopt_array($curl, array(
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL            => $full_uri,
        CURLOPT_USERAGENT      => 'Codular Sample cURL Request',
    ));
// Send the request & save response to $resp
    $resp = curl_exec($curl);
// Close request to clear up some resources
    curl_close($curl);
    _log('CURLED:');
    _log($resp);


}

function email($value)
{
    return (bool)filter_var($value, FILTER_VALIDATE_EMAIL);
}

function validateForm($type, $value)
{
    if (isEmptyNullWhitespace($value)) {
        return false;
    }

    switch ($type) {
        case 'email':
            return email($value);
        case 'first':
            return true; // isEmptyNullWhitespace already validated.
        case 'last':
            return true; // isEmptyNullWhitespace already validated.
        case 'zip':
            if (!zip($value)) {
                return false;
            }
            return getNearbyZipCodes($value);
        case 'birthday':
            _log('birthday');
            if (date($value)) {
                isOver21($value);
            };
            break;
    }
}

$result = validateForm('zip', '99999');

_log('RESULT:' . $result);
