<?php

namespace ChronopostPickupPoint\Model\Base;

use \Exception;
use \PDO;
use ChronopostPickupPoint\Model\ChronopostPickupPointAreaFreeshipping as ChildChronopostPickupPointAreaFreeshipping;
use ChronopostPickupPoint\Model\ChronopostPickupPointAreaFreeshippingQuery as ChildChronopostPickupPointAreaFreeshippingQuery;
use ChronopostPickupPoint\Model\Map\ChronopostPickupPointAreaFreeshippingTableMap;
use ChronopostPickupPoint\Model\Thelia\Model\Area;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'chronopost_pickup_point_area_freeshipping' table.
 *
 *
 *
 * @method     ChildChronopostPickupPointAreaFreeshippingQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildChronopostPickupPointAreaFreeshippingQuery orderByAreaId($order = Criteria::ASC) Order by the area_id column
 * @method     ChildChronopostPickupPointAreaFreeshippingQuery orderByDeliveryModeId($order = Criteria::ASC) Order by the delivery_mode_id column
 * @method     ChildChronopostPickupPointAreaFreeshippingQuery orderByCartAmount($order = Criteria::ASC) Order by the cart_amount column
 *
 * @method     ChildChronopostPickupPointAreaFreeshippingQuery groupById() Group by the id column
 * @method     ChildChronopostPickupPointAreaFreeshippingQuery groupByAreaId() Group by the area_id column
 * @method     ChildChronopostPickupPointAreaFreeshippingQuery groupByDeliveryModeId() Group by the delivery_mode_id column
 * @method     ChildChronopostPickupPointAreaFreeshippingQuery groupByCartAmount() Group by the cart_amount column
 *
 * @method     ChildChronopostPickupPointAreaFreeshippingQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildChronopostPickupPointAreaFreeshippingQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildChronopostPickupPointAreaFreeshippingQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildChronopostPickupPointAreaFreeshippingQuery leftJoinArea($relationAlias = null) Adds a LEFT JOIN clause to the query using the Area relation
 * @method     ChildChronopostPickupPointAreaFreeshippingQuery rightJoinArea($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Area relation
 * @method     ChildChronopostPickupPointAreaFreeshippingQuery innerJoinArea($relationAlias = null) Adds a INNER JOIN clause to the query using the Area relation
 *
 * @method     ChildChronopostPickupPointAreaFreeshippingQuery leftJoinChronopostPickupPointDeliveryMode($relationAlias = null) Adds a LEFT JOIN clause to the query using the ChronopostPickupPointDeliveryMode relation
 * @method     ChildChronopostPickupPointAreaFreeshippingQuery rightJoinChronopostPickupPointDeliveryMode($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ChronopostPickupPointDeliveryMode relation
 * @method     ChildChronopostPickupPointAreaFreeshippingQuery innerJoinChronopostPickupPointDeliveryMode($relationAlias = null) Adds a INNER JOIN clause to the query using the ChronopostPickupPointDeliveryMode relation
 *
 * @method     ChildChronopostPickupPointAreaFreeshipping findOne(ConnectionInterface $con = null) Return the first ChildChronopostPickupPointAreaFreeshipping matching the query
 * @method     ChildChronopostPickupPointAreaFreeshipping findOneOrCreate(ConnectionInterface $con = null) Return the first ChildChronopostPickupPointAreaFreeshipping matching the query, or a new ChildChronopostPickupPointAreaFreeshipping object populated from the query conditions when no match is found
 *
 * @method     ChildChronopostPickupPointAreaFreeshipping findOneById(int $id) Return the first ChildChronopostPickupPointAreaFreeshipping filtered by the id column
 * @method     ChildChronopostPickupPointAreaFreeshipping findOneByAreaId(int $area_id) Return the first ChildChronopostPickupPointAreaFreeshipping filtered by the area_id column
 * @method     ChildChronopostPickupPointAreaFreeshipping findOneByDeliveryModeId(int $delivery_mode_id) Return the first ChildChronopostPickupPointAreaFreeshipping filtered by the delivery_mode_id column
 * @method     ChildChronopostPickupPointAreaFreeshipping findOneByCartAmount(string $cart_amount) Return the first ChildChronopostPickupPointAreaFreeshipping filtered by the cart_amount column
 *
 * @method     array findById(int $id) Return ChildChronopostPickupPointAreaFreeshipping objects filtered by the id column
 * @method     array findByAreaId(int $area_id) Return ChildChronopostPickupPointAreaFreeshipping objects filtered by the area_id column
 * @method     array findByDeliveryModeId(int $delivery_mode_id) Return ChildChronopostPickupPointAreaFreeshipping objects filtered by the delivery_mode_id column
 * @method     array findByCartAmount(string $cart_amount) Return ChildChronopostPickupPointAreaFreeshipping objects filtered by the cart_amount column
 *
 */
abstract class ChronopostPickupPointAreaFreeshippingQuery extends ModelCriteria
{

    /**
     * Initializes internal state of \ChronopostPickupPoint\Model\Base\ChronopostPickupPointAreaFreeshippingQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'thelia', $modelName = '\\ChronopostPickupPoint\\Model\\ChronopostPickupPointAreaFreeshipping', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildChronopostPickupPointAreaFreeshippingQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildChronopostPickupPointAreaFreeshippingQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof \ChronopostPickupPoint\Model\ChronopostPickupPointAreaFreeshippingQuery) {
            return $criteria;
        }
        $query = new \ChronopostPickupPoint\Model\ChronopostPickupPointAreaFreeshippingQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildChronopostPickupPointAreaFreeshipping|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = ChronopostPickupPointAreaFreeshippingTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(ChronopostPickupPointAreaFreeshippingTableMap::DATABASE_NAME);
        }
        $this->basePreSelect($con);
        if ($this->formatter || $this->modelAlias || $this->with || $this->select
         || $this->selectColumns || $this->asColumns || $this->selectModifiers
         || $this->map || $this->having || $this->joins) {
            return $this->findPkComplex($key, $con);
        } else {
            return $this->findPkSimple($key, $con);
        }
    }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return   ChildChronopostPickupPointAreaFreeshipping A model object, or null if the key is not found
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT ID, AREA_ID, DELIVERY_MODE_ID, CART_AMOUNT FROM chronopost_pickup_point_area_freeshipping WHERE ID = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            $obj = new ChildChronopostPickupPointAreaFreeshipping();
            $obj->hydrate($row);
            ChronopostPickupPointAreaFreeshippingTableMap::addInstanceToPool($obj, (string) $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return ChildChronopostPickupPointAreaFreeshipping|array|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($dataFetcher);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return ObjectCollection|array|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection($this->getDbName());
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($dataFetcher);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return ChildChronopostPickupPointAreaFreeshippingQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(ChronopostPickupPointAreaFreeshippingTableMap::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return ChildChronopostPickupPointAreaFreeshippingQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(ChronopostPickupPointAreaFreeshippingTableMap::ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildChronopostPickupPointAreaFreeshippingQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(ChronopostPickupPointAreaFreeshippingTableMap::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(ChronopostPickupPointAreaFreeshippingTableMap::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ChronopostPickupPointAreaFreeshippingTableMap::ID, $id, $comparison);
    }

    /**
     * Filter the query on the area_id column
     *
     * Example usage:
     * <code>
     * $query->filterByAreaId(1234); // WHERE area_id = 1234
     * $query->filterByAreaId(array(12, 34)); // WHERE area_id IN (12, 34)
     * $query->filterByAreaId(array('min' => 12)); // WHERE area_id > 12
     * </code>
     *
     * @see       filterByArea()
     *
     * @param     mixed $areaId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildChronopostPickupPointAreaFreeshippingQuery The current query, for fluid interface
     */
    public function filterByAreaId($areaId = null, $comparison = null)
    {
        if (is_array($areaId)) {
            $useMinMax = false;
            if (isset($areaId['min'])) {
                $this->addUsingAlias(ChronopostPickupPointAreaFreeshippingTableMap::AREA_ID, $areaId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($areaId['max'])) {
                $this->addUsingAlias(ChronopostPickupPointAreaFreeshippingTableMap::AREA_ID, $areaId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ChronopostPickupPointAreaFreeshippingTableMap::AREA_ID, $areaId, $comparison);
    }

    /**
     * Filter the query on the delivery_mode_id column
     *
     * Example usage:
     * <code>
     * $query->filterByDeliveryModeId(1234); // WHERE delivery_mode_id = 1234
     * $query->filterByDeliveryModeId(array(12, 34)); // WHERE delivery_mode_id IN (12, 34)
     * $query->filterByDeliveryModeId(array('min' => 12)); // WHERE delivery_mode_id > 12
     * </code>
     *
     * @see       filterByChronopostPickupPointDeliveryMode()
     *
     * @param     mixed $deliveryModeId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildChronopostPickupPointAreaFreeshippingQuery The current query, for fluid interface
     */
    public function filterByDeliveryModeId($deliveryModeId = null, $comparison = null)
    {
        if (is_array($deliveryModeId)) {
            $useMinMax = false;
            if (isset($deliveryModeId['min'])) {
                $this->addUsingAlias(ChronopostPickupPointAreaFreeshippingTableMap::DELIVERY_MODE_ID, $deliveryModeId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($deliveryModeId['max'])) {
                $this->addUsingAlias(ChronopostPickupPointAreaFreeshippingTableMap::DELIVERY_MODE_ID, $deliveryModeId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ChronopostPickupPointAreaFreeshippingTableMap::DELIVERY_MODE_ID, $deliveryModeId, $comparison);
    }

    /**
     * Filter the query on the cart_amount column
     *
     * Example usage:
     * <code>
     * $query->filterByCartAmount(1234); // WHERE cart_amount = 1234
     * $query->filterByCartAmount(array(12, 34)); // WHERE cart_amount IN (12, 34)
     * $query->filterByCartAmount(array('min' => 12)); // WHERE cart_amount > 12
     * </code>
     *
     * @param     mixed $cartAmount The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildChronopostPickupPointAreaFreeshippingQuery The current query, for fluid interface
     */
    public function filterByCartAmount($cartAmount = null, $comparison = null)
    {
        if (is_array($cartAmount)) {
            $useMinMax = false;
            if (isset($cartAmount['min'])) {
                $this->addUsingAlias(ChronopostPickupPointAreaFreeshippingTableMap::CART_AMOUNT, $cartAmount['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($cartAmount['max'])) {
                $this->addUsingAlias(ChronopostPickupPointAreaFreeshippingTableMap::CART_AMOUNT, $cartAmount['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ChronopostPickupPointAreaFreeshippingTableMap::CART_AMOUNT, $cartAmount, $comparison);
    }

    /**
     * Filter the query by a related \ChronopostPickupPoint\Model\Thelia\Model\Area object
     *
     * @param \ChronopostPickupPoint\Model\Thelia\Model\Area|ObjectCollection $area The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildChronopostPickupPointAreaFreeshippingQuery The current query, for fluid interface
     */
    public function filterByArea($area, $comparison = null)
    {
        if ($area instanceof \ChronopostPickupPoint\Model\Thelia\Model\Area) {
            return $this
                ->addUsingAlias(ChronopostPickupPointAreaFreeshippingTableMap::AREA_ID, $area->getId(), $comparison);
        } elseif ($area instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ChronopostPickupPointAreaFreeshippingTableMap::AREA_ID, $area->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByArea() only accepts arguments of type \ChronopostPickupPoint\Model\Thelia\Model\Area or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Area relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildChronopostPickupPointAreaFreeshippingQuery The current query, for fluid interface
     */
    public function joinArea($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Area');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Area');
        }

        return $this;
    }

    /**
     * Use the Area relation Area object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \ChronopostPickupPoint\Model\Thelia\Model\AreaQuery A secondary query class using the current class as primary query
     */
    public function useAreaQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinArea($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Area', '\ChronopostPickupPoint\Model\Thelia\Model\AreaQuery');
    }

    /**
     * Filter the query by a related \ChronopostPickupPoint\Model\ChronopostPickupPointDeliveryMode object
     *
     * @param \ChronopostPickupPoint\Model\ChronopostPickupPointDeliveryMode|ObjectCollection $chronopostPickupPointDeliveryMode The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildChronopostPickupPointAreaFreeshippingQuery The current query, for fluid interface
     */
    public function filterByChronopostPickupPointDeliveryMode($chronopostPickupPointDeliveryMode, $comparison = null)
    {
        if ($chronopostPickupPointDeliveryMode instanceof \ChronopostPickupPoint\Model\ChronopostPickupPointDeliveryMode) {
            return $this
                ->addUsingAlias(ChronopostPickupPointAreaFreeshippingTableMap::DELIVERY_MODE_ID, $chronopostPickupPointDeliveryMode->getId(), $comparison);
        } elseif ($chronopostPickupPointDeliveryMode instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ChronopostPickupPointAreaFreeshippingTableMap::DELIVERY_MODE_ID, $chronopostPickupPointDeliveryMode->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByChronopostPickupPointDeliveryMode() only accepts arguments of type \ChronopostPickupPoint\Model\ChronopostPickupPointDeliveryMode or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the ChronopostPickupPointDeliveryMode relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildChronopostPickupPointAreaFreeshippingQuery The current query, for fluid interface
     */
    public function joinChronopostPickupPointDeliveryMode($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('ChronopostPickupPointDeliveryMode');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'ChronopostPickupPointDeliveryMode');
        }

        return $this;
    }

    /**
     * Use the ChronopostPickupPointDeliveryMode relation ChronopostPickupPointDeliveryMode object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \ChronopostPickupPoint\Model\ChronopostPickupPointDeliveryModeQuery A secondary query class using the current class as primary query
     */
    public function useChronopostPickupPointDeliveryModeQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinChronopostPickupPointDeliveryMode($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'ChronopostPickupPointDeliveryMode', '\ChronopostPickupPoint\Model\ChronopostPickupPointDeliveryModeQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildChronopostPickupPointAreaFreeshipping $chronopostPickupPointAreaFreeshipping Object to remove from the list of results
     *
     * @return ChildChronopostPickupPointAreaFreeshippingQuery The current query, for fluid interface
     */
    public function prune($chronopostPickupPointAreaFreeshipping = null)
    {
        if ($chronopostPickupPointAreaFreeshipping) {
            $this->addUsingAlias(ChronopostPickupPointAreaFreeshippingTableMap::ID, $chronopostPickupPointAreaFreeshipping->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the chronopost_pickup_point_area_freeshipping table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ChronopostPickupPointAreaFreeshippingTableMap::DATABASE_NAME);
        }
        $affectedRows = 0; // initialize var to track total num of affected rows
        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            ChronopostPickupPointAreaFreeshippingTableMap::clearInstancePool();
            ChronopostPickupPointAreaFreeshippingTableMap::clearRelatedInstancePool();

            $con->commit();
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }

        return $affectedRows;
    }

    /**
     * Performs a DELETE on the database, given a ChildChronopostPickupPointAreaFreeshipping or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or ChildChronopostPickupPointAreaFreeshipping object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
     public function delete(ConnectionInterface $con = null)
     {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ChronopostPickupPointAreaFreeshippingTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(ChronopostPickupPointAreaFreeshippingTableMap::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();


        ChronopostPickupPointAreaFreeshippingTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            ChronopostPickupPointAreaFreeshippingTableMap::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }
    }

} // ChronopostPickupPointAreaFreeshippingQuery
