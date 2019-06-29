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

use Fduch\Netrc\Exception\FileNotFoundException;
use Fduch\Netrc\Exception\ParseException;

/**
 * Netrc manager
 *
 * @author Alex Medvedev
 */
class Netrc
{
    /**
     * Get the default path of the netrc file that will be used if one
     * is not provided.
     *
     * @throws FileNotFoundException when netrc default path could not be resolved
     *
     * @return string
     */
    public static function getDefaultPath() : string
    {
        $homePath = getenv('HOME');
        if (!homePath) {
            throw new FileNotFoundException(
                "HOME environment variable must be set for correct netrc handling"
            );
        }
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $filename = strtr($homePath, '\\', '/') . '/_netrc';
        } else {
            $filename = rtrim($homePath, '/') . '/.netrc';
        }
        return $filename;
    }

    /**
     * Parses netrc file specified or default one
     *
     * @param string|null $filename
     *
     * @throws FileNotFoundException when netrc file could not be found or is not readable
     * @throws ParseException when netrc file could not be parsed
     *
     * @return array of netrc values grouped by machines
     */
    public static function parse($filename = null) : array
    {
        // fetch netrc filename if it is not specified
        if (!$filename) {
            $filename = self::getDefaultPath();
        }
        $realFilename = realpath($filename);

        if ( !$realFilename || !file_exists( $realFilename ) ) {
            throw new FileNotFoundException(
                "The netrc path ($filename) does not resolve to an actual file."
            );
        }

        // check that netrc file is available
        if (!is_readable($realFilename) || !$content = file_get_contents($realFilename)) {
            throw new FileNotFoundException(
                "netrc file ($realFilename) is not readable"
            );
        }

        // parse file
        $parser = new Parser();
        return $parser->parse($content);
    }
}
