<?php

namespace Mizner\VFT\Shortcode;

use const Mizner\VFT\PATH;

class Form
{

    public function __construct()
    {
        add_shortcode('vft-form', [$this, 'shortcode_output']);
    }

    public function shortcode_output()
    {
        ob_start();
        include_once PATH . 'templates/form.php';
        return ob_get_clean();
    }

}