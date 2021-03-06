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

namespace Datto\Cinnabari\AbstractArtifact\Tables;

use Datto\Cinnabari\Exception;
use Datto\Cinnabari\Pixies\AliasMapper;
use Datto\Cinnabari\AbstractArtifact\Column;
use Datto\Cinnabari\AbstractArtifact\Statements\Clauses\Limit;
use Datto\Cinnabari\AbstractArtifact\Statements\Clauses\GroupBy;
use Datto\Cinnabari\AbstractArtifact\Statements\Clauses\OrderBy;

/**
 * Class SelectTable
 *
 * The AbstractArtifact equivalent of a (My)SQL SELECT subquery.
 */
class SelectTable extends AbstractTable
{
    /** @var AbstractTable */
    private $table;

    /** @var null|string */
    private $where;

    /** @var GroupBy[] */
    private $groupBys;

    /** @var null|string */
    private $having;

    /** @var OrderBy[] */
    private $orderBys;

    /** @var null|Limit */
    private $limit;

    /** @var Column[] */
    private $columns;

    /** @var JoinTable[] */
    private $joins;

    /**
     * SelectTable constructor.
     *
     * @param AliasMapper $mapper
     */
    public function __construct(AliasMapper $mapper)
    {
        $this->where = null;
        $this->groupBys = array();
        $this->having = null;
        $this->orderBys = array();
        $this->limit = null;
        $this->columns = array();
        $this->joins = array();
        parent::__construct($mapper);
    }

    public function setWhere($where)
    {
        if ($this->where) {
            throw Exception::internalError('Select: multiple wheres');
        }
        $this->where = $where;
    }

    public function getWhere()
    {
        return $this->where;
    }

    public function addGroupBy(GroupBy $groupBy)
    {
        $this->groupBys[] = $groupBy;
    }

    public function getGroupBys()
    {
        return $this->groupBys;
    }

    public function setHaving($having)
    {
        if ($this->having) {
            throw Exception::internalError('Select: multiple havings');
        }
        $this->having = $having;
    }

    public function getHaving()
    {
        return $this->having;
    }

    public function addOrderBy(OrderBy $orderBy)
    {
        $this->orderBys[] = $orderBy;
    }

    public function getOrderBys()
    {
        return $this->orderBys;
    }

    public function setLimit($limit)
    {
        if ($this->limit) {
            throw Exception::internalError('Select: multiple limits');
        }
        $this->limit = $limit;
    }

    public function getLimit()
    {
        return $this->limit;
    }

    public function setTable($table)
    {
        $this->table = $table;
    }

    public function getTable()
    {
        return $this->table;
    }

    /**
     * @param JoinTable $join
     */
    public function addJoin($join)
    {
        $this->joins[] = $join;
    }

    /**
     * @return AbstractTable[]
     */
    public function getJoins()
    {
        return $this->joins;
    }

    /**
     * @param Column $column
     */
    public function addColumn($column)
    {
        $this->columns[] = $column;
    }

    /**
     * @return array|Column[]
     */
    public function getColumns()
    {
        return $this->columns;
    }
}
