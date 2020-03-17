<?php
/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2018-2020. Ingram Micro. All Rights Reserved.
 */

namespace Test\Unit;

use \Connect\RQL\Query;

class QueryTest extends \Test\TestCase
{
    public function testEmptyConstructor()
    {
        $rql = new Query();
        $this->assertEquals("", $rql->compile());
    }

    public function testArrayBackwardsCompatibilityString()
    {
        $arrayFilters = array(
            "product.id" => "PRD-123123123",
            "ordering" => ["test1","test2"],
            "limit" => 10,
            "offset" => 4,
            "orderby" => "property"
        );
        $rql = new Query($arrayFilters);
        $this->assertEquals('?eq(product.id,PRD-123123123)&ordering(test1,test2)&limit=10&order_by=property&offset=4', $rql->compile());
    }

    public function testArrayBackwardsCompatibilityArray()
    {
        $arrayFilters = array(
            "product.id" => array("PRD-123123123","PRD-123123123")
        );
        $rql = new Query($arrayFilters);
        $this->assertEquals('?in(product.id,(PRD-123123123,PRD-123123123))', $rql->compile());
    }

    public function testEqual()
    {
        $rql = new Query();
        $rql->equal('key', 'value');
        $this->assertEquals('?eq(key,value)', $rql->compile());
    }

    public function testIn()
    {
        $rql = new Query();
        $rql->in('key', array('value1','value2'));
        $this->assertEquals('?in(key,(value1,value2))', $rql->compile());
    }

    public function testSelect()
    {
        $rql = new Query();
        $rql->select(array('attribute'));
        $this->assertEquals('?select(attribute)', $rql->compile());
    }

    public function testLike()
    {
        $rql = new Query();
        $rql->like('product.id', 'PR-');
        $this->assertEquals('?like(product.id,PR-)', $rql->compile());
    }

    public function testiLike()
    {
        $rql = new Query();
        $rql->ilike('product.id', 'PR-');
        $this->assertEquals('?ilike(product.id,PR-)', $rql->compile());
    }

    public function testOut()
    {
        $rql = new Query();
        $rql->out('product.id', array('PR-','CN-'));
        $this->assertEquals('?out(product.id,(PR-,CN-))', $rql->compile());
    }

    public function testOrderBy()
    {
        $rql = new Query();
        $rql->orderby('date');
        $this->assertEquals('?order_by=date', $rql->compile());
    }

    public function testIsNot()
    {
        $rql = new Query();
        $rql->isNot('property', 'value');
        $this->assertEquals('?ne(property,value)', $rql->compile());
    }

    public function testGreater()
    {
        $rql = new Query();
        $rql->greater('property', 'value');
        $this->assertEquals('?gt(property,value)', $rql->compile());
    }

    public function testGreaterOrEqual()
    {
        $rql = new Query();
        $rql->greaterOrEqual('property', 'value');
        $this->assertEquals('?ge(property,value)', $rql->compile());
    }

    public function testLess()
    {
        $rql = new Query();
        $rql->lesser('property', 'value');
        $this->assertEquals('?lt(property,value)', $rql->compile());
    }

    public function testLessOrEqual()
    {
        $rql = new Query();
        $rql->lesserOrEqual('property', 'value');
        $this->assertEquals('?le(property,value)', $rql->compile());
    }

    public function testLimit()
    {
        $rql = new Query();
        $rql->limit(10);
        $this->assertEquals('?limit=10', $rql->__toString());
    }

    public function testOffset()
    {
        $rql = new Query();
        $rql->offset(10);
        $this->assertEquals('?offset=10', $rql->compile());
    }

    public function testordering()
    {
        $rql = new Query();
        $rql->ordering(["property1", "property2"]);
        $this->assertEquals("?ordering(property1,property2)", $rql->compile());
    }
}
