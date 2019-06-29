<?php
declare(strict_types=1);
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

use Fduch\Netrc\Exception\ParseException;
use PHPUnit\Framework\TestCase;

class ParserTest extends TestCase
{
    protected $parser;

    protected function setUp() : void
    {
        $this->parser = new Parser();
    }

    /**
     * @test
     */
    public function correctNetrcParsedSuccessfully() : void
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
     * @test
     */
    public function emptyLinesAndWhiteSpacesParsedSuccessfully() : void
    {
        $netrc = <<<EOF


machine          machine.one login john password         pass1
machine


 machine.two
login steve
    password pass2


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
     * @test
     */
    public function incorrectDefaultThrowsException() : void
    {
        $this->expectException(ParseException::class);

        $netrc = <<<EOF
default machine.one login john password pass1
EOF;
        $this->parser->parse($netrc);
    }

    /**
     * @test
     */
    public function incorrectKeyThrowsException() : void
    {
        $this->expectException(ParseException::class);

        $netrc = <<<EOF
machine machine.one logOn john password pass1
EOF;
        $this->parser->parse($netrc);
    }

    /**
     * @test
     */
    public function emptyMachineNameThrowsException() : void
    {
        $this->expectException(ParseException::class);

        $netrc = <<<EOF
machine
EOF;
        $this->parser->parse($netrc);
    }

    /**
     * @test
     */
    public function unsetValueForValidKeyThrowsException() : void
    {
        $this->expectException(ParseException::class);

        $netrc = <<<EOF
machine machine.one login
EOF;
        $this->parser->parse($netrc);
    }

    /**
     * @test
     */
    public function attemptToSetInfoForUnknownMachineThrowsException() : void
    {
        $this->expectException(ParseException::class);

        $netrc = <<<EOF
login john
EOF;
        $this->parser->parse($netrc);
    }

    /**
     * @test
     */
    public function commentsInNetrcAreIgnored() : void
    {
        $netrc = <<<EOF
machine machine.one login john password pass1

# machine machine.two
#login machine.two
machine machine.two login steve password pass2 #should be omited

#machine machine.two login steve password pass2
machine machine.three login mike
     password pass3#should be omited
# machine machine.two
#login machine.two
# steve machine.two


EOF;
        $this->assertEquals(
            $this->parser->parse($netrc),
            array(
                "machine.one"   => array('login' => 'john', 'password' => 'pass1'),
                "machine.two"   => array('login' => 'steve', 'password' => 'pass2'),
                "machine.three" => array('login' => 'mike', 'password' => 'pass3'),
            )
        );
    }
}
 