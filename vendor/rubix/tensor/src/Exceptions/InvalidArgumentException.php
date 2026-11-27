<?php

namespace Tensor\Exceptions;

use InvalidArgumentException as SplInvalidArgumentException;

class InvalidArgumentException extends SplInvalidArgumentException implements TensorException
{
    //
}
