<?php

namespace Fcm;

interface Request
{
    /**
     * @return string
     */
    public function getUrl(): string;

    /**
     * @return array
     */
    public function getBody(): array;
}
