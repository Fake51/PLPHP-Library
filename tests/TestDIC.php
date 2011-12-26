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
        $dic->addReusableObject($obj);
        $this->assertTrue($dic->get('stdClass') instanceof stdClass);
        $this->assertTrue($dic->get('stdClass') === $obj);
    }
}
