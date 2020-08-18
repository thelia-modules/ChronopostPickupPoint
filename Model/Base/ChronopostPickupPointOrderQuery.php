<?php

namespace ChronopostPickupPoint\Model\Base;

use \Exception;
use \PDO;
use ChronopostPickupPoint\Model\ChronopostPickupPointOrder as ChildChronopostPickupPointOrder;
use ChronopostPickupPoint\Model\ChronopostPickupPointOrderQuery as ChildChronopostPickupPointOrderQuery;
use ChronopostPickupPoint\Model\Map\ChronopostPickupPointOrderTableMap;
use ChronopostPickupPoint\Model\Thelia\Model\Order;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'chronopost_pickup_point_order' table.
 *
 *
 *
 * @method     ChildChronopostPickupPointOrderQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildChronopostPickupPointOrderQuery orderByOrderId($order = Criteria::ASC) Order by the order_id column
 * @method     ChildChronopostPickupPointOrderQuery orderByDeliveryType($order = Criteria::ASC) Order by the delivery_type column
 * @method     ChildChronopostPickupPointOrderQuery orderByDeliveryCode($order = Criteria::ASC) Order by the delivery_code column
 * @method     ChildChronopostPickupPointOrderQuery orderByLabelDirectory($order = Criteria::ASC) Order by the label_directory column
 * @method     ChildChronopostPickupPointOrderQuery orderByLabelNumber($order = Criteria::ASC) Order by the label_number column
 *
 * @method     ChildChronopostPickupPointOrderQuery groupById() Group by the id column
 * @method     ChildChronopostPickupPointOrderQuery groupByOrderId() Group by the order_id column
 * @method     ChildChronopostPickupPointOrderQuery groupByDeliveryType() Group by the delivery_type column
 * @method     ChildChronopostPickupPointOrderQuery groupByDeliveryCode() Group by the delivery_code column
 * @method     ChildChronopostPickupPointOrderQuery groupByLabelDirectory() Group by the label_directory column
 * @method     ChildChronopostPickupPointOrderQuery groupByLabelNumber() Group by the label_number column
 *
 * @method     ChildChronopostPickupPointOrderQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildChronopostPickupPointOrderQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildChronopostPickupPointOrderQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildChronopostPickupPointOrderQuery leftJoinOrder($relationAlias = null) Adds a LEFT JOIN clause to the query using the Order relation
 * @method     ChildChronopostPickupPointOrderQuery rightJoinOrder($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Order relation
 * @method     ChildChronopostPickupPointOrderQuery innerJoinOrder($relationAlias = null) Adds a INNER JOIN clause to the query using the Order relation
 *
 * @method     ChildChronopostPickupPointOrder findOne(ConnectionInterface $con = null) Return the first ChildChronopostPickupPointOrder matching the query
 * @method     ChildChronopostPickupPointOrder findOneOrCreate(ConnectionInterface $con = null) Return the first ChildChronopostPickupPointOrder matching the query, or a new ChildChronopostPickupPointOrder object populated from the query conditions when no match is found
 *
 * @method     ChildChronopostPickupPointOrder findOneById(int $id) Return the first ChildChronopostPickupPointOrder filtered by the id column
 * @method     ChildChronopostPickupPointOrder findOneByOrderId(int $order_id) Return the first ChildChronopostPickupPointOrder filtered by the order_id column
 * @method     ChildChronopostPickupPointOrder findOneByDeliveryType(string $delivery_type) Return the first ChildChronopostPickupPointOrder filtered by the delivery_type column
 * @method     ChildChronopostPickupPointOrder findOneByDeliveryCode(string $delivery_code) Return the first ChildChronopostPickupPointOrder filtered by the delivery_code column
 * @method     ChildChronopostPickupPointOrder findOneByLabelDirectory(string $label_directory) Return the first ChildChronopostPickupPointOrder filtered by the label_directory column
 * @method     ChildChronopostPickupPointOrder findOneByLabelNumber(string $label_number) Return the first ChildChronopostPickupPointOrder filtered by the label_number column
 *
 * @method     array findById(int $id) Return ChildChronopostPickupPointOrder objects filtered by the id column
 * @method     array findByOrderId(int $order_id) Return ChildChronopostPickupPointOrder objects filtered by the order_id column
 * @method     array findByDeliveryType(string $delivery_type) Return ChildChronopostPickupPointOrder objects filtered by the delivery_type column
 * @method     array findByDeliveryCode(string $delivery_code) Return ChildChronopostPickupPointOrder objects filtered by the delivery_code column
 * @method     array findByLabelDirectory(string $label_directory) Return ChildChronopostPickupPointOrder objects filtered by the label_directory column
 * @method     array findByLabelNumber(string $label_number) Return ChildChronopostPickupPointOrder objects filtered by the label_number column
 *
 */
abstract class ChronopostPickupPointOrderQuery extends ModelCriteria
{

    /**
     * Initializes internal state of \ChronopostPickupPoint\Model\Base\ChronopostPickupPointOrderQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'thelia', $modelName = '\\ChronopostPickupPoint\\Model\\ChronopostPickupPointOrder', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildChronopostPickupPointOrderQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildChronopostPickupPointOrderQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof \ChronopostPickupPoint\Model\ChronopostPickupPointOrderQuery) {
            return $criteria;
        }
        $query = new \ChronopostPickupPoint\Model\ChronopostPickupPointOrderQuery();
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
     * @return ChildChronopostPickupPointOrder|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = ChronopostPickupPointOrderTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(ChronopostPickupPointOrderTableMap::DATABASE_NAME);
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
     * @return   ChildChronopostPickupPointOrder A model object, or null if the key is not found
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT ID, ORDER_ID, DELIVERY_TYPE, DELIVERY_CODE, LABEL_DIRECTORY, LABEL_NUMBER FROM chronopost_pickup_point_order WHERE ID = :p0';
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
            $obj = new ChildChronopostPickupPointOrder();
            $obj->hydrate($row);
            ChronopostPickupPointOrderTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildChronopostPickupPointOrder|array|mixed the result, formatted by the current formatter
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
     * @return ChildChronopostPickupPointOrderQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(ChronopostPickupPointOrderTableMap::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return ChildChronopostPickupPointOrderQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(ChronopostPickupPointOrderTableMap::ID, $keys, Criteria::IN);
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
     * @return ChildChronopostPickupPointOrderQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(ChronopostPickupPointOrderTableMap::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(ChronopostPickupPointOrderTableMap::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ChronopostPickupPointOrderTableMap::ID, $id, $comparison);
    }

    /**
     * Filter the query on the order_id column
     *
     * Example usage:
     * <code>
     * $query->filterByOrderId(1234); // WHERE order_id = 1234
     * $query->filterByOrderId(array(12, 34)); // WHERE order_id IN (12, 34)
     * $query->filterByOrderId(array('min' => 12)); // WHERE order_id > 12
     * </code>
     *
     * @see       filterByOrder()
     *
     * @param     mixed $orderId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildChronopostPickupPointOrderQuery The current query, for fluid interface
     */
    public function filterByOrderId($orderId = null, $comparison = null)
    {
        if (is_array($orderId)) {
            $useMinMax = false;
            if (isset($orderId['min'])) {
                $this->addUsingAlias(ChronopostPickupPointOrderTableMap::ORDER_ID, $orderId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($orderId['max'])) {
                $this->addUsingAlias(ChronopostPickupPointOrderTableMap::ORDER_ID, $orderId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ChronopostPickupPointOrderTableMap::ORDER_ID, $orderId, $comparison);
    }

    /**
     * Filter the query on the delivery_type column
     *
     * Example usage:
     * <code>
     * $query->filterByDeliveryType('fooValue');   // WHERE delivery_type = 'fooValue'
     * $query->filterByDeliveryType('%fooValue%'); // WHERE delivery_type LIKE '%fooValue%'
     * </code>
     *
     * @param     string $deliveryType The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildChronopostPickupPointOrderQuery The current query, for fluid interface
     */
    public function filterByDeliveryType($deliveryType = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($deliveryType)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $deliveryType)) {
                $deliveryType = str_replace('*', '%', $deliveryType);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ChronopostPickupPointOrderTableMap::DELIVERY_TYPE, $deliveryType, $comparison);
    }

    /**
     * Filter the query on the delivery_code column
     *
     * Example usage:
     * <code>
     * $query->filterByDeliveryCode('fooValue');   // WHERE delivery_code = 'fooValue'
     * $query->filterByDeliveryCode('%fooValue%'); // WHERE delivery_code LIKE '%fooValue%'
     * </code>
     *
     * @param     string $deliveryCode The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildChronopostPickupPointOrderQuery The current query, for fluid interface
     */
    public function filterByDeliveryCode($deliveryCode = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($deliveryCode)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $deliveryCode)) {
                $deliveryCode = str_replace('*', '%', $deliveryCode);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ChronopostPickupPointOrderTableMap::DELIVERY_CODE, $deliveryCode, $comparison);
    }

    /**
     * Filter the query on the label_directory column
     *
     * Example usage:
     * <code>
     * $query->filterByLabelDirectory('fooValue');   // WHERE label_directory = 'fooValue'
     * $query->filterByLabelDirectory('%fooValue%'); // WHERE label_directory LIKE '%fooValue%'
     * </code>
     *
     * @param     string $labelDirectory The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildChronopostPickupPointOrderQuery The current query, for fluid interface
     */
    public function filterByLabelDirectory($labelDirectory = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($labelDirectory)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $labelDirectory)) {
                $labelDirectory = str_replace('*', '%', $labelDirectory);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ChronopostPickupPointOrderTableMap::LABEL_DIRECTORY, $labelDirectory, $comparison);
    }

    /**
     * Filter the query on the label_number column
     *
     * Example usage:
     * <code>
     * $query->filterByLabelNumber('fooValue');   // WHERE label_number = 'fooValue'
     * $query->filterByLabelNumber('%fooValue%'); // WHERE label_number LIKE '%fooValue%'
     * </code>
     *
     * @param     string $labelNumber The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildChronopostPickupPointOrderQuery The current query, for fluid interface
     */
    public function filterByLabelNumber($labelNumber = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($labelNumber)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $labelNumber)) {
                $labelNumber = str_replace('*', '%', $labelNumber);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ChronopostPickupPointOrderTableMap::LABEL_NUMBER, $labelNumber, $comparison);
    }

    /**
     * Filter the query by a related \ChronopostPickupPoint\Model\Thelia\Model\Order object
     *
     * @param \ChronopostPickupPoint\Model\Thelia\Model\Order|ObjectCollection $order The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildChronopostPickupPointOrderQuery The current query, for fluid interface
     */
    public function filterByOrder($order, $comparison = null)
    {
        if ($order instanceof \ChronopostPickupPoint\Model\Thelia\Model\Order) {
            return $this
                ->addUsingAlias(ChronopostPickupPointOrderTableMap::ORDER_ID, $order->getId(), $comparison);
        } elseif ($order instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ChronopostPickupPointOrderTableMap::ORDER_ID, $order->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByOrder() only accepts arguments of type \ChronopostPickupPoint\Model\Thelia\Model\Order or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Order relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildChronopostPickupPointOrderQuery The current query, for fluid interface
     */
    public function joinOrder($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Order');

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
            $this->addJoinObject($join, 'Order');
        }

        return $this;
    }

    /**
     * Use the Order relation Order object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \ChronopostPickupPoint\Model\Thelia\Model\OrderQuery A secondary query class using the current class as primary query
     */
    public function useOrderQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinOrder($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Order', '\ChronopostPickupPoint\Model\Thelia\Model\OrderQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildChronopostPickupPointOrder $chronopostPickupPointOrder Object to remove from the list of results
     *
     * @return ChildChronopostPickupPointOrderQuery The current query, for fluid interface
     */
    public function prune($chronopostPickupPointOrder = null)
    {
        if ($chronopostPickupPointOrder) {
            $this->addUsingAlias(ChronopostPickupPointOrderTableMap::ID, $chronopostPickupPointOrder->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the chronopost_pickup_point_order table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ChronopostPickupPointOrderTableMap::DATABASE_NAME);
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
            ChronopostPickupPointOrderTableMap::clearInstancePool();
            ChronopostPickupPointOrderTableMap::clearRelatedInstancePool();

            $con->commit();
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }

        return $affectedRows;
    }

    /**
     * Performs a DELETE on the database, given a ChildChronopostPickupPointOrder or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or ChildChronopostPickupPointOrder object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(ChronopostPickupPointOrderTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(ChronopostPickupPointOrderTableMap::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();


        ChronopostPickupPointOrderTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            ChronopostPickupPointOrderTableMap::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }
    }

} // ChronopostPickupPointOrderQuery
