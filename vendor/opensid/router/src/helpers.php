<?php

/**
 * Get hook router
 * 
 * @param array
 */
function getHooks($config = [])
{
    return OpenSID\Hook::getHooks($config);
}

/**
 * Get all routes
 * 
 * @return array
 */
function getRoutes()
{
    return OpenSID\Route::getRoutes();
}

