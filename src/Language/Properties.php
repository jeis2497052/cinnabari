<?php

/**
 * Copyright (C) 2016 Datto, Inc.
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
 * @author Spencer Mortensen <smortensen@datto.com>
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL-3.0
 * @copyright 2016 Datto, Inc.
 */

namespace Datto\Cinnabari\Language;

use Datto\Cinnabari\Exception\TranslatorException;

class Properties
{
    /** @var array */
    private $properties;

    private static $databaseClass = 'Database';

    public function __construct($properties)
    {
        $this->properties = $properties;
    }

    public function getType($class, $property)
    {
        if ($class === null) {
            $class = self::$databaseClass;
        }

        $type = &$this->properties[$class][$property];

        if ($type === null) {
            throw TranslatorException::unknownProperty($class, $property);
        }

        return $type;
    }
}
