<?php

namespace OpenSID;

/**
 * All OpenSID CI middleware used in the application SHOULD implement this interface, 
 * in order to be properly detected by the router
 *
 * @author Anderson Salas <anderson@ingenia.me>
 */
interface MiddlewareInterface
{
    /**
     * Middleware entry point
     * 
     * @param mixed $args Middleware arguments
     * 
     * @return mixed
     */
    public function run($args);
}