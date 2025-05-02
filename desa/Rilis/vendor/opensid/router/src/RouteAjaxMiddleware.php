<?php

namespace OpenSID;

/**
 * This middleware is used in routes that must be restricted to AJAX requests
 *
 * @author Anderson Salas <anderson@ingenia.me>
 */
class RouteAjaxMiddleware implements MiddlewareInterface
{
    /**
     * {@inheritDoc}
     * 
     * @see \OpenSID\MiddlewareInterface::run() 
     */
    public function run($args = [])
    {
        if(!ci()->input->is_ajax_request())
        {
            trigger_404();
        }
    }
}