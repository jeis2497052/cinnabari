<?php

namespace Datto\Cinnabari\AbstractArtifact\Statements;

use Datto\Cinnabari\AbstractArtifact\AbstractArtifact;
use Datto\Cinnabari\AbstractArtifact\Tables\Table;
use Datto\Cinnabari\AbstractArtifact\Statements\Clauses\Limit;
use Datto\Cinnabari\AbstractArtifact\Statements\Clauses\OrderBy;
use Datto\Cinnabari\Exception;
use Datto\Cinnabari\Pixies\AliasMapper;

/**
 * Return an AbstractArtifact, AliasManager, and Delete for use in the tests below
 */
function init()
{
    $abstractArtifact = new AbstractArtifact();
    $aliasMapper = new AliasMapper($abstractArtifact, function ($in) {
        return "`{$in}`";
    });
    $delete = new DeleteStatement();
    return array($abstractArtifact, $aliasMapper, $delete);
}


//---------------------------------------------------------------
// Test
list($abstractArtifact, $aliasMapper, $delete) = init();
$delete->setWhere('abc');
$output = $delete->getWhere();

// Output
$output = 'abc';


//---------------------------------------------------------------
// Test
list($abstractArtifact, $aliasMapper, $delete) = init();
$delete->setWhere('hi');
$delete->setWhere('again');

// Output
throw Exception::internalError('Delete: multiple wheres');


//---------------------------------------------------------------
// Test
list($abstractArtifact, $aliasMapper, $delete) = init();
$delete->addOrderBy(new OrderBy('xyz'));
$orderBys = $delete->getOrderBys();
$output = $orderBys[0]->getExpression();

// Output
$output = 'xyz';


//---------------------------------------------------------------
// Test
list($abstractArtifact, $aliasMapper, $delete) = init();
$delete->setLimit(new Limit(33,44));
$output = $delete->getLimit()->getRowCount() * 100 + $delete->getLimit()->getOffset();

// Output
$output = 4433;


//---------------------------------------------------------------
// Test
list($abstractArtifact, $aliasMapper, $delete) = init();
$delete->setLimit(new Limit(33,44));
$delete->setLimit(new Limit(55,66));

// Output
throw Exception::internalError('Delete: multiple limits');


//---------------------------------------------------------------
// Test
list($abstractArtifact, $aliasMapper, $delete) = init();
$delete->addTable(new Table('aTable', $aliasMapper));
$tables = $delete->getTables();
$output = $tables[0]->getName();

// Output
$output = 'aTable';
