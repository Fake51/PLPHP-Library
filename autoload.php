<?php
/**
 * contains the autoloader class and sets up autoloading
 *
 * PHP Version 5.3+
 *
 * @category PLPHP-Library
 * @package  PLPHP-Library
 * @author   Peter Lind <peter.e.lind@gmail.com>
 * @license  https://github.com/Fake51/PLPHP-Library/blob/master/COPYRIGHT FreeBSD License
 * @link     http://www.plphp.dk
 */

$autoloader = new AutoLoad();

/**
 * Autoloader exception class
 *
 * @category PLPHP-Library
 * @package  PLPHP-Library-Exceptions
 * @author   Peter Lind <peter.e.lind@gmail.com>
 * @license  https://github.com/Fake51/PLPHP-Library/blob/master/COPYRIGHT FreeBSD License
 * @link     http://www.plphp.dk
 */
class AutoLoadException extends Exception
{
}

/**
 * Autoloader class
 *
 * @category PLPHP-Library
 * @package  PLPHP-Library
 * @author   Peter Lind <peter.e.lind@gmail.com>
 * @license  https://github.com/Fake51/PLPHP-Library/blob/master/COPYRIGHT FreeBSD License
 * @link     http://www.plphp.dk
 */
class AutoLoad
{
    /**
     * array of class prefixes
     * that will be ignored by the autoloader
     *
     * @var array
     */
    protected $ignored_prefixes = array();

    /**
     * path prefix for loading files from
     *
     * @var string
     */
    protected $path_prefix = '';

    /**
     * public constructor
     *
     * @param array  $ignored_prefixes Ignored class names starting with prefixes from array
     * @param string $path_prefix      Prefix for path to load classes from
     * @param bool   $prepend          Whether to prepend autoloader on autoloader stack
     *
     * @access public
     * @return void
     */
    public function __construct(array $ignored_prefixes = array(), $path_prefix = '', $prepend = false)
    {
        if ($ignored_prefixes) {
            $this->ignored_prefixes = $ignored_prefixes;
        }

        if ($path_prefix) {
            $this->path_prefix = rtrim($path_prefix, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        }

        spl_autoload_register(array($this, 'load'), true, $prepend);
    }

    /**
     * handles autoloading classes
     *
     * @param string $class_name Name of class to load
     *
     * @throws AutoLoadException
     * @access public
     * @return bool
     */
    public function load($class_name)
    {
        foreach ($this->ignored_prefixes as $prefix) {
            if (strpos($class_name, $prefix) !== false) {
                return;
            }
        }

        $filename = mb_strtolower((string) $class_name);
        if (strpos($filename, '.') !== false || strpos($filename, '/') !== false) {
            throw new AutoLoadException('Classname contains invalid characters');
        }

        include $this->path_prefix . str_replace('_', DIRECTORY_SEPARATOR, $filename) . '.php';
    }
}
