<?php
/**
 * contains the autoloader class and sets up autoloading
 *
 * PHP Version 5.3+
 *
 * @category FVHub
 * @package  FVHubCore
 * @author   Peter Lind <peter.e.lind@gmail.com>
 * @license  ./COPYRIGHT FreeBSD License
 * @link     http://www.fastaval.dk
 */

$autoloader = new AutoLoader();
spl_autoload_register(array($autoloader, 'load'));

/**
 * Autoloader class
 *
 * @category FVHub
 * @package  FVHubCore
 * @author   Peter Lind <peter.e.lind@gmail.com>
 * @license  ./COPYRIGHT FreeBSD License
 * @link     http://www.fastaval.dk
 */
class AutoLoader
{
    /**
     * array of class prefixes
     * that will be ignored by the autoloader
     *
     * @var array
     */
    protected static $ignored_prefixes = array('Slim');

    /**
     * handles autoloading classes
     *
     * @param string $class_name Name of class to load
     *
     * @throws FVHubException
     * @access public
     * @return bool
     */
    public function load($class_name)
    {
        foreach (self::$ignored_prefixes as $prefix) {
            if (strpos($class_name, $prefix) !== false) {
                return;
            }
        }

        $filename = mb_strtolower((string) $class_name);
        if (strpos($filename, '.') !== false || strpos($filename, '/') !== false) {
            throw new FVHubException('Classname contains invalid characters');
        }

        include str_replace('_', DIRECTORY_SEPARATOR, $filename) . '.php';
    }
}
