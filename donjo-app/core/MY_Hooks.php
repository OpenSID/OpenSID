<?php

use App\Bootstrap\RequestAwareTrait;

defined('BASEPATH') || exit('No direct script access allowed');

class MY_Hooks extends CI_Hooks
{
    use RequestAwareTrait;
}
