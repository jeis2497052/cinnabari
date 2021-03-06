<?php

/**
 * Copyright (C) 2016, 2017 Datto, Inc.
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
 * @copyright 2016, 2017 Datto, Inc.
 */

namespace Datto\Cinnabari\AbstractArtifact;

use Datto\Cinnabari\Pixies\AliasMapper;

/**
 * Class Column
 *
 * The AbstractArtifact equivalent of a SQL-style column, corresponding for example
 * to the input {"id", clientId}, where "id" is the name and clientId the value.
 */
class Column
{
    /** @var string */
    private $name;

    /** @var string */
    private $value;

    /** @var string */
    private $tag;

    /**
     * Column constructor
     *
     * Construct a Column per the parameters, and assign it a tag (see class AliasMapper).
     *
     * @param string $name  The label to display with this column
     * @param string $value The table and column in SQL terms
     * @param AliasMapper $mapper
     */
    public function __construct($name, $value, AliasMapper $mapper)
    {
        $this->name = $name;
        $this->value = $value;
        $this->tag = $mapper->createColumnTag();
    }

    public function getName()
    {
        return $this->name;
    }

    public function setValue($value)
    {
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }

    /**
     * Return the tag for this Column (see class AliasMapper)
     *
     * @return string
     */
    public function getTag()
    {
        return $this->tag;
    }
}
