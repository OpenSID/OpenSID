<?php

use App\Bootstrap\RequestAwareTrait;

defined('BASEPATH') || exit('No direct script access allowed');

class MY_Input extends CI_Input
{
    use RequestAwareTrait;
}
