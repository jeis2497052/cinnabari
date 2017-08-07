<?php

/**
 * Copyright (C) 2017 Datto, Inc.
 *
 * This file is part of Cinnabari.
 *
 * Cinnabari is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License, version 3,
 * as published by the Free Software Foundation.
 *
 * Cinnabari is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with Cinnabari. If not, see <http://www.gnu.org/licenses/>.
 *
 * @author Mark Greeley mgreeley@datto.com>
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL-3.0
 * @copyright 2017 Datto, Inc.
 */

namespace Datto\Cinnabari\Result\AliasMapper;

use Datto\Cinnabari\Result\SIL\SIL;

/**
 * Class Aliases
 *
 * The final database code may need to contain aliases for tables, columns, and/or
 * parameters (e.g., the `0` in "FROM TABLE abc AS `0`"). The values of these
 * aliases can only be calculated after SQL generation is essentially complete.
 * Therefore, the database (e.g., MySQL) code generated by the Formatter contains
 * placeholders (called tags) for aliases.
 * After the Formatter has run, the final alias for each tag can be calculated by
 * AliasMapper::calculate(). AliasMapper::replaceTagsWithAliases() should then be
 * run to replace the tags in the database code with their corresponding aliases.
 *
 * @package Datto\Cinnabari\Result\AliasMapper
 */

class AliasMapper
{
    /** @var SIL */
    private $sil;

    /** @var callable */
    private $mungeFunction;

    /** @var array */
    private $aliases;

    /** @var int */
    private $parameterCounter;

    /** @var int */
    private $columnCounter;

    /** @var int */
    private $tableCounter;

    /**
     * AliasMapper constructor.
     *
     * $mungeFunction allows the caller to perform
     * target-language-specific editing on alias names. For example,
     * MySQL aliases might need to be surrounded by backticks (e.g., `:5`).
     *
     * @param SIL $sil
     * @param callable $mungeFunction
     */
    public function __construct($sil, $mungeFunction)
    {
        $this->sil = $sil;
        $this->mungeFunction = $mungeFunction;
        $this->aliases = array();
        $this->parameterCounter = 0;
        $this->tableCounter = 0;
        $this->columnCounter = 0;
    }

    /**
     * @param string $tag
     * @param string $alias
     */
    private function addAlias($tag, $alias)
    {
        $this->aliases[$tag] = $alias;
    }

    /**
     * @param string $tag
     *
     * @return string|null
     */
    public function getAlias($tag)
    {
        return (isset($this->aliases[$tag])) ? ($this->aliases[$tag]) : null;
    }

    /**
     * Assign an alias string for each table, column, and parameter used in query $dbCode.
     * $dbCode presumably includes tags like "{{t0}}".
     *
     * @param string $dbCode
     */
    public function calculate($dbCode)
    {
        $parameterCounter = 0;
        $tableCounter = 0;   // For FROMs, JOINs, ...
        $columnCounter = 0;
        $munge = $this->mungeFunction;

        preg_match_all('~{{[cpt]\d+}}~', $dbCode, $matches, PREG_OFFSET_CAPTURE);

        foreach ($matches[0] as $match) {
            if ($this->getAlias($match[0]) === null) {
                if ($match[0][2] === 'c') {
                    $alias = $munge($columnCounter++);
                    $this->addAlias($match[0], $alias);
                } elseif ($match[0][2] === 'p') {
                    $alias = $munge(':' . $parameterCounter++);
                    $this->addAlias($match[0], $alias);
                } elseif ($match[0][2] === 't') {
                    $alias = $munge($tableCounter++);
                    $this->addAlias($match[0], $alias);
                }
            }
        }
    }


    /**
     * Return a copy of the $input string, with the tags replaced by their
     * corresponding aliases.
     *
     * @param string $input
     *
     * @return string
     */
    public function replaceTagsWithAliases($input)
    {
        $output = $input;

        foreach ($this->aliases as $tag => $alias) {
            $output = str_replace($tag, $alias, $output);
        }

        return $output;
    }


    /**
     * Create a new parameter tag, and increment the parameter tag counter.
     *
     * @return string
     */
    public function createParameterTag()
    {
        $tag = '{{p' . $this->parameterCounter . '}}';
        $this->parameterCounter++;
        return $tag;
    }


    /**
     * Create a new table tag, and increment the table tag counter.
     *
     * @return string
     */
    public function createTableTag()
    {
        $tag = '{{t' . $this->tableCounter . '}}';
        $this->tableCounter++;
        return $tag;
    }


    /**
     * Create a new column tag, and increment the column tag counter.
     *
     * @return string
     */
    public function createColumnTag()
    {
        $tag = '{{c' . $this->columnCounter . '}}';
        $this->columnCounter++;
        return $tag;
    }
}
