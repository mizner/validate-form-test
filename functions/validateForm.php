<?php

namespace Mizner\VFT\Validate;

function emptyNullWhitespace($question)
{
    return (!isset($question) || trim($question) === '');
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
    return (filter_var($value, FILTER_VALIDATE_EMAIL) ? true : false);
}

function validateForm($type, $value)
{
    if (emptyNullWhitespace($value)) {
        _log('NOPE!');
        return false;
    }

    switch ($type) {
        case 'email':
            _log('email');
            email($value);
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

validateForm('email', '');
