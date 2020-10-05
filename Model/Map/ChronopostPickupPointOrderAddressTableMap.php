<?php

namespace ChronopostPickupPoint\Model\Map;

use ChronopostPickupPoint\Model\ChronopostPickupPointOrderAddress;
use ChronopostPickupPoint\Model\ChronopostPickupPointOrderAddressQuery;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\InstancePoolTrait;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\DataFetcher\DataFetcherInterface;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\RelationMap;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Map\TableMapTrait;


/**
 * This class defines the structure of the 'chronopost_pickup_point_order_address' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class ChronopostPickupPointOrderAddressTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;
    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'ChronopostPickupPoint.Model.Map.ChronopostPickupPointOrderAddressTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'thelia';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'chronopost_pickup_point_order_address';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\ChronopostPickupPoint\\Model\\ChronopostPickupPointOrderAddress';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'ChronopostPickupPoint.Model.ChronopostPickupPointOrderAddress';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 8;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 8;

    /**
     * the column name for the ID field
     */
    const ID = 'chronopost_pickup_point_order_address.ID';

    /**
     * the column name for the COMPANY field
     */
    const COMPANY = 'chronopost_pickup_point_order_address.COMPANY';

    /**
     * the column name for the ADDRESS1 field
     */
    const ADDRESS1 = 'chronopost_pickup_point_order_address.ADDRESS1';

    /**
     * the column name for the ADDRESS2 field
     */
    const ADDRESS2 = 'chronopost_pickup_point_order_address.ADDRESS2';

    /**
     * the column name for the ADDRESS3 field
     */
    const ADDRESS3 = 'chronopost_pickup_point_order_address.ADDRESS3';

    /**
     * the column name for the ZIP_CODE field
     */
    const ZIP_CODE = 'chronopost_pickup_point_order_address.ZIP_CODE';

    /**
     * the column name for the CITY field
     */
    const CITY = 'chronopost_pickup_point_order_address.CITY';

    /**
     * the column name for the COUNTRY_ID field
     */
    const COUNTRY_ID = 'chronopost_pickup_point_order_address.COUNTRY_ID';

    /**
     * The default string format for model objects of the related table
     */
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        self::TYPE_PHPNAME       => array('Id', 'Company', 'Address1', 'Address2', 'Address3', 'ZipCode', 'City', 'CountryId', ),
        self::TYPE_STUDLYPHPNAME => array('id', 'company', 'address1', 'address2', 'address3', 'zipCode', 'city', 'countryId', ),
        self::TYPE_COLNAME       => array(ChronopostPickupPointOrderAddressTableMap::ID, ChronopostPickupPointOrderAddressTableMap::COMPANY, ChronopostPickupPointOrderAddressTableMap::ADDRESS1, ChronopostPickupPointOrderAddressTableMap::ADDRESS2, ChronopostPickupPointOrderAddressTableMap::ADDRESS3, ChronopostPickupPointOrderAddressTableMap::ZIP_CODE, ChronopostPickupPointOrderAddressTableMap::CITY, ChronopostPickupPointOrderAddressTableMap::COUNTRY_ID, ),
        self::TYPE_RAW_COLNAME   => array('ID', 'COMPANY', 'ADDRESS1', 'ADDRESS2', 'ADDRESS3', 'ZIP_CODE', 'CITY', 'COUNTRY_ID', ),
        self::TYPE_FIELDNAME     => array('id', 'company', 'Address1', 'Address2', 'Address3', 'zip_code', 'city', 'country_id', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'Company' => 1, 'Address1' => 2, 'Address2' => 3, 'Address3' => 4, 'ZipCode' => 5, 'City' => 6, 'CountryId' => 7, ),
        self::TYPE_STUDLYPHPNAME => array('id' => 0, 'company' => 1, 'address1' => 2, 'address2' => 3, 'address3' => 4, 'zipCode' => 5, 'city' => 6, 'countryId' => 7, ),
        self::TYPE_COLNAME       => array(ChronopostPickupPointOrderAddressTableMap::ID => 0, ChronopostPickupPointOrderAddressTableMap::COMPANY => 1, ChronopostPickupPointOrderAddressTableMap::ADDRESS1 => 2, ChronopostPickupPointOrderAddressTableMap::ADDRESS2 => 3, ChronopostPickupPointOrderAddressTableMap::ADDRESS3 => 4, ChronopostPickupPointOrderAddressTableMap::ZIP_CODE => 5, ChronopostPickupPointOrderAddressTableMap::CITY => 6, ChronopostPickupPointOrderAddressTableMap::COUNTRY_ID => 7, ),
        self::TYPE_RAW_COLNAME   => array('ID' => 0, 'COMPANY' => 1, 'ADDRESS1' => 2, 'ADDRESS2' => 3, 'ADDRESS3' => 4, 'ZIP_CODE' => 5, 'CITY' => 6, 'COUNTRY_ID' => 7, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'company' => 1, 'Address1' => 2, 'Address2' => 3, 'Address3' => 4, 'zip_code' => 5, 'city' => 6, 'country_id' => 7, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, )
    );

    /**
     * Initialize the table attributes and columns
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws PropelException
     */
    public function initialize()
    {
        // attributes
        $this->setName('chronopost_pickup_point_order_address');
        $this->setPhpName('ChronopostPickupPointOrderAddress');
        $this->setClassName('\\ChronopostPickupPoint\\Model\\ChronopostPickupPointOrderAddress');
        $this->setPackage('ChronopostPickupPoint.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('ID', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('COMPANY', 'Company', 'VARCHAR', false, 255, null);
        $this->addColumn('ADDRESS1', 'Address1', 'VARCHAR', false, 255, null);
        $this->addColumn('ADDRESS2', 'Address2', 'VARCHAR', false, 255, null);
        $this->addColumn('ADDRESS3', 'Address3', 'VARCHAR', false, 255, null);
        $this->addColumn('ZIP_CODE', 'ZipCode', 'VARCHAR', false, 255, null);
        $this->addColumn('CITY', 'City', 'VARCHAR', false, 255, null);
        $this->addForeignKey('COUNTRY_ID', 'CountryId', 'INTEGER', 'country', 'ID', true, null, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Country', '\\ChronopostPickupPoint\\Model\\Thelia\\Model\\Country', RelationMap::MANY_TO_ONE, array('country_id' => 'id', ), 'RESTRICT', 'RESTRICT');
    } // buildRelations()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     */
    public static function getPrimaryKeyHashFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        // If the PK cannot be derived from the row, return NULL.
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
    }

    /**
     * Retrieves the primary key from the DB resultset row
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, an array of the primary key columns will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return mixed The primary key of the row
     */
    public static function getPrimaryKeyFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {

            return (int) $row[
                            $indexType == TableMap::TYPE_NUM
                            ? 0 + $offset
                            : self::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)
                        ];
    }

    /**
     * The class that the tableMap will make instances of.
     *
     * If $withPrefix is true, the returned path
     * uses a dot-path notation which is translated into a path
     * relative to a location on the PHP include_path.
     * (e.g. path.to.MyClass -> 'path/to/MyClass.php')
     *
     * @param boolean $withPrefix Whether or not to return the path with the class name
     * @return string path.to.ClassName
     */
    public static function getOMClass($withPrefix = true)
    {
        return $withPrefix ? ChronopostPickupPointOrderAddressTableMap::CLASS_DEFAULT : ChronopostPickupPointOrderAddressTableMap::OM_CLASS;
    }

    /**
     * Populates an object of the default type or an object that inherit from the default.
     *
     * @param array  $row       row returned by DataFetcher->fetch().
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                 One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     * @return array (ChronopostPickupPointOrderAddress object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = ChronopostPickupPointOrderAddressTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = ChronopostPickupPointOrderAddressTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + ChronopostPickupPointOrderAddressTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = ChronopostPickupPointOrderAddressTableMap::OM_CLASS;
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            ChronopostPickupPointOrderAddressTableMap::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
    }

    /**
     * The returned array will contain objects of the default type or
     * objects that inherit from the default.
     *
     * @param DataFetcherInterface $dataFetcher
     * @return array
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
    public static function populateObjects(DataFetcherInterface $dataFetcher)
    {
        $results = array();

        // set the class once to avoid overhead in the loop
        $cls = static::getOMClass(false);
        // populate the object(s)
        while ($row = $dataFetcher->fetch()) {
            $key = ChronopostPickupPointOrderAddressTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = ChronopostPickupPointOrderAddressTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                ChronopostPickupPointOrderAddressTableMap::addInstanceToPool($obj, $key);
            } // if key exists
        }

        return $results;
    }
    /**
     * Add all the columns needed to create a new object.
     *
     * Note: any columns that were marked with lazyLoad="true" in the
     * XML schema will not be added to the select list and only loaded
     * on demand.
     *
     * @param Criteria $criteria object containing the columns to add.
     * @param string   $alias    optional table alias
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
    public static function addSelectColumns(Criteria $criteria, $alias = null)
    {
        if (null === $alias) {
            $criteria->addSelectColumn(ChronopostPickupPointOrderAddressTableMap::ID);
            $criteria->addSelectColumn(ChronopostPickupPointOrderAddressTableMap::COMPANY);
            $criteria->addSelectColumn(ChronopostPickupPointOrderAddressTableMap::ADDRESS1);
            $criteria->addSelectColumn(ChronopostPickupPointOrderAddressTableMap::ADDRESS2);
            $criteria->addSelectColumn(ChronopostPickupPointOrderAddressTableMap::ADDRESS3);
            $criteria->addSelectColumn(ChronopostPickupPointOrderAddressTableMap::ZIP_CODE);
            $criteria->addSelectColumn(ChronopostPickupPointOrderAddressTableMap::CITY);
            $criteria->addSelectColumn(ChronopostPickupPointOrderAddressTableMap::COUNTRY_ID);
        } else {
            $criteria->addSelectColumn($alias . '.ID');
            $criteria->addSelectColumn($alias . '.COMPANY');
            $criteria->addSelectColumn($alias . '.ADDRESS1');
            $criteria->addSelectColumn($alias . '.ADDRESS2');
            $criteria->addSelectColumn($alias . '.ADDRESS3');
            $criteria->addSelectColumn($alias . '.ZIP_CODE');
            $criteria->addSelectColumn($alias . '.CITY');
            $criteria->addSelectColumn($alias . '.COUNTRY_ID');
        }
    }

    /**
     * Returns the TableMap related to this object.
     * This method is not needed for general use but a specific application could have a need.
     * @return TableMap
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
    public static function getTableMap()
    {
        return Propel::getServiceContainer()->getDatabaseMap(ChronopostPickupPointOrderAddressTableMap::DATABASE_NAME)->getTable(ChronopostPickupPointOrderAddressTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
      $dbMap = Propel::getServiceContainer()->getDatabaseMap(ChronopostPickupPointOrderAddressTableMap::DATABASE_NAME);
      if (!$dbMap->hasTable(ChronopostPickupPointOrderAddressTableMap::TABLE_NAME)) {
        $dbMap->addTableObject(new ChronopostPickupPointOrderAddressTableMap());
      }
    }

    /**
     * Performs a DELETE on the database, given a ChronopostPickupPointOrderAddress or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or ChronopostPickupPointOrderAddress object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
     public static function doDelete($values, ConnectionInterface $con = null)
     {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ChronopostPickupPointOrderAddressTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \ChronopostPickupPoint\Model\ChronopostPickupPointOrderAddress) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(ChronopostPickupPointOrderAddressTableMap::DATABASE_NAME);
            $criteria->add(ChronopostPickupPointOrderAddressTableMap::ID, (array) $values, Criteria::IN);
        }

        $query = ChronopostPickupPointOrderAddressQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) { ChronopostPickupPointOrderAddressTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) { ChronopostPickupPointOrderAddressTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the chronopost_pickup_point_order_address table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return ChronopostPickupPointOrderAddressQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a ChronopostPickupPointOrderAddress or Criteria object.
     *
     * @param mixed               $criteria Criteria or ChronopostPickupPointOrderAddress object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ChronopostPickupPointOrderAddressTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from ChronopostPickupPointOrderAddress object
        }

        if ($criteria->containsKey(ChronopostPickupPointOrderAddressTableMap::ID) && $criteria->keyContainsValue(ChronopostPickupPointOrderAddressTableMap::ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.ChronopostPickupPointOrderAddressTableMap::ID.')');
        }


        // Set the correct dbName
        $query = ChronopostPickupPointOrderAddressQuery::create()->mergeWith($criteria);

        try {
            // use transaction because $criteria could contain info
            // for more than one table (I guess, conceivably)
            $con->beginTransaction();
            $pk = $query->doInsert($con);
            $con->commit();
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }

        return $pk;
    }

} // ChronopostPickupPointOrderAddressTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
ChronopostPickupPointOrderAddressTableMap::buildTableMap();
