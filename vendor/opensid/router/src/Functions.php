<?php

/**
 * Gets a route URL by its name
 * 
 * @param string  $name    Route name
 * @param array   $params  Route parameters
 * 
 * @return string
 */
function route($name = null, $params = [])
{
    if($name === null)
    {
        $route = Route::getCurrentRoute();
    }
    else
    {
        $route = Route::getByName($name);
    }

    return $route->buildUrl($params);
}

/**
 * Checks if a route exists
 * 
 * @param string $name Route name
 * 
 * @return bool
 */
function route_exists($name)
{
    return isset(Route::$compiled['names'][$name]);
}

/**
 * Returns the framework singleton
 * 
 * (Alias of &get_instance() CodeIgniter function)
 * 
 * @return object
 */
function &ci()
{
    return get_instance();
}

/**
 * Shows a screen with information about OpenSID CI
 *
 * @return void
 */
function my_info()
{
    ob_start();

    require OpenSID_CI_DIR . '/Resources/About.php';
    $OpenSIDInfo = ob_get_clean();

    ci()->output->set_output($OpenSIDInfo);
}


/**
 * Triggers the custom OpenSID CI 404 error page, with fallback to
 * native show_404() function
 *
 * @return void
 */
function trigger_404()
{
    $_404 = Route::get404();

    if(is_null($_404) || !is_callable($_404))
    {
        show_404();
    }

    call_user_func($_404);
    exit;
}

/**
 * Redirects to a route URL by its name
 * 
 * @param string $name     Route name
 * @param array  $params   Route parameters
 * @param array  $messages Array with flashdata messages
 * 
 * @return void
 */
function route_redirect($name, $params = [], $messages = [])
{
    if(!empty($messages) && is_array($messages))
    {
        ci()->load->library('session');

        foreach($messages as $_name => $_value)
        {
            ci()->session->set_flashdata($_name, $_value);
        }
    }

    redirect(route($name, $params));
}