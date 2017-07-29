<?php

namespace Appeltaert\PAM\Printer;


class Plain implements PrinterInterface
{
    /**
     * @var string
     */
    private $padding = '';

    /**
     * @var int
     */
    private $maxdepth = 0;

    /**
     * @var string
     */
    private $whitespace = '';

    /**
     * @param string $padding
     * @param int $maxdepth
     * @param string $whitespace
     */
    public function __construct($padding = ' ', $maxdepth = 1, $whitespace = ' ')
    {
        $this->padding = $padding;
        $this->maxdepth = $maxdepth;
        $this->whitespace = $whitespace;
    }

    /**
     * @param int $padding
     * @param $stringSoFar
     * @param $currentLevel
     * @param int $depth
     * @return string
     */
    private function walk($padding = 0, &$stringSoFar, $currentLevel, $depth = 0)
    {
        $longestKey = $this->longest(array_keys($currentLevel)) + 1;

        $keysIndex = array_flip(array_keys($currentLevel));

        foreach($currentLevel as $k => $val) {
            if (0 !== $keysIndex[$k]) {
                $stringSoFar .= $this->pad(' ', $padding, $depth);
            }
            $stringSoFar .= $this->pad("$k:", $longestKey);
            if (!is_array($val)) {
                $stringSoFar .= "\033[33m$val\033[0m\n";
            } else {
                $stringSoFar .= $this->walk($padding + $longestKey, $stringSoFar, $val, $depth + 1);
            }
        }
    }

    /**
     * @param array $collection
     * @return string
     */
    public function parse(array $collection)
    {
        $content = '';
        $this->walk(0, $content, $collection);
        return $content;

    }

    /**
     * @param array $vals
     * @return int
     */
    private function longest(array $vals)
    {
        usort($vals, function($a, $b) {
            return strlen($b) - strlen($a);
        });
        return strlen($vals[0]);
    }

    /**
     * @param string $input
     * @param int $length
     * @param int $multiplier
     * @return string
     */
    private function pad($input, $length, $multiplier = 1)
    {
        return str_pad($input, $length, $this->padding, STR_PAD_LEFT)
            . str_repeat($this->whitespace, $multiplier);
    }
}