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

use Fduch\Netrc\Exception\ParseException;

/**
 * Parses netrc file
 *
 * @author Alex Medvedev
 */
class Parser
{
    /**
     * List of netrc keys
     *
     * @var array
     */
    protected static $netrcKeys = array("machine", "default", "login", "password", "account", "macdef");

    /**
     * Parses netrc content
     *
     * @param string $content file content
     *
     * @throws ParseException when netrc file could not be parsed
     *
     * @return array of netrc values grouped by machines
     */
    public function parse($content)
    {
        $tokens = array_filter(preg_split("/[\s]+/", $content));

        $i = 0;
        $result = array();
        $currentMachine = '';

        while ($i < count($tokens)) {
            $currentToken = $tokens[$i];
            if ($currentToken == 'machine') {
                if (!isset($tokens[$i+1])) {
                    throw new ParseException("Cannot fetch machine name");
                }
                $currentMachine = $tokens[$i+1];
                $result[$currentMachine] = array();
                $i+=2;
            } else if ($currentToken == 'default') {
                $currentMachine = 'default';
                $result[$currentMachine] = array();
                $i++;
            } else {
                if (!in_array($currentToken, static::$netrcKeys)) {
                    throw new ParseException("Unknown key detected");
                }
                if (!isset($tokens[$i+1])) {
                    throw new ParseException("Cannot fetch value of $tokens[$i] key for $currentMachine machine");
                }
                $result[$currentMachine][$currentToken] = $tokens[$i+1];
                $i+=2;
            }
        }

        return $result;
    }
}
 