<?php

namespace Mizner\VFT\Validate;

use DateTime;

function isEmptyNullWhitespace($value)
{
    return (empty($value) || ctype_space($value));
}

function email($value)
{
    return (bool)filter_var($value, FILTER_VALIDATE_EMAIL);
}

function zip($value)
{
    return (bool)preg_match('#^\d{5}([\-]?\d{4})?$#', $value);
}

function getData($uri)
{
    $ch = curl_init();
    $timeout = 5;
    curl_setopt($ch, CURLOPT_URL, $uri);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    $data = curl_exec($ch);
    curl_close($ch);

    return $data;
}

function zipCodeApiRadiusURI($args)
{
    return sprintf(
        'https://www.zipcodeapi.com/rest/%s/%s.%s/%s/%s/%s',
        $args['api_key'],
        $args['option'],
        $args['format'],
        $args['zip'],
        $args['radius'],
        $args['unit']
    );
}

function zipCodeAPIValidate($data)
{
    return (!in_array('404', (array)json_decode($data)));
}

function date($date, $format = 'm/d/Y')
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}

function validateAge($value, $age = 21)
{
    $now = (new DateTime())->format('m/d/Y');
    $end = DateTime::createFromFormat('m/d/Y', $now);
    $start = DateTime::createFromFormat('m/d/Y', $value);
    $diff = $start->diff($end);

    return ((int)$diff->format('%y') >= $age ? true : false);
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
            return zipCodeAPIValidate(
                getData(
                    zipCodeApiRadiusURI([
                        'api_key' => 'DxeNzBDUm2Py16YKIrdt7r5MfcP9NBJLwsuFqZ5E2WmrRw9YKHSnAogHvXy4aYKQ',
                        'radius'  => '5',
                        'unit'    => 'mile',
                        'zip'     => $value,
                        'option'  => 'radius',
                        'format'  => 'json',
                    ])
                )
            );
        case 'birthday':
            if (!date($value)) {
                return false;
            };
            return validateAge($value);
    }
}

$result = validateForm('birthday', '08/09/1999');

_log('RESULT:');
_log($result);
