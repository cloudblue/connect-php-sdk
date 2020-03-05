<?php
/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2020. Ingram Micro. All Rights Reserved.
 */

namespace Connect\RQL;

class Query
{
    /**
     * List of properties
     * @var array
     */
    protected $in;

    /**
     * List of properties
     * @var array
     */
    protected $out;

    /**
     * Limit of elements to return
     * @var int
     */
    protected $limit;

    /**
     * orderby single property to orderby
     * @var string
     */
    protected $orderby;

    /**
     * Offset to return
     * Useful together with limit
     * @var int
     */
    protected $offset;

    /**
     * List of properties to set ordering
     * @var array
     */
    protected $ordering;

    /**
     * List of properties and patterns
     * @var array
     */
    protected $like;

    /**
     * List of properties and patterns
     * @var array
     */
    protected $ilike;

    /**
     * List of attributes to return
     * @var array
     */
    protected $select;

    /**
     * A relational operator is used to filter objects by comparing one of their
     * properties against a specified value.
     * @var array
     */
    protected $relationalOperators;

    public function __construct($input = null)
    {
        if (is_array($input)) {
            foreach ($input as $key => $value) {
                if (property_exists($this, $key)) {
                    $this->{$key} = $value;
                } else {
                    if (is_array($value)) {
                        $this->in($key, $value);
                    } else {
                        $this->equal($key, $value);
                    }
                }
            }
        }
    }

    /**
     * Select objects where the specified property value is in the provided array
     * @param $property
     * @param array $inArray
     * @return $this
     */
    public function in($property, array $inArray)
    {
        $this->in[$property] = $inArray;
        return $this;
    }

    /**
     * Select objects where the specified property value is not in the provided array
     * @param $property
     * @param array $inArray
     * @return $this
     */
    public function out($property, array $inArray)
    {
        $this->out[$property] = $inArray;
        return $this;
    }

    /**
     * Return the given number of objects from the start position
     * @param $amount
     * @return $this
     */
    public function limit($amount)
    {
        $this->limit = $amount;
        return $this;
    }

    /**
     * Order list by given property
     * Allows + and - operand for Ascending or Descending
     * @param $amount
     * @return $this
     */
    public function orderby($property)
    {
        $this->orderby = $property;
        return $this;
    }

    /**
     * Offset (page) to return on paged queries
     * @param $offset
     * @return $this
     */
    public function offset($page)
    {
        $this->offset = $page;
        return $this;
    }

    /**
     * Orderlist of objects by the given properties (unlimited number of properties).
     * The list is ordered first by the first specified property, then by the second, and
     * so on. The order is specified by the prefix: + ascending order, - descending.
     * @param $propertyList
     * @return $this
     */
    public function ordering(array $propertyList)
    {
        $this->ordering = $propertyList;
        return $this;
    }

    /**
     * Search for the specified pattern in the specified property. The function is similar
     * to the SQL LIKE operator, though it uses the * wildcard instead of %. To specify in
     * a pattern the * symbol itself, it must be percent-encoded, that is, you need to specify
     * %2A instead of *, see the usage examples below. In addition, it is possible to use the
     * ? wildcard in the pattern to specify that any symbol will be valid in this position.
     * @param $property
     * @param $pattern
     * @return $this
     */
    public function like($property, $pattern)
    {
        $this->like[$property] = $pattern;
        return $this;
    }

    /**
     * Same as like but case unsensitive
    * @param $property
    * @param $pattern
    * @return $this
    */
    public function ilike($property, $pattern)
    {
        $this->ilike[$property] = $pattern;
        return $this;
    }

    /**
     * The function is applicable to a list of resources (hereafter base resources). It receives
     * the list of attributes (up to 100 attributes) that can be primitive properties of the base
     * resources, relation names, and relation names combined with properties of related resources.
     * The output is the list of objects presenting the selected properties and related (linked)
     * resources. Normally, when relations are selected, the base resource properties are also presented
     * in the output.
     * @param array $attributeList
     * @return $this
     */
    public function select(array $attributeList)
    {
        $this->select = $attributeList;
        return $this;
    }

    /**
     * Select objects with a $property value equal to $value
     * @param string $property
     * @param mixed $value
     * @return $this
     */
    public function equal($property, $value)
    {
        $this->relationalOperators['eq'][] = array($property,$value);
        return $this;
    }

    /**
     * Select objects with a $property value is not the $value
     * @param string $property
     * @param mixed $value
     * @return $this
     */
    public function isNot($property, $value)
    {
        $this->relationalOperators['ne'][] = array($property,$value);
        return $this;
    }

    /**
     * Select objects with a $property value greater than the $value
     * @param string $property
     * @param mixed $value
     * @return $this
     */
    public function greater($property, $value)
    {
        $this->relationalOperators['gt'][] = array($property,$value);
        return $this;
    }

    /**
     * Select objects with a $property value equal or greater than the $value
     * @param string $property
     * @param mixed $value
     * @return $this
     */
    public function greaterOrEqual($property, $value)
    {
        $this->relationalOperators['ge'][] = array($property,$value);
        return $this;
    }

    /**
     * Select objects with a $property value less than the $value
     * @param string $property
     * @param mixed $value
     * @return $this
     */
    public function lesser($property, $value)
    {
        $this->relationalOperators['lt'][] = array($property,$value);
        return $this;
    }

    /**
     * Select objects with a $property value equal or less than the $value
     * @param string $property
     * @param mixed $value
     * @return $this
     */
    public function lesserOrEqual($property, $value)
    {
        $this->relationalOperators['le'][] = array($property,$value);
        return $this;
    }

    /**
     * Compile the query
     * @return string
     */
    public function compile()
    {
        $rql = array();

        if (isset($this->select)) {
            $rql[] = sprintf('select(%s)', implode(',', $this->select));
        }

        if (isset($this->like)) {
            foreach ($this->like as $property => $pattern) {
                $rql[] = sprintf('like(%s,%s)', $property, $pattern);
            }
        }

        if (isset($this->ilike)) {
            foreach ($this->ilike as $property => $pattern) {
                $rql[] = sprintf('ilike(%s,%s)', $property, $pattern);
            }
        }

        if (isset($this->in)) {
            foreach ($this->in as $property => $inArray) {
                $rql[] = sprintf('in(%s,(%s))', $property, implode(',', $inArray));
            }
        }

        if (isset($this->out)) {
            foreach ($this->out as $property => $inArray) {
                $rql[] = sprintf('out(%s,(%s))', $property, implode(',', $inArray));
            }
        }

        if (isset($this->relationalOperators)) {
            foreach ($this->relationalOperators as $operator => $arguments) {
                foreach ($arguments as $argument) {
                    $rql[] = sprintf('%s(%s)', $operator, implode(',', $argument));
                }
            }
        }

        if (isset($this->ordering)) {
            $rql[] = sprintf('ordering(%s)', implode(',', $this->ordering));
        }

        if (isset($this->limit)) {
            $rql[] = sprintf('limit=%s', $this->limit);
        }

        if (isset($this->orderby)) {
            $rql[] = sprintf('order_by=%s', $this->orderby);
        }

        if (isset($this->offset)) {
            $rql[] = sprintf('offset=%s', $this->offset);
        }

        if (count($rql) > 0) {
            return "?".implode('&', $rql);
        }
        return "";
    }

    /**
     * Return the string representation of the query
     * @return string
     */
    public function __toString()
    {
        return $this->compile();
    }
}
