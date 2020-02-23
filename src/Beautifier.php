<?php

namespace Repack\CssPhp;

//Shopping24\CSSBeautifier;

class Beautifier
{
    const TAP = "    ";

    private static $repair = false;

    /**
     * The run function will beautify your string, which include a CSS structure.
     *
     * @param $string
     * @param bool $repair - switch the mode that will add semicolons if there has to be one.
     *
     * @return string
     */
    public static function beautify($string, $repair = true)
    {
        self::$repair = $repair;

        $taps = 0;

        $beautifiedArray = array();

        foreach (self::stringToArray($string) as $key => $line) {
            $line = trim($line);

            switch (true) {
                case preg_match('/{/', $line):
                    $line = self::checkHealthyWhiteSpaces(self::createTaps($line, $taps), preg_match('/@/', $line));
                    $taps++;
                    break;

                case preg_match('/}/', $line):
                    $taps--;
                    $line = self::createTaps($line, $taps);
                    break;

                default:
                    $line = self::checkHealthyWhiteSpaces(self::createTaps($line, $taps));
            }

            $beautifiedArray[$key] = self::$repair ? self::checkHealthyAttribute($line) : $line;
        }

        return self::arrayToString($beautifiedArray);
    }

    /**
     * Add "new Lines" after every "{;}".
     *
     * @param String $string
     *
     * @return String
     */
    private static function newLines($string)
    {
        return preg_replace("/}/", "\n$0", preg_replace("/[{;}]/", "$0\n", $string));
    }

    /**
     * Convert a string to an array.
     * Each line will be an item in the array.
     *
     * @param String $string
     *
     * @return array
     */
    private static function stringToArray($string)
    {
        return explode(PHP_EOL, self::newLines($string));
    }

    /**
     * Convert an array to a string.
     * Each item in the array is a new line in the string.
     *
     * @param array $array
     *
     * @return string
     */
    private static function arrayToString($array)
    {
        $string = "";
        foreach ($array as $key => $line) {
            if (strlen(preg_replace("/ /", "", $line)) != 0) {
                if ($key != 0) {
                    $string .= "\n";
                }
                $string .= $line;
            }
        }
        return $string;
    }

    /**
     * Check the ';' in the end of a line and will add one if needed.
     *
     * @param string $string
     *
     * @return string
     */
    private static function checkHealthyAttribute($string)
    {
        if (preg_match("/[{;}]/", $string) == false && preg_match("/:/", $string) == true) {
            $string .= ";";
        }
        return $string;
    }

    /**
     * Check the line for a healthy structure.
     *
     * @param string $string
     * @param bool $checkDoublePoints
     *
     * @return string
     */
    private static function checkHealthyWhiteSpaces($string, $checkDoublePoints = true)
    {
        if (preg_match('/:/', $string) && !preg_match('/: /', $string) && $checkDoublePoints) {
            $string = preg_replace('/:/', ': ', $string, 1);
            if (preg_match('/(url\()/', $string)) {
                preg_match('~(url\()(.*?)\)~', $string, $result);
                if (isset($result[0])) {
                    $string = str_replace($result[0], preg_replace('/ /', '', $result[0]), $string);
                }
            }
        }
        if (!preg_match('/ {/', $string)) {
            $string = preg_replace('/{/', ' {', $string);
        }
        return $string;
    }

    /**
     * Create the needed taps.
     *
     * @param string $string
     * @param int $taps
     *
     * @return string
     */
    private static function createTaps($string, $taps)
    {
        if (!preg_match('/, /', $string)) {
            $string = str_replace(',', ', ', $string);
        }
        for ($i = 0; $i < $taps; $i++) {
            $string = self::TAP . $string;
        }
        return $string;
    }
}
