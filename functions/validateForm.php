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
    return;
}

function generic($value)
{

    return;
}

function email($value)
{
    return (boolval(filter_var($value, FILTER_VALIDATE_EMAIL)));
}

function validateForm($type, $value)
{
    if (isEmptyNullWhitespace($value)) {
        return false;
    }

    switch ($type) {
        case 'email':
            return email($value);
            break;
        case 'first':
            _log('first');
            generic($value);
            break;
        case 'last':
            _log('last');
            generic($value);
            break;
        case 'zip':
            _log('zip');
            zip($value);
            break;
        case 'birthday':
            _log('birthday');
            if (date($value)) {
                isOver21($value);
            };
            break;
    }
}

$result = validateForm('email', 'michaelmizner@gmail.com');

_log('RESULT:' . $result);
