<?php

namespace Model;

/**
 * Class MathCalc.
 */
class MathCalc
{
    /**
     * function that converts mathematical expression  to reverse polish notation.
     *
     * @param $s
     *
     * @return array|string
     */
    public static function convertToPolishNotation($s)
    {
        static $prior = array('^' => 3, '*' => 3, '/' => 3, '+' => 2, '-' => 2, '(' => 1);
        $stack = $out = $items = array();
        $pair = array();
        // get operators & operands
        $d = null;
        for ($i = 0, $l = strlen($s); $i < $l; ++$i) { // scan expression
            if (is_numeric($s{$i}) || $s{$i} === '.') {
                $d .= $s{$i};
            } else {
                if ($d != null) {
                    $out[] = $d; // add operand(numeric)
                    $pair[] = $d;
                    $d = null;
                }
                // add operator
                if (sizeof($stack) == 0 || $s{$i} === '(') {
                    $stack[] = $s{$i};
                } elseif ($s{$i} === ')') {
                    for ($j = sizeof($stack) - 1; $j >= 0; --$j) {
                        if ($stack[$j] !== '(') {
                            $out[] = array_pop($stack);
                        } else {
                            array_pop($stack);
                            break;
                        }
                    }
                } else { // + - * /
                    for ($j = sizeof($stack) - 1; $j >= 0; --$j) {
                        if ($prior[$stack[$j]] < $prior[$s{$i}]) {
                            break;
                        }
                        $out[] = $stack[$j];
                        unset($stack[$j]);
                    }
                    $stack = array_values($stack);
                    $stack[] = $s{$i};
                }
            }// else
        }
        if ($d !== null) {
            $out[] = $d;
        }
        if (count($stack)) {
            $out = array_merge($out, array_reverse($stack));
        }

        $out = implode(' ', $out);

        return $out;
    }

    /**
     * function that returns the calculation of mathematical expression with reverse polish notation.
     *
     * @param $str
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public static function calc($str)
    {
        $stack = array();

        $token = strtok($str, ' ');

        while ($token !== false) {
            if (in_array($token, array('*', '/', '+', '-'), false)) {
                if (count($stack) < 2) {
                    throw new \Exception("Not enough data for operation in '$token'");
                }
                $b = array_pop($stack);
                $a = array_pop($stack);
                switch ($token) {
                    case '*': $res = $a * $b; break;
                    case '/': $res = $a / $b; break;
                    case '+': $res = $a + $b; break;
                    case '-': $res = $a - $b; break;
                }
                array_push($stack, $res);
            } elseif (is_numeric($token)) {
                array_push($stack, $token);
            } else {
                throw new \Exception("Wrong symbol in $token!");
            }

            $token = strtok(' ');
        }
        if (count($stack) > 1) {
            throw new \Exception('Wrong expression!');
        }

        return array_pop($stack);
    }
}
