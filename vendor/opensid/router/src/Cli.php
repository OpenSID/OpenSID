<?php

namespace OpenSID;

use OpenSID\RouteBuilder as Route;

/**
 * CLI handler for OpenSID CI
 * 
 * (Due security reasons, mostly commands defined here are disbled in 'production'
 * and 'testing' environments)
 * 
 * @author Anderson Salas <anderson@ingenia.me>
 */
class Cli
{
    /**
     * Registers all 'OpenSID make' commands
     * 
     * @return void
     */
    public static function maker()
    {
        if(ENVIRONMENT !== 'development')
        {
            return;
        }

        Route::group('OpenSID', function(){
            Route::group('make', function(){
                Route::cli('controller/{(.+):name}',function($name){
                    self::makeContoller($name);
                });

                Route::cli('model/{(.+):name}',function($name){
                    self::makeModel($name);
                });

                Route::cli('helper/{(.+):name}',function($name){
                    self::makeHelper($name);
                });

                Route::cli('library/{(.+):name}',function($name){
                    self::makeLibrary($name);
                });

                Route::cli('middleware/{(.+):name}',function($name){
                    self::makeMiddleware($name);
                });

                Route::cli('migration/{name}/{((sequential|timestamp)):type?}',function($name, $type = 'timestamp'){
                    self::makeMigration($name, $type);
                });
            });
        });
    }

    /**
     * Registers the 'OpenSID migrate' command
     * 
     * @return void
     */
    public static function migrations()
    {
        if(ENVIRONMENT !== 'development')
        {
            return;
        }

        Route::group('OpenSID', function(){
            Route::group('migrate', function(){
                Route::cli('{version?}',function($version = null){
                    self::migrate($version);
                });
            });
        });
    }
    
    /**
     * Parses a CLI agument 
     * 
     * @param string $name    Parameter value
     * @param mixed  $default Default parameter value
     * @internal
     * 
     * @return boolean|array|string
     */
    private static function arg($name, $default = null)
    {
        foreach($_SERVER['argv'] as $arg){
            $find = '--' . $name;
            if(substr($arg, 0, strlen($find)) == $find){
                if($arg == $find){
                    return true;
                }
                if(count(explode(':',$arg)) == 2){
                    list(,$value) = explode(':',$arg);
                    return $value;
                }
            }
        }
        return $default;
    }

    /**
     * Creates a controller
     *
     * To create a resource controller with common CRUD operations structure,
     * use the --resource parameter. Example:
     * 
     *   php index.php OpenSID make controller Airplanes --resource
     *
     * (For HMVC users) To specify the module name, use the --module:[name] 
     * parameter. Example:
     * 
     *   php index.php OpenSID make controller Invoice --module:MyModule
     *   
     *   ... you can also create a new resource module controller:
     *   
     *   php index.php OpenSID make controller Invoice --module:MyModule --resource
     *
     * @param  string $name Controller name
     *
     * @return void
     */
    private static function makeContoller($name, $resource = false)
    {
        $resource = self::arg('resource') === true;
        $module = self::arg('module');
        
        if(!is_string($module) || empty($module)){
            $module = null;
        }
        
        $subFolder = null;
        $name = ucfirst($name) . 'Controller';
        
        if(count(explode('/', $name)) > 0)
        {
            $subFolder = explode('/', $name);
            $name = array_pop($subFolder);
            $subFolder = implode('/', $subFolder);
        }
        
        $path = APPPATH . (!empty($module) ? 'modules/' . $module . '/' : '' ) . 'controllers/' . (!empty($subFolder) ? $subFolder . '/' : '' );
        
        if(!file_exists($path)){
            mkdir($path, 0777, true);
        }
        
        if(file_exists($path . '/' . $name . '.php'))
        {
            show_error('The file already exists!');
        }

        $file = <<<CONTROLLER
<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class $name extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    %CONTROLLER_BODY%
}
CONTROLLER;


        if(!$resource)
        {
            $controllerBody ='
    /**
     * Index action
     */
    public function index()
    {

    }
';
        }
        else
        {
            $controllerBody = '
    /**
     * Index action
     */
    public function index()
    {

    }

    /**
     * Create action
     */
    public function create()
    {

    }

    /**
     * Store action
     */
    public function store()
    {

    }

    /**
     * Show action
     *
     * @param  string  $id
     */
    public function show($id)
    {

    }

    /**
     * Edit action
     *
     * @param  string  $id
     */
    public function edit($id)
    {

    }

    /**
     * Update action
     *
     * @param  string  $id
     */
    public function update($id)
    {

    }

    /**
     * Destroy action
     *
     * @param  string $id
     */
    public function destroy($id)
    {

    }
';
        }

        $file = str_ireplace('%CONTROLLER_BODY%', $controllerBody, $file);

        file_put_contents($path . '/' . $name . '.php', $file);

        echo "\nCREATED:\n" . realpath($path . '/' . $name . '.php') . "\n";
    }
    
    /**
     * Creates a model
     *
     * (For HMVC users) To specify the module name, use the --module:[name]
     * parameter. Example:
     *
     *   php index.php OpenSID make model ModelName --module:MyModule
     *
     * @param  string $name Model name
     *
     * @return void
     */
    private static function makeModel($name)
    {        
        $module = self::arg('module');
        
        if(!is_string($module) || empty($module)){
            $module = null;
        }
        
        $subFolder = null;
        $name = ucfirst($name) . '_model';
        
        if(count(explode('/', $name)) > 0)
        {
            $subFolder = explode('/', $name);
            $name = array_pop($subFolder);
            $subFolder = implode('/', $subFolder);
        }
        
        $path = APPPATH . (!empty($module) ? 'modules/' . $module . '/' : '' ) . 'models/' . (!empty($subFolder) ? $subFolder . '/' : '' );
        
        if(!file_exists($path)){
            mkdir($path, 0777, true);
        }
        
        if(file_exists($path . '/' . $name . '.php'))
        {
            show_error('The file already exists!');
        }
        
        $file = <<<MODEL
<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class $name extends CI_Model
{

}
MODEL;

        file_put_contents($path . '/' . $name . '.php', $file);

        echo "\nCREATED:\n" . realpath($path . '/' . $name . '.php') . "\n";
    }

    
    /**
     * Creates a helper
     *
     * (For HMVC users) To specify the module name, use the --module:[name]
     * parameter. Example:
     *
     *   php index.php OpenSID make helper MyHelper --module:MyModule
     *
     * @param  string $name Helper name
     *
     * @return void
     */
    private static function makeHelper($name)
    {        
        $module = self::arg('module');
        
        if(!is_string($module) || empty($module)){
            $module = null;
        }
        
        $subFolder = null;
        $name = ucfirst($name) . '_helper';
        
        if(count(explode('/', $name)) > 0)
        {
            $subFolder = explode('/', $name);
            $name = array_pop($subFolder);
            $subFolder = implode('/', $subFolder);
        }
        
        $path = APPPATH . (!empty($module) ? 'modules/' . $module . '/' : '' ) . 'helpers/' . (!empty($subFolder) ? $subFolder . '/' : '' );
        
        if(!file_exists($path)){
            mkdir($path, 0777, true);
        }
        
        if(file_exists($path . '/' . $name . '.php'))
        {
            show_error('The file already exists!');
        }
        
        $file = <<<HELPER
<?php

defined('BASEPATH') OR exit('No direct script access allowed');


HELPER;
        
        file_put_contents($path . '/' . $name . '.php', $file);
        
        echo "\nCREATED:\n" . realpath($path . '/' . $name . '.php') . "\n";
    }


    /**
     * Creates a middleware
     * 
     * @param string $name Middleware name
     * 
     * @return void
     */
    private static function makeMiddleware($name)
    {
        $dir = [];

        if(count(explode('/', $name)) > 0)
        {
            $dir  = explode('/', $name);
            $name = array_pop($dir);
        }

        $name = ucfirst($name) . 'Middleware';
        $path = APPPATH . 'middleware/' . ( empty($dir) ? $name : implode('/', $dir) . '/' . $name ) . '.php';

        if(!empty($dir))
        {
            Utils::rmkdir($dir,'middleware');
        }

        if(file_exists($path))
        {
            show_error('The file already exists!');
        }

        $file = <<<MIDDLEWARE
<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class $name implements OpenSID\MiddlewareInterface
{

    /**
     * Middleware entry point
     *
     * @return void
     */
    public function run(\$args = [])
    {

    }
}
MIDDLEWARE;

        file_put_contents($path, $file);

        echo "\nCREATED:\n" . realpath($path) . "\n";
    }

    /**
     * Creates a library
     *
     * @param  string $name Library name
     *
     * @return void
     */
    private static function makeLibrary($name)
    {
        $module = self::arg('module');
        
        if(!is_string($module) || empty($module)){
            $module = null;
        }
        
        $subFolder = null;
        $name = ucfirst($name);
        
        if(count(explode('/', $name)) > 0)
        {
            $subFolder = explode('/', $name);
            $name = array_pop($subFolder);
            $subFolder = implode('/', $subFolder);
        }
        
        $path = APPPATH . (!empty($module) ? 'modules/' . $module . '/' : '' ) . 'libraries/' . (!empty($subFolder) ? $subFolder . '/' : '' );
        
        if(!file_exists($path)){
            mkdir($path, 0777, true);
        }
        
        if(file_exists($path . '/' . $name . '.php'))
        {
            show_error('The file already exists!');
        }
        
        $file = <<<LIBRARY
<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class $name
{

}
LIBRARY;
        
        file_put_contents($path . '/' . $name . '.php', $file);
        
        echo "\nCREATED:\n" . realpath($path . '/' . $name . '.php') . "\n";
    }

    /**
     * Creates a migration
     *
     * @param  string  $name Name
     * @param  string  $type Type (sequential|date)
     *
     * @return void
     */
    private static function makeMigration($name, $type)
    {
        if(!file_exists(APPPATH . '/migrations'))
        {
            mkdir(APPPATH . '/migrations');
        }

        $name = trim(str_ireplace(' ', '_', $name));

        if($type == 'timestamp')
        {
            $filename = date('Y') . date('m') . date('d') . date('H') . date('i') . date('s') . '_' . $name;
        }
        else
        {
            $migrations = scandir(APPPATH . '/migrations');
            $last = 0;

            foreach($migrations as $migration)
            {
                if($migration == '.' || $migration == '..')
                {
                    continue;
                }

                $_number = substr($migration,0,4);

                if(preg_match('/^[0-9]{3}_$/',$_number))
                {
                    if($_number > $last)
                    {
                        $last = (int) $_number;
                    }
                }
            }
            $last++;
            $filename = str_pad($last, 3, '0', STR_PAD_LEFT) . '_' . $name;
        }

        $path = APPPATH . 'migrations/' . $filename . '.php';

        if(file_exists($path))
        {
            show_error('The file already exists!');
        }

        $file = <<<MIGRATION
<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_{$name} extends CI_Migration
{
    public function up()
    {

    }

    public function down()
    {

    }
}
MIGRATION;

        file_put_contents($path, $file);

        echo "\nCREATED:\n" . realpath($path) . "\n";
    }

    /**
     * Runs a migration
     *
     * @param  string  $version (Optional)
     *
     * @return void
     */
    private static function migrate($version = null)
    {
        if($version == 'reverse')
        {
            self::migrate('0');
            return;
        }

        if($version == 'refresh')
        {
            self::migrate('0');
            self::migrate();
            return;
        }

        ci()->load->library('migration');

        $migrations = ci()->migration->find_migrations();

        $_migrationsTable = new \ReflectionProperty('CI_Migration', '_migration_table');
        $_migrationsTable->setAccessible(true);
        $_migrationsTable = $_migrationsTable->getValue(ci()->migration);

        $old = ci()->db->get($_migrationsTable)->result()[0]->version;

        $migrate = function() use($version)
        {
            if($version === null)
            {
                return ci()->migration->latest();
            }

            return ci()->migration->version($version);
        };

        $result = $migrate();

        if($result === FALSE)
        {
            show_error(ci()->migration->error_string());
        }

        $current = ci()->db->get($_migrationsTable)->result()[0]->version;

        echo "\n";

        if($old == $current)
        {
            echo "Nothing to migrate. \n";
        }
        else
        {
            $migrated   = [];
            $index      = 0;
            $migrations = $old < $current ? $migrations : array_reverse($migrations, true);
            $ascendent  = $old < $current;

            foreach($migrations as $name => $path)
            {
                if($ascendent)
                {
                    if( $current >=  $name)
                    {
                        echo 'MIGRATED: ' . basename($migrations[$name]) . "\n";
                    }
                }
                else
                {
                    if( $current <= $name)
                    {
                        echo 'REVERSED: ' . basename($migrations[$name]) . "\n";
                    }
                }
            }
        }
    }
}