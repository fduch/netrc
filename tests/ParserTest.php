<?php
/**
 * This file is part of the Netrc package.
 *
 * (c) Alex Medvedev <alex.medwedew@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Date: 3/21/14
 */

namespace Fduch\Netrc;

use PHPUnit_Framework_TestCase;

class ParserTest extends \PHPUnit_Framework_TestCase
{
    protected $parser;

    protected function setUp()
    {
        $this->parser = new Parser();
    }

    /**
     * @test
     */
    public function testCorrectNetrcParsedSuccessfully()
    {
        $netrc = <<<EOF
machine machine.one login john password pass1
machine machine.two login steve password pass2
EOF;
        $this->assertEquals(
            $this->parser->parse($netrc),
            array(
                "machine.one" => array('login' => 'john', 'password' => 'pass1'),
                "machine.two" => array('login' => 'steve', 'password' => 'pass2'),
            )
        );
    }

    /**
     * @expectedException Fduch\Netrc\Exception\ParseException
     * @test
     */
    public function testIncorrectDefaultThrowsException()
    {
        $netrc = <<<EOF
default machine.one login john password pass1
EOF;
        $this->parser->parse($netrc);
    }

    /**
     * @expectedException Fduch\Netrc\Exception\ParseException
     * @test
     */
    public function testIncorrectKeyThrowsException()
    {
        $netrc = <<<EOF
machine machine.one logOn john password pass1
EOF;
        $this->parser->parse($netrc);
    }

    /**
     * @expectedException Fduch\Netrc\Exception\ParseException
     * @test
     */
    public function testEmptyMachineNameThrowsException()
    {
        $netrc = <<<EOF
machine
EOF;
        $this->parser->parse($netrc);
    }

    /**
     * @expectedException Fduch\Netrc\Exception\ParseException
     * @test
     */
    public function testUnsetValueForValidKeyThrowsException()
    {
        $netrc = <<<EOF
machine machine.one login
EOF;
        $this->parser->parse($netrc);
    }

    /**
     * @expectedException Fduch\Netrc\Exception\ParseException
     * @test
     */
    public function testAttemtToSetInfoForUnknownMachineThrowsException()
    {
        $netrc = <<<EOF
login john
EOF;
        $this->parser->parse($netrc);
    }
}
 