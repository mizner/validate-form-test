<?php

namespace Mizner\VFT\Validate;

use DateTime;

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

function isEmptyNullWhitespace($value)
{
    return (empty($value) || ctype_space($value));
}

function validateEmail($value)
{
    return (bool)filter_var($value, FILTER_VALIDATE_EMAIL);
}

function validateZip($value)
{
    return (bool)preg_match('#^\d{5}([\-]?\d{4})?$#', $value);
}

function zipCodeAreaURI($args)
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

function validateZipCodeArea($data)
{
    return (!in_array('404', (array)json_decode($data)));
}

function validateDate($date, $format = 'm/d/Y')
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
            return validateEmail($value);
        case 'first':
            return true; // isEmptyNullWhitespace already validated.
        case 'last':
            return true; // isEmptyNullWhitespace already validated.
        case 'zip':
            if (!validateZip($value)) {
                return false;
            }
            return validateZipCodeArea(
                getData(
                    zipCodeAreaURI([
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
            if (!validateDate($value)) {
                return false;
            };
            return validateAge($value);
    }
}

