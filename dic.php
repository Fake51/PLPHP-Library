<?php
/**
 * contains dependency injection container
 * and it's exception class
 *
 * PHP Version 5.3+
 *
 * @category PLPHP-Library
 * @package  PLPHP-Library
 * @author   Peter Lind <peter.e.lind@gmail.com>
 * @license  ./COPYRIGHT FreeBSD License
 * @link     http://www.plphp.dk
 */

/**
 * dependency injection container exception
 *
 * @category PLPHP-Library
 * @package  PLPHP-Library
 * @author   Peter Lind <peter.e.lind@gmail.com>
 * @license  ./COPYRIGHT FreeBSD License
 * @link     http://www.plphp.dk
 */
class DICException extends exception
{
}

/**
 * dependency injection container
 *
 * @category PLPHP-Library
 * @package  PLPHP-Library
 * @author   Peter Lind <peter.e.lind@gmail.com>
 * @license  ./COPYRIGHT FreeBSD License
 * @link     http://www.plphp.dk
 */
class DIC
{
    /**
     * dependencies for various classes
     *
     * @var array
     */
    protected static $dependencies = array(
        'DIC' => array(
            'reusable' => true,
        ),
    );

    /**
     * pool for reusing objects when
     * possible
     *
     * @var array
     */
    protected static $object_pool = array();

    /**
     * public constructor
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        if (empty(self::$object_pool[get_class($this)])) {
            self::$object_pool[get_class($this)] = $this;
        }
    }

    /**
     * adds an object to pool of reusables
     * - allows for more complex constructions
     *   and setups of objects if needed
     *
     * @param object $object Object for reuse
     *
     * @throws DICException
     * @access public
     * @return $this
     */
    public function addReusableObject($object)
    {
        if (!is_object($object)) {
            throw new DICException('Provided parameter is not an object');
        }

        $class = get_class($object);

        if (!isset(self::$dependencies[$class])) {
            self::$dependencies[$class] = array('reusable' => true);
        }

        self::$object_pool[$class] = $object;

        return $this;
    }

    /**
     * factory method - returns an object
     * of the desired kind
     *
     * @param string $class_name Name of class to return object of
     *
     * @throws DICException
     * @access public
     * @return mixed
     */
    public function get($class_name)
    {
        if (!isset(self::$dependencies[$class_name])) {
            throw new DICException('No information available for class ' . $class_name);
        }

        if (!empty(self::$dependencies[$class_name]['reusable']) && !empty(self::$object_pool[$class_name])) {
            return self::$object_pool[$class_name];
        }

        $object = $this->createObject($class_name);

        if (!empty(self::$dependencies[$class_name]['reusable'])) {
            self::$object_pool[$class_name] = $object;
        }

        return $object;
    }

    /**
     * creates the individual objects filling
     * them with dependencies as needed
     *
     * @param string $class_name Name of class to create
     *
     * @throws DICException
     * @access protected
     * @return mixed
     */
    protected function createObject($class_name)
    {
        $reflection  = new ReflectionClass($class_name);

        if (empty(self::$dependencies[$class_name]['parameters'])) {
            $constructor = $reflection->getConstructor();

            $params    = array();
            $constants = get_defined_constants();
            foreach ($constructor->getParameters() as $parameter) {
                if ($class = $parameter->getClass()) {
                    if (!isset(self::$dependencies[$class->getName()])) {
                        throw new DICException('Cannot create dependency class ' . $class->getName());
                    }

                    $params[] = $this->get($class->getName());
                } else {
                    if (!isset($constants[$parameter->getName()])) {
                        throw new DICException('Cannot find dependency: ' . $parameter->getName());
                    }

                    $params[] = $constants[$parameter->getName()];
                }
            }

            self::$dependencies[$class_name]['parameters'] = $params;
        }

        return $reflection->newInstanceArgs(self::$dependencies[$class_name]['parameters']);
    }
}
