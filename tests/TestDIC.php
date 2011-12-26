<?php
/**
 * test for the DIC class
 *
 * PHP Version 5.3+
 *
 * @category PLPHP-Library
 * @package  PLPHP-Library-Tests
 * @author   Peter Lind <peter.e.lind@gmail.com>
 * @license  ./COPYRIGHT FreeBSD License
 * @link     http://www.plphp.dk
 */

require __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'autoload.php';
$autoload = new Autoload();

/**
 * tests the DIC class
 *
 * @category PLPHP-Library
 * @package  PLPHP-Library-Tests
 * @author   Peter Lind <peter.e.lind@gmail.com>
 * @license  ./COPYRIGHT FreeBSD License
 * @link     http://www.plphp.dk
 */
class TestDIC extends PHPUnit_Framework_TestCase
{
    /**
     * tests the DIC factory method
     *
     * @access public
     * @return void
     */
    public function testGet()
    {
        $dic = new DIC();
        $this->assertTrue($dic->get('DIC') instanceof DIC);
        $this->assertTrue($dic->get('DIC') === $dic);
    }

    /**
     * tests the method to manually add an object
     * to the DIC for further reuse
     *
     * @access public
     * @return void
     */
    public function testAddReusableObject()
    {
        $dic = new DIC();
        $obj = new stdClass();
        $this->assertTrue($dic->addReusableObject($obj) instanceof DIC);
        $this->assertTrue($dic->get('stdClass') instanceof stdClass);
        $this->assertTrue($dic->get('stdClass') === $obj);
    }

    /**
     * tests the method for adding classes to the DIC
     *
     * @access public
     * @return void
     */
    public function testAddClass()
    {
        $dic = new DIC();
        $this->assertTrue($dic->addClass('stdClass', new stdClass) instanceof DIC);
        $this->assertTrue($dic->get('stdClass') instanceof stdClass);

        $dic           = new DIC();
        $obj           = new stdClass();
        $obj->reusable = true;
        $this->assertTrue($dic->addClass($obj, $obj) instanceof DIC);
        $this->assertTrue($dic->get('stdClass') instanceof stdClass);
    }
}
