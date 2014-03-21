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
 * Netrc manager
 *
 * @author Alex Medvedev
 */
class Netrc
{
    /**
     * Parses netrc file specified or default one
     *
     * @param string|null $filename
     *
     * @throws ParseException when netrc file could not be read or parsed
     *
     * @return array of netrc values grouped by machines
     */
    public static function parse($filename = null)
    {
        // fetch netrc filename if it is not specified
        if (!$filename) {
            if (!$homePath = getenv('HOME')) {
                throw new ParseException("HOME environment variable must be set for correctly netrc handling");
            }
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                $filename = strtr($homePath, '\\', '/') . '/_netrc';
            } else {
                $filename = rtrim($homePath, '/') . '/.netrc';
            }
        }
        $filename = realpath($filename);

        // check that netrc file is available
        if (!file_exists($filename) || !is_readable($filename) || !$content = file_get_contents($filename)) {
            throw new ParseException("netrc file does not exist or is not readable");
        }

        // parse file
        $parser = new Parser();
        return $parser->parse($content);
    }
}
 