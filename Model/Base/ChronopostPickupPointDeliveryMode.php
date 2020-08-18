<?php

namespace ChronopostPickupPoint\Model\Base;

use \Exception;
use \PDO;
use ChronopostPickupPoint\Model\ChronopostPickupPointAreaFreeshipping as ChildChronopostPickupPointAreaFreeshipping;
use ChronopostPickupPoint\Model\ChronopostPickupPointAreaFreeshippingQuery as ChildChronopostPickupPointAreaFreeshippingQuery;
use ChronopostPickupPoint\Model\ChronopostPickupPointDeliveryMode as ChildChronopostPickupPointDeliveryMode;
use ChronopostPickupPoint\Model\ChronopostPickupPointDeliveryModeQuery as ChildChronopostPickupPointDeliveryModeQuery;
use ChronopostPickupPoint\Model\ChronopostPickupPointPrice as ChildChronopostPickupPointPrice;
use ChronopostPickupPoint\Model\ChronopostPickupPointPriceQuery as ChildChronopostPickupPointPriceQuery;
use ChronopostPickupPoint\Model\Map\ChronopostPickupPointDeliveryModeTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\BadMethodCallException;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Parser\AbstractParser;

abstract class ChronopostPickupPointDeliveryMode implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\ChronopostPickupPoint\\Model\\Map\\ChronopostPickupPointDeliveryModeTableMap';


    /**
     * attribute to determine if this object has previously been saved.
     * @var boolean
     */
    protected $new = true;

    /**
     * attribute to determine whether this object has been deleted.
     * @var boolean
     */
    protected $deleted = false;

    /**
     * The columns that have been modified in current object.
     * Tracking modified columns allows us to only update modified columns.
     * @var array
     */
    protected $modifiedColumns = array();

    /**
     * The (virtual) columns that are added at runtime
     * The formatters can add supplementary columns based on a resultset
     * @var array
     */
    protected $virtualColumns = array();

    /**
     * The value for the id field.
     * @var        int
     */
    protected $id;

    /**
     * The value for the title field.
     * @var        string
     */
    protected $title;

    /**
     * The value for the code field.
     * @var        string
     */
    protected $code;

    /**
     * The value for the freeshipping_active field.
     * @var        boolean
     */
    protected $freeshipping_active;

    /**
     * The value for the freeshipping_from field.
     * @var        double
     */
    protected $freeshipping_from;

    /**
     * @var        ObjectCollection|ChildChronopostPickupPointPrice[] Collection to store aggregation of ChildChronopostPickupPointPrice objects.
     */
    protected $collChronopostPickupPointPrices;
    protected $collChronopostPickupPointPricesPartial;

    /**
     * @var        ObjectCollection|ChildChronopostPickupPointAreaFreeshipping[] Collection to store aggregation of ChildChronopostPickupPointAreaFreeshipping objects.
     */
    protected $collChronopostPickupPointAreaFreeshippings;
    protected $collChronopostPickupPointAreaFreeshippingsPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection
     */
    protected $chronopostPickupPointPricesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection
     */
    protected $chronopostPickupPointAreaFreeshippingsScheduledForDeletion = null;

    /**
     * Initializes internal state of ChronopostPickupPoint\Model\Base\ChronopostPickupPointDeliveryMode object.
     */
    public function __construct()
    {
    }

    /**
     * Returns whether the object has been modified.
     *
     * @return boolean True if the object has been modified.
     */
    public function isModified()
    {
        return !!$this->modifiedColumns;
    }

    /**
     * Has specified column been modified?
     *
     * @param  string  $col column fully qualified name (TableMap::TYPE_COLNAME), e.g. Book::AUTHOR_ID
     * @return boolean True if $col has been modified.
     */
    public function isColumnModified($col)
    {
        return $this->modifiedColumns && isset($this->modifiedColumns[$col]);
    }

    /**
     * Get the columns that have been modified in this object.
     * @return array A unique list of the modified column names for this object.
     */
    public function getModifiedColumns()
    {
        return $this->modifiedColumns ? array_keys($this->modifiedColumns) : [];
    }

    /**
     * Returns whether the object has ever been saved.  This will
     * be false, if the object was retrieved from storage or was created
     * and then saved.
     *
     * @return boolean true, if the object has never been persisted.
     */
    public function isNew()
    {
        return $this->new;
    }

    /**
     * Setter for the isNew attribute.  This method will be called
     * by Propel-generated children and objects.
     *
     * @param boolean $b the state of the object.
     */
    public function setNew($b)
    {
        $this->new = (Boolean) $b;
    }

    /**
     * Whether this object has been deleted.
     * @return boolean The deleted state of this object.
     */
    public function isDeleted()
    {
        return $this->deleted;
    }

    /**
     * Specify whether this object has been deleted.
     * @param  boolean $b The deleted state of this object.
     * @return void
     */
    public function setDeleted($b)
    {
        $this->deleted = (Boolean) $b;
    }

    /**
     * Sets the modified state for the object to be false.
     * @param  string $col If supplied, only the specified column is reset.
     * @return void
     */
    public function resetModified($col = null)
    {
        if (null !== $col) {
            if (isset($this->modifiedColumns[$col])) {
                unset($this->modifiedColumns[$col]);
            }
        } else {
            $this->modifiedColumns = array();
        }
    }

    /**
     * Compares this with another <code>ChronopostPickupPointDeliveryMode</code> instance.  If
     * <code>obj</code> is an instance of <code>ChronopostPickupPointDeliveryMode</code>, delegates to
     * <code>equals(ChronopostPickupPointDeliveryMode)</code>.  Otherwise, returns <code>false</code>.
     *
     * @param  mixed   $obj The object to compare to.
     * @return boolean Whether equal to the object specified.
     */
    public function equals($obj)
    {
        $thisclazz = get_class($this);
        if (!is_object($obj) || !($obj instanceof $thisclazz)) {
            return false;
        }

        if ($this === $obj) {
            return true;
        }

        if (null === $this->getPrimaryKey()
            || null === $obj->getPrimaryKey())  {
            return false;
        }

        return $this->getPrimaryKey() === $obj->getPrimaryKey();
    }

    /**
     * If the primary key is not null, return the hashcode of the
     * primary key. Otherwise, return the hash code of the object.
     *
     * @return int Hashcode
     */
    public function hashCode()
    {
        if (null !== $this->getPrimaryKey()) {
            return crc32(serialize($this->getPrimaryKey()));
        }

        return crc32(serialize(clone $this));
    }

    /**
     * Get the associative array of the virtual columns in this object
     *
     * @return array
     */
    public function getVirtualColumns()
    {
        return $this->virtualColumns;
    }

    /**
     * Checks the existence of a virtual column in this object
     *
     * @param  string  $name The virtual column name
     * @return boolean
     */
    public function hasVirtualColumn($name)
    {
        return array_key_exists($name, $this->virtualColumns);
    }

    /**
     * Get the value of a virtual column in this object
     *
     * @param  string $name The virtual column name
     * @return mixed
     *
     * @throws PropelException
     */
    public function getVirtualColumn($name)
    {
        if (!$this->hasVirtualColumn($name)) {
            throw new PropelException(sprintf('Cannot get value of inexistent virtual column %s.', $name));
        }

        return $this->virtualColumns[$name];
    }

    /**
     * Set the value of a virtual column in this object
     *
     * @param string $name  The virtual column name
     * @param mixed  $value The value to give to the virtual column
     *
     * @return ChronopostPickupPointDeliveryMode The current object, for fluid interface
     */
    public function setVirtualColumn($name, $value)
    {
        $this->virtualColumns[$name] = $value;

        return $this;
    }

    /**
     * Logs a message using Propel::log().
     *
     * @param  string  $msg
     * @param  int     $priority One of the Propel::LOG_* logging levels
     * @return boolean
     */
    protected function log($msg, $priority = Propel::LOG_INFO)
    {
        return Propel::log(get_class($this) . ': ' . $msg, $priority);
    }

    /**
     * Populate the current object from a string, using a given parser format
     * <code>
     * $book = new Book();
     * $book->importFrom('JSON', '{"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * @param mixed $parser A AbstractParser instance,
     *                       or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param string $data The source data to import from
     *
     * @return ChronopostPickupPointDeliveryMode The current object, for fluid interface
     */
    public function importFrom($parser, $data)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        $this->fromArray($parser->toArray($data), TableMap::TYPE_PHPNAME);

        return $this;
    }

    /**
     * Export the current object properties to a string, using a given parser format
     * <code>
     * $book = BookQuery::create()->findPk(9012);
     * echo $book->exportTo('JSON');
     *  => {"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * @param  mixed   $parser                 A AbstractParser instance, or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param  boolean $includeLazyLoadColumns (optional) Whether to include lazy load(ed) columns. Defaults to TRUE.
     * @return string  The exported data
     */
    public function exportTo($parser, $includeLazyLoadColumns = true)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        return $parser->fromArray($this->toArray(TableMap::TYPE_PHPNAME, $includeLazyLoadColumns, array(), true));
    }

    /**
     * Clean up internal collections prior to serializing
     * Avoids recursive loops that turn into segmentation faults when serializing
     */
    public function __sleep()
    {
        $this->clearAllReferences();

        return array_keys(get_object_vars($this));
    }

    /**
     * Get the [id] column value.
     *
     * @return   int
     */
    public function getId()
    {

        return $this->id;
    }

    /**
     * Get the [title] column value.
     *
     * @return   string
     */
    public function getTitle()
    {

        return $this->title;
    }

    /**
     * Get the [code] column value.
     *
     * @return   string
     */
    public function getCode()
    {

        return $this->code;
    }

    /**
     * Get the [freeshipping_active] column value.
     *
     * @return   boolean
     */
    public function getFreeshippingActive()
    {

        return $this->freeshipping_active;
    }

    /**
     * Get the [freeshipping_from] column value.
     *
     * @return   double
     */
    public function getFreeshippingFrom()
    {

        return $this->freeshipping_from;
    }

    /**
     * Set the value of [id] column.
     *
     * @param      int $v new value
     * @return   \ChronopostPickupPoint\Model\ChronopostPickupPointDeliveryMode The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[ChronopostPickupPointDeliveryModeTableMap::ID] = true;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [title] column.
     *
     * @param      string $v new value
     * @return   \ChronopostPickupPoint\Model\ChronopostPickupPointDeliveryMode The current object (for fluent API support)
     */
    public function setTitle($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->title !== $v) {
            $this->title = $v;
            $this->modifiedColumns[ChronopostPickupPointDeliveryModeTableMap::TITLE] = true;
        }


        return $this;
    } // setTitle()

    /**
     * Set the value of [code] column.
     *
     * @param      string $v new value
     * @return   \ChronopostPickupPoint\Model\ChronopostPickupPointDeliveryMode The current object (for fluent API support)
     */
    public function setCode($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->code !== $v) {
            $this->code = $v;
            $this->modifiedColumns[ChronopostPickupPointDeliveryModeTableMap::CODE] = true;
        }


        return $this;
    } // setCode()

    /**
     * Sets the value of the [freeshipping_active] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param      boolean|integer|string $v The new value
     * @return   \ChronopostPickupPoint\Model\ChronopostPickupPointDeliveryMode The current object (for fluent API support)
     */
    public function setFreeshippingActive($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->freeshipping_active !== $v) {
            $this->freeshipping_active = $v;
            $this->modifiedColumns[ChronopostPickupPointDeliveryModeTableMap::FREESHIPPING_ACTIVE] = true;
        }


        return $this;
    } // setFreeshippingActive()

    /**
     * Set the value of [freeshipping_from] column.
     *
     * @param      double $v new value
     * @return   \ChronopostPickupPoint\Model\ChronopostPickupPointDeliveryMode The current object (for fluent API support)
     */
    public function setFreeshippingFrom($v)
    {
        if ($v !== null) {
            $v = (double) $v;
        }

        if ($this->freeshipping_from !== $v) {
            $this->freeshipping_from = $v;
            $this->modifiedColumns[ChronopostPickupPointDeliveryModeTableMap::FREESHIPPING_FROM] = true;
        }


        return $this;
    } // setFreeshippingFrom()

    /**
     * Indicates whether the columns in this object are only set to default values.
     *
     * This method can be used in conjunction with isModified() to indicate whether an object is both
     * modified _and_ has some values set which are non-default.
     *
     * @return boolean Whether the columns in this object are only been set with default values.
     */
    public function hasOnlyDefaultValues()
    {
        // otherwise, everything was equal, so return TRUE
        return true;
    } // hasOnlyDefaultValues()

    /**
     * Hydrates (populates) the object variables with values from the database resultset.
     *
     * An offset (0-based "start column") is specified so that objects can be hydrated
     * with a subset of the columns in the resultset rows.  This is needed, for example,
     * for results of JOIN queries where the resultset row includes columns from two or
     * more tables.
     *
     * @param array   $row       The row returned by DataFetcher->fetch().
     * @param int     $startcol  0-based offset column which indicates which restultset column to start with.
     * @param boolean $rehydrate Whether this object is being re-hydrated from the database.
     * @param string  $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                  One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                            TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @return int             next starting column
     * @throws PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate($row, $startcol = 0, $rehydrate = false, $indexType = TableMap::TYPE_NUM)
    {
        try {


            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : ChronopostPickupPointDeliveryModeTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : ChronopostPickupPointDeliveryModeTableMap::translateFieldName('Title', TableMap::TYPE_PHPNAME, $indexType)];
            $this->title = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : ChronopostPickupPointDeliveryModeTableMap::translateFieldName('Code', TableMap::TYPE_PHPNAME, $indexType)];
            $this->code = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : ChronopostPickupPointDeliveryModeTableMap::translateFieldName('FreeshippingActive', TableMap::TYPE_PHPNAME, $indexType)];
            $this->freeshipping_active = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : ChronopostPickupPointDeliveryModeTableMap::translateFieldName('FreeshippingFrom', TableMap::TYPE_PHPNAME, $indexType)];
            $this->freeshipping_from = (null !== $col) ? (double) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 5; // 5 = ChronopostPickupPointDeliveryModeTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating \ChronopostPickupPoint\Model\ChronopostPickupPointDeliveryMode object", 0, $e);
        }
    }

    /**
     * Checks and repairs the internal consistency of the object.
     *
     * This method is executed after an already-instantiated object is re-hydrated
     * from the database.  It exists to check any foreign keys to make sure that
     * the objects related to the current object are correct based on foreign key.
     *
     * You can override this method in the stub class, but you should always invoke
     * the base method from the overridden method (i.e. parent::ensureConsistency()),
     * in case your model changes.
     *
     * @throws PropelException
     */
    public function ensureConsistency()
    {
    } // ensureConsistency

    /**
     * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
     *
     * This will only work if the object has been saved and has a valid primary key set.
     *
     * @param      boolean $deep (optional) Whether to also de-associated any related objects.
     * @param      ConnectionInterface $con (optional) The ConnectionInterface connection to use.
     * @return void
     * @throws PropelException - if this object is deleted, unsaved or doesn't have pk match in db
     */
    public function reload($deep = false, ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("Cannot reload a deleted object.");
        }

        if ($this->isNew()) {
            throw new PropelException("Cannot reload an unsaved object.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(ChronopostPickupPointDeliveryModeTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildChronopostPickupPointDeliveryModeQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->collChronopostPickupPointPrices = null;

            $this->collChronopostPickupPointAreaFreeshippings = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see ChronopostPickupPointDeliveryMode::setDeleted()
     * @see ChronopostPickupPointDeliveryMode::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(ChronopostPickupPointDeliveryModeTableMap::DATABASE_NAME);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = ChildChronopostPickupPointDeliveryModeQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            if ($ret) {
                $deleteQuery->delete($con);
                $this->postDelete($con);
                $con->commit();
                $this->setDeleted(true);
            } else {
                $con->commit();
            }
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Persists this object to the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All modified related objects will also be persisted in the doSave()
     * method.  This method wraps all precipitate database operations in a
     * single transaction.
     *
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see doSave()
     */
    public function save(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("You cannot save an object that has been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(ChronopostPickupPointDeliveryModeTableMap::DATABASE_NAME);
        }

        $con->beginTransaction();
        $isInsert = $this->isNew();
        try {
            $ret = $this->preSave($con);
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
            } else {
                $ret = $ret && $this->preUpdate($con);
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                ChronopostPickupPointDeliveryModeTableMap::addInstanceToPool($this);
            } else {
                $affectedRows = 0;
            }
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs the work of inserting or updating the row in the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All related objects are also updated in this method.
     *
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see save()
     */
    protected function doSave(ConnectionInterface $con)
    {
        $affectedRows = 0; // initialize var to track total num of affected rows
        if (!$this->alreadyInSave) {
            $this->alreadyInSave = true;

            if ($this->isNew() || $this->isModified()) {
                // persist changes
                if ($this->isNew()) {
                    $this->doInsert($con);
                } else {
                    $this->doUpdate($con);
                }
                $affectedRows += 1;
                $this->resetModified();
            }

            if ($this->chronopostPickupPointPricesScheduledForDeletion !== null) {
                if (!$this->chronopostPickupPointPricesScheduledForDeletion->isEmpty()) {
                    \ChronopostPickupPoint\Model\ChronopostPickupPointPriceQuery::create()
                        ->filterByPrimaryKeys($this->chronopostPickupPointPricesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->chronopostPickupPointPricesScheduledForDeletion = null;
                }
            }

                if ($this->collChronopostPickupPointPrices !== null) {
            foreach ($this->collChronopostPickupPointPrices as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->chronopostPickupPointAreaFreeshippingsScheduledForDeletion !== null) {
                if (!$this->chronopostPickupPointAreaFreeshippingsScheduledForDeletion->isEmpty()) {
                    \ChronopostPickupPoint\Model\ChronopostPickupPointAreaFreeshippingQuery::create()
                        ->filterByPrimaryKeys($this->chronopostPickupPointAreaFreeshippingsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->chronopostPickupPointAreaFreeshippingsScheduledForDeletion = null;
                }
            }

                if ($this->collChronopostPickupPointAreaFreeshippings !== null) {
            foreach ($this->collChronopostPickupPointAreaFreeshippings as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            $this->alreadyInSave = false;

        }

        return $affectedRows;
    } // doSave()

    /**
     * Insert the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @throws PropelException
     * @see doSave()
     */
    protected function doInsert(ConnectionInterface $con)
    {
        $modifiedColumns = array();
        $index = 0;

        $this->modifiedColumns[ChronopostPickupPointDeliveryModeTableMap::ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . ChronopostPickupPointDeliveryModeTableMap::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(ChronopostPickupPointDeliveryModeTableMap::ID)) {
            $modifiedColumns[':p' . $index++]  = 'ID';
        }
        if ($this->isColumnModified(ChronopostPickupPointDeliveryModeTableMap::TITLE)) {
            $modifiedColumns[':p' . $index++]  = 'TITLE';
        }
        if ($this->isColumnModified(ChronopostPickupPointDeliveryModeTableMap::CODE)) {
            $modifiedColumns[':p' . $index++]  = 'CODE';
        }
        if ($this->isColumnModified(ChronopostPickupPointDeliveryModeTableMap::FREESHIPPING_ACTIVE)) {
            $modifiedColumns[':p' . $index++]  = 'FREESHIPPING_ACTIVE';
        }
        if ($this->isColumnModified(ChronopostPickupPointDeliveryModeTableMap::FREESHIPPING_FROM)) {
            $modifiedColumns[':p' . $index++]  = 'FREESHIPPING_FROM';
        }

        $sql = sprintf(
            'INSERT INTO chronopost_pickup_point_delivery_mode (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'ID':
                        $stmt->bindValue($identifier, $this->id, PDO::PARAM_INT);
                        break;
                    case 'TITLE':
                        $stmt->bindValue($identifier, $this->title, PDO::PARAM_STR);
                        break;
                    case 'CODE':
                        $stmt->bindValue($identifier, $this->code, PDO::PARAM_STR);
                        break;
                    case 'FREESHIPPING_ACTIVE':
                        $stmt->bindValue($identifier, (int) $this->freeshipping_active, PDO::PARAM_INT);
                        break;
                    case 'FREESHIPPING_FROM':
                        $stmt->bindValue($identifier, $this->freeshipping_from, PDO::PARAM_STR);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), 0, $e);
        }

        try {
            $pk = $con->lastInsertId();
        } catch (Exception $e) {
            throw new PropelException('Unable to get autoincrement id.', 0, $e);
        }
        $this->setId($pk);

        $this->setNew(false);
    }

    /**
     * Update the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @return Integer Number of updated rows
     * @see doSave()
     */
    protected function doUpdate(ConnectionInterface $con)
    {
        $selectCriteria = $this->buildPkeyCriteria();
        $valuesCriteria = $this->buildCriteria();

        return $selectCriteria->doUpdate($valuesCriteria, $con);
    }

    /**
     * Retrieves a field from the object by name passed in as a string.
     *
     * @param      string $name name
     * @param      string $type The type of fieldname the $name is of:
     *                     one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                     TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                     Defaults to TableMap::TYPE_PHPNAME.
     * @return mixed Value of field.
     */
    public function getByName($name, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = ChronopostPickupPointDeliveryModeTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
        $field = $this->getByPosition($pos);

        return $field;
    }

    /**
     * Retrieves a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param      int $pos position in xml schema
     * @return mixed Value of field at $pos
     */
    public function getByPosition($pos)
    {
        switch ($pos) {
            case 0:
                return $this->getId();
                break;
            case 1:
                return $this->getTitle();
                break;
            case 2:
                return $this->getCode();
                break;
            case 3:
                return $this->getFreeshippingActive();
                break;
            case 4:
                return $this->getFreeshippingFrom();
                break;
            default:
                return null;
                break;
        } // switch()
    }

    /**
     * Exports the object as an array.
     *
     * You can specify the key type of the array by passing one of the class
     * type constants.
     *
     * @param     string  $keyType (optional) One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME,
     *                    TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                    Defaults to TableMap::TYPE_PHPNAME.
     * @param     boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to TRUE.
     * @param     array $alreadyDumpedObjects List of objects to skip to avoid recursion
     * @param     boolean $includeForeignObjects (optional) Whether to include hydrated related objects. Default to FALSE.
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = TableMap::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array(), $includeForeignObjects = false)
    {
        if (isset($alreadyDumpedObjects['ChronopostPickupPointDeliveryMode'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['ChronopostPickupPointDeliveryMode'][$this->getPrimaryKey()] = true;
        $keys = ChronopostPickupPointDeliveryModeTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getTitle(),
            $keys[2] => $this->getCode(),
            $keys[3] => $this->getFreeshippingActive(),
            $keys[4] => $this->getFreeshippingFrom(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->collChronopostPickupPointPrices) {
                $result['ChronopostPickupPointPrices'] = $this->collChronopostPickupPointPrices->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collChronopostPickupPointAreaFreeshippings) {
                $result['ChronopostPickupPointAreaFreeshippings'] = $this->collChronopostPickupPointAreaFreeshippings->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
        }

        return $result;
    }

    /**
     * Sets a field from the object by name passed in as a string.
     *
     * @param      string $name
     * @param      mixed  $value field value
     * @param      string $type The type of fieldname the $name is of:
     *                     one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                     TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                     Defaults to TableMap::TYPE_PHPNAME.
     * @return void
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = ChronopostPickupPointDeliveryModeTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param      int $pos position in xml schema
     * @param      mixed $value field value
     * @return void
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setTitle($value);
                break;
            case 2:
                $this->setCode($value);
                break;
            case 3:
                $this->setFreeshippingActive($value);
                break;
            case 4:
                $this->setFreeshippingFrom($value);
                break;
        } // switch()
    }

    /**
     * Populates the object using an array.
     *
     * This is particularly useful when populating an object from one of the
     * request arrays (e.g. $_POST).  This method goes through the column
     * names, checking to see whether a matching key exists in populated
     * array. If so the setByName() method is called for that column.
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_PHPNAME.
     *
     * @param      array  $arr     An array to populate the object from.
     * @param      string $keyType The type of keys the array uses.
     * @return void
     */
    public function fromArray($arr, $keyType = TableMap::TYPE_PHPNAME)
    {
        $keys = ChronopostPickupPointDeliveryModeTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setTitle($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setCode($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setFreeshippingActive($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setFreeshippingFrom($arr[$keys[4]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(ChronopostPickupPointDeliveryModeTableMap::DATABASE_NAME);

        if ($this->isColumnModified(ChronopostPickupPointDeliveryModeTableMap::ID)) $criteria->add(ChronopostPickupPointDeliveryModeTableMap::ID, $this->id);
        if ($this->isColumnModified(ChronopostPickupPointDeliveryModeTableMap::TITLE)) $criteria->add(ChronopostPickupPointDeliveryModeTableMap::TITLE, $this->title);
        if ($this->isColumnModified(ChronopostPickupPointDeliveryModeTableMap::CODE)) $criteria->add(ChronopostPickupPointDeliveryModeTableMap::CODE, $this->code);
        if ($this->isColumnModified(ChronopostPickupPointDeliveryModeTableMap::FREESHIPPING_ACTIVE)) $criteria->add(ChronopostPickupPointDeliveryModeTableMap::FREESHIPPING_ACTIVE, $this->freeshipping_active);
        if ($this->isColumnModified(ChronopostPickupPointDeliveryModeTableMap::FREESHIPPING_FROM)) $criteria->add(ChronopostPickupPointDeliveryModeTableMap::FREESHIPPING_FROM, $this->freeshipping_from);

        return $criteria;
    }

    /**
     * Builds a Criteria object containing the primary key for this object.
     *
     * Unlike buildCriteria() this method includes the primary key values regardless
     * of whether or not they have been modified.
     *
     * @return Criteria The Criteria object containing value(s) for primary key(s).
     */
    public function buildPkeyCriteria()
    {
        $criteria = new Criteria(ChronopostPickupPointDeliveryModeTableMap::DATABASE_NAME);
        $criteria->add(ChronopostPickupPointDeliveryModeTableMap::ID, $this->id);

        return $criteria;
    }

    /**
     * Returns the primary key for this object (row).
     * @return   int
     */
    public function getPrimaryKey()
    {
        return $this->getId();
    }

    /**
     * Generic method to set the primary key (id column).
     *
     * @param       int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setId($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {

        return null === $this->getId();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of \ChronopostPickupPoint\Model\ChronopostPickupPointDeliveryMode (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setTitle($this->getTitle());
        $copyObj->setCode($this->getCode());
        $copyObj->setFreeshippingActive($this->getFreeshippingActive());
        $copyObj->setFreeshippingFrom($this->getFreeshippingFrom());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getChronopostPickupPointPrices() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addChronopostPickupPointPrice($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getChronopostPickupPointAreaFreeshippings() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addChronopostPickupPointAreaFreeshipping($relObj->copy($deepCopy));
                }
            }

        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setId(NULL); // this is a auto-increment column, so set to default value
        }
    }

    /**
     * Makes a copy of this object that will be inserted as a new row in table when saved.
     * It creates a new object filling in the simple attributes, but skipping any primary
     * keys that are defined for the table.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @return                 \ChronopostPickupPoint\Model\ChronopostPickupPointDeliveryMode Clone of current object.
     * @throws PropelException
     */
    public function copy($deepCopy = false)
    {
        // we use get_class(), because this might be a subclass
        $clazz = get_class($this);
        $copyObj = new $clazz();
        $this->copyInto($copyObj, $deepCopy);

        return $copyObj;
    }


    /**
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param      string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('ChronopostPickupPointPrice' == $relationName) {
            return $this->initChronopostPickupPointPrices();
        }
        if ('ChronopostPickupPointAreaFreeshipping' == $relationName) {
            return $this->initChronopostPickupPointAreaFreeshippings();
        }
    }

    /**
     * Clears out the collChronopostPickupPointPrices collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addChronopostPickupPointPrices()
     */
    public function clearChronopostPickupPointPrices()
    {
        $this->collChronopostPickupPointPrices = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collChronopostPickupPointPrices collection loaded partially.
     */
    public function resetPartialChronopostPickupPointPrices($v = true)
    {
        $this->collChronopostPickupPointPricesPartial = $v;
    }

    /**
     * Initializes the collChronopostPickupPointPrices collection.
     *
     * By default this just sets the collChronopostPickupPointPrices collection to an empty array (like clearcollChronopostPickupPointPrices());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initChronopostPickupPointPrices($overrideExisting = true)
    {
        if (null !== $this->collChronopostPickupPointPrices && !$overrideExisting) {
            return;
        }
        $this->collChronopostPickupPointPrices = new ObjectCollection();
        $this->collChronopostPickupPointPrices->setModel('\ChronopostPickupPoint\Model\ChronopostPickupPointPrice');
    }

    /**
     * Gets an array of ChildChronopostPickupPointPrice objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildChronopostPickupPointDeliveryMode is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return Collection|ChildChronopostPickupPointPrice[] List of ChildChronopostPickupPointPrice objects
     * @throws PropelException
     */
    public function getChronopostPickupPointPrices($criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collChronopostPickupPointPricesPartial && !$this->isNew();
        if (null === $this->collChronopostPickupPointPrices || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collChronopostPickupPointPrices) {
                // return empty collection
                $this->initChronopostPickupPointPrices();
            } else {
                $collChronopostPickupPointPrices = ChildChronopostPickupPointPriceQuery::create(null, $criteria)
                    ->filterByChronopostPickupPointDeliveryMode($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collChronopostPickupPointPricesPartial && count($collChronopostPickupPointPrices)) {
                        $this->initChronopostPickupPointPrices(false);

                        foreach ($collChronopostPickupPointPrices as $obj) {
                            if (false == $this->collChronopostPickupPointPrices->contains($obj)) {
                                $this->collChronopostPickupPointPrices->append($obj);
                            }
                        }

                        $this->collChronopostPickupPointPricesPartial = true;
                    }

                    reset($collChronopostPickupPointPrices);

                    return $collChronopostPickupPointPrices;
                }

                if ($partial && $this->collChronopostPickupPointPrices) {
                    foreach ($this->collChronopostPickupPointPrices as $obj) {
                        if ($obj->isNew()) {
                            $collChronopostPickupPointPrices[] = $obj;
                        }
                    }
                }

                $this->collChronopostPickupPointPrices = $collChronopostPickupPointPrices;
                $this->collChronopostPickupPointPricesPartial = false;
            }
        }

        return $this->collChronopostPickupPointPrices;
    }

    /**
     * Sets a collection of ChronopostPickupPointPrice objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $chronopostPickupPointPrices A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return   ChildChronopostPickupPointDeliveryMode The current object (for fluent API support)
     */
    public function setChronopostPickupPointPrices(Collection $chronopostPickupPointPrices, ConnectionInterface $con = null)
    {
        $chronopostPickupPointPricesToDelete = $this->getChronopostPickupPointPrices(new Criteria(), $con)->diff($chronopostPickupPointPrices);


        $this->chronopostPickupPointPricesScheduledForDeletion = $chronopostPickupPointPricesToDelete;

        foreach ($chronopostPickupPointPricesToDelete as $chronopostPickupPointPriceRemoved) {
            $chronopostPickupPointPriceRemoved->setChronopostPickupPointDeliveryMode(null);
        }

        $this->collChronopostPickupPointPrices = null;
        foreach ($chronopostPickupPointPrices as $chronopostPickupPointPrice) {
            $this->addChronopostPickupPointPrice($chronopostPickupPointPrice);
        }

        $this->collChronopostPickupPointPrices = $chronopostPickupPointPrices;
        $this->collChronopostPickupPointPricesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related ChronopostPickupPointPrice objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related ChronopostPickupPointPrice objects.
     * @throws PropelException
     */
    public function countChronopostPickupPointPrices(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collChronopostPickupPointPricesPartial && !$this->isNew();
        if (null === $this->collChronopostPickupPointPrices || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collChronopostPickupPointPrices) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getChronopostPickupPointPrices());
            }

            $query = ChildChronopostPickupPointPriceQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByChronopostPickupPointDeliveryMode($this)
                ->count($con);
        }

        return count($this->collChronopostPickupPointPrices);
    }

    /**
     * Method called to associate a ChildChronopostPickupPointPrice object to this object
     * through the ChildChronopostPickupPointPrice foreign key attribute.
     *
     * @param    ChildChronopostPickupPointPrice $l ChildChronopostPickupPointPrice
     * @return   \ChronopostPickupPoint\Model\ChronopostPickupPointDeliveryMode The current object (for fluent API support)
     */
    public function addChronopostPickupPointPrice(ChildChronopostPickupPointPrice $l)
    {
        if ($this->collChronopostPickupPointPrices === null) {
            $this->initChronopostPickupPointPrices();
            $this->collChronopostPickupPointPricesPartial = true;
        }

        if (!in_array($l, $this->collChronopostPickupPointPrices->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddChronopostPickupPointPrice($l);
        }

        return $this;
    }

    /**
     * @param ChronopostPickupPointPrice $chronopostPickupPointPrice The chronopostPickupPointPrice object to add.
     */
    protected function doAddChronopostPickupPointPrice($chronopostPickupPointPrice)
    {
        $this->collChronopostPickupPointPrices[]= $chronopostPickupPointPrice;
        $chronopostPickupPointPrice->setChronopostPickupPointDeliveryMode($this);
    }

    /**
     * @param  ChronopostPickupPointPrice $chronopostPickupPointPrice The chronopostPickupPointPrice object to remove.
     * @return ChildChronopostPickupPointDeliveryMode The current object (for fluent API support)
     */
    public function removeChronopostPickupPointPrice($chronopostPickupPointPrice)
    {
        if ($this->getChronopostPickupPointPrices()->contains($chronopostPickupPointPrice)) {
            $this->collChronopostPickupPointPrices->remove($this->collChronopostPickupPointPrices->search($chronopostPickupPointPrice));
            if (null === $this->chronopostPickupPointPricesScheduledForDeletion) {
                $this->chronopostPickupPointPricesScheduledForDeletion = clone $this->collChronopostPickupPointPrices;
                $this->chronopostPickupPointPricesScheduledForDeletion->clear();
            }
            $this->chronopostPickupPointPricesScheduledForDeletion[]= clone $chronopostPickupPointPrice;
            $chronopostPickupPointPrice->setChronopostPickupPointDeliveryMode(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this ChronopostPickupPointDeliveryMode is new, it will return
     * an empty collection; or if this ChronopostPickupPointDeliveryMode has previously
     * been saved, it will retrieve related ChronopostPickupPointPrices from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in ChronopostPickupPointDeliveryMode.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return Collection|ChildChronopostPickupPointPrice[] List of ChildChronopostPickupPointPrice objects
     */
    public function getChronopostPickupPointPricesJoinArea($criteria = null, $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildChronopostPickupPointPriceQuery::create(null, $criteria);
        $query->joinWith('Area', $joinBehavior);

        return $this->getChronopostPickupPointPrices($query, $con);
    }

    /**
     * Clears out the collChronopostPickupPointAreaFreeshippings collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addChronopostPickupPointAreaFreeshippings()
     */
    public function clearChronopostPickupPointAreaFreeshippings()
    {
        $this->collChronopostPickupPointAreaFreeshippings = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collChronopostPickupPointAreaFreeshippings collection loaded partially.
     */
    public function resetPartialChronopostPickupPointAreaFreeshippings($v = true)
    {
        $this->collChronopostPickupPointAreaFreeshippingsPartial = $v;
    }

    /**
     * Initializes the collChronopostPickupPointAreaFreeshippings collection.
     *
     * By default this just sets the collChronopostPickupPointAreaFreeshippings collection to an empty array (like clearcollChronopostPickupPointAreaFreeshippings());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initChronopostPickupPointAreaFreeshippings($overrideExisting = true)
    {
        if (null !== $this->collChronopostPickupPointAreaFreeshippings && !$overrideExisting) {
            return;
        }
        $this->collChronopostPickupPointAreaFreeshippings = new ObjectCollection();
        $this->collChronopostPickupPointAreaFreeshippings->setModel('\ChronopostPickupPoint\Model\ChronopostPickupPointAreaFreeshipping');
    }

    /**
     * Gets an array of ChildChronopostPickupPointAreaFreeshipping objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildChronopostPickupPointDeliveryMode is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return Collection|ChildChronopostPickupPointAreaFreeshipping[] List of ChildChronopostPickupPointAreaFreeshipping objects
     * @throws PropelException
     */
    public function getChronopostPickupPointAreaFreeshippings($criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collChronopostPickupPointAreaFreeshippingsPartial && !$this->isNew();
        if (null === $this->collChronopostPickupPointAreaFreeshippings || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collChronopostPickupPointAreaFreeshippings) {
                // return empty collection
                $this->initChronopostPickupPointAreaFreeshippings();
            } else {
                $collChronopostPickupPointAreaFreeshippings = ChildChronopostPickupPointAreaFreeshippingQuery::create(null, $criteria)
                    ->filterByChronopostPickupPointDeliveryMode($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collChronopostPickupPointAreaFreeshippingsPartial && count($collChronopostPickupPointAreaFreeshippings)) {
                        $this->initChronopostPickupPointAreaFreeshippings(false);

                        foreach ($collChronopostPickupPointAreaFreeshippings as $obj) {
                            if (false == $this->collChronopostPickupPointAreaFreeshippings->contains($obj)) {
                                $this->collChronopostPickupPointAreaFreeshippings->append($obj);
                            }
                        }

                        $this->collChronopostPickupPointAreaFreeshippingsPartial = true;
                    }

                    reset($collChronopostPickupPointAreaFreeshippings);

                    return $collChronopostPickupPointAreaFreeshippings;
                }

                if ($partial && $this->collChronopostPickupPointAreaFreeshippings) {
                    foreach ($this->collChronopostPickupPointAreaFreeshippings as $obj) {
                        if ($obj->isNew()) {
                            $collChronopostPickupPointAreaFreeshippings[] = $obj;
                        }
                    }
                }

                $this->collChronopostPickupPointAreaFreeshippings = $collChronopostPickupPointAreaFreeshippings;
                $this->collChronopostPickupPointAreaFreeshippingsPartial = false;
            }
        }

        return $this->collChronopostPickupPointAreaFreeshippings;
    }

    /**
     * Sets a collection of ChronopostPickupPointAreaFreeshipping objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $chronopostPickupPointAreaFreeshippings A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return   ChildChronopostPickupPointDeliveryMode The current object (for fluent API support)
     */
    public function setChronopostPickupPointAreaFreeshippings(Collection $chronopostPickupPointAreaFreeshippings, ConnectionInterface $con = null)
    {
        $chronopostPickupPointAreaFreeshippingsToDelete = $this->getChronopostPickupPointAreaFreeshippings(new Criteria(), $con)->diff($chronopostPickupPointAreaFreeshippings);


        $this->chronopostPickupPointAreaFreeshippingsScheduledForDeletion = $chronopostPickupPointAreaFreeshippingsToDelete;

        foreach ($chronopostPickupPointAreaFreeshippingsToDelete as $chronopostPickupPointAreaFreeshippingRemoved) {
            $chronopostPickupPointAreaFreeshippingRemoved->setChronopostPickupPointDeliveryMode(null);
        }

        $this->collChronopostPickupPointAreaFreeshippings = null;
        foreach ($chronopostPickupPointAreaFreeshippings as $chronopostPickupPointAreaFreeshipping) {
            $this->addChronopostPickupPointAreaFreeshipping($chronopostPickupPointAreaFreeshipping);
        }

        $this->collChronopostPickupPointAreaFreeshippings = $chronopostPickupPointAreaFreeshippings;
        $this->collChronopostPickupPointAreaFreeshippingsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related ChronopostPickupPointAreaFreeshipping objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related ChronopostPickupPointAreaFreeshipping objects.
     * @throws PropelException
     */
    public function countChronopostPickupPointAreaFreeshippings(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collChronopostPickupPointAreaFreeshippingsPartial && !$this->isNew();
        if (null === $this->collChronopostPickupPointAreaFreeshippings || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collChronopostPickupPointAreaFreeshippings) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getChronopostPickupPointAreaFreeshippings());
            }

            $query = ChildChronopostPickupPointAreaFreeshippingQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByChronopostPickupPointDeliveryMode($this)
                ->count($con);
        }

        return count($this->collChronopostPickupPointAreaFreeshippings);
    }

    /**
     * Method called to associate a ChildChronopostPickupPointAreaFreeshipping object to this object
     * through the ChildChronopostPickupPointAreaFreeshipping foreign key attribute.
     *
     * @param    ChildChronopostPickupPointAreaFreeshipping $l ChildChronopostPickupPointAreaFreeshipping
     * @return   \ChronopostPickupPoint\Model\ChronopostPickupPointDeliveryMode The current object (for fluent API support)
     */
    public function addChronopostPickupPointAreaFreeshipping(ChildChronopostPickupPointAreaFreeshipping $l)
    {
        if ($this->collChronopostPickupPointAreaFreeshippings === null) {
            $this->initChronopostPickupPointAreaFreeshippings();
            $this->collChronopostPickupPointAreaFreeshippingsPartial = true;
        }

        if (!in_array($l, $this->collChronopostPickupPointAreaFreeshippings->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddChronopostPickupPointAreaFreeshipping($l);
        }

        return $this;
    }

    /**
     * @param ChronopostPickupPointAreaFreeshipping $chronopostPickupPointAreaFreeshipping The chronopostPickupPointAreaFreeshipping object to add.
     */
    protected function doAddChronopostPickupPointAreaFreeshipping($chronopostPickupPointAreaFreeshipping)
    {
        $this->collChronopostPickupPointAreaFreeshippings[]= $chronopostPickupPointAreaFreeshipping;
        $chronopostPickupPointAreaFreeshipping->setChronopostPickupPointDeliveryMode($this);
    }

    /**
     * @param  ChronopostPickupPointAreaFreeshipping $chronopostPickupPointAreaFreeshipping The chronopostPickupPointAreaFreeshipping object to remove.
     * @return ChildChronopostPickupPointDeliveryMode The current object (for fluent API support)
     */
    public function removeChronopostPickupPointAreaFreeshipping($chronopostPickupPointAreaFreeshipping)
    {
        if ($this->getChronopostPickupPointAreaFreeshippings()->contains($chronopostPickupPointAreaFreeshipping)) {
            $this->collChronopostPickupPointAreaFreeshippings->remove($this->collChronopostPickupPointAreaFreeshippings->search($chronopostPickupPointAreaFreeshipping));
            if (null === $this->chronopostPickupPointAreaFreeshippingsScheduledForDeletion) {
                $this->chronopostPickupPointAreaFreeshippingsScheduledForDeletion = clone $this->collChronopostPickupPointAreaFreeshippings;
                $this->chronopostPickupPointAreaFreeshippingsScheduledForDeletion->clear();
            }
            $this->chronopostPickupPointAreaFreeshippingsScheduledForDeletion[]= clone $chronopostPickupPointAreaFreeshipping;
            $chronopostPickupPointAreaFreeshipping->setChronopostPickupPointDeliveryMode(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this ChronopostPickupPointDeliveryMode is new, it will return
     * an empty collection; or if this ChronopostPickupPointDeliveryMode has previously
     * been saved, it will retrieve related ChronopostPickupPointAreaFreeshippings from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in ChronopostPickupPointDeliveryMode.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return Collection|ChildChronopostPickupPointAreaFreeshipping[] List of ChildChronopostPickupPointAreaFreeshipping objects
     */
    public function getChronopostPickupPointAreaFreeshippingsJoinArea($criteria = null, $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildChronopostPickupPointAreaFreeshippingQuery::create(null, $criteria);
        $query->joinWith('Area', $joinBehavior);

        return $this->getChronopostPickupPointAreaFreeshippings($query, $con);
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->title = null;
        $this->code = null;
        $this->freeshipping_active = null;
        $this->freeshipping_from = null;
        $this->alreadyInSave = false;
        $this->clearAllReferences();
        $this->resetModified();
        $this->setNew(true);
        $this->setDeleted(false);
    }

    /**
     * Resets all references to other model objects or collections of model objects.
     *
     * This method is a user-space workaround for PHP's inability to garbage collect
     * objects with circular references (even in PHP 5.3). This is currently necessary
     * when using Propel in certain daemon or large-volume/high-memory operations.
     *
     * @param      boolean $deep Whether to also clear the references on all referrer objects.
     */
    public function clearAllReferences($deep = false)
    {
        if ($deep) {
            if ($this->collChronopostPickupPointPrices) {
                foreach ($this->collChronopostPickupPointPrices as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collChronopostPickupPointAreaFreeshippings) {
                foreach ($this->collChronopostPickupPointAreaFreeshippings as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collChronopostPickupPointPrices = null;
        $this->collChronopostPickupPointAreaFreeshippings = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(ChronopostPickupPointDeliveryModeTableMap::DEFAULT_STRING_FORMAT);
    }

    /**
     * Code to be run before persisting the object
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preSave(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after persisting the object
     * @param ConnectionInterface $con
     */
    public function postSave(ConnectionInterface $con = null)
    {

    }

    /**
     * Code to be run before inserting to database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preInsert(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after inserting to database
     * @param ConnectionInterface $con
     */
    public function postInsert(ConnectionInterface $con = null)
    {

    }

    /**
     * Code to be run before updating the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preUpdate(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after updating the object in database
     * @param ConnectionInterface $con
     */
    public function postUpdate(ConnectionInterface $con = null)
    {

    }

    /**
     * Code to be run before deleting the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preDelete(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after deleting the object in database
     * @param ConnectionInterface $con
     */
    public function postDelete(ConnectionInterface $con = null)
    {

    }


    /**
     * Derived method to catches calls to undefined methods.
     *
     * Provides magic import/export method support (fromXML()/toXML(), fromYAML()/toYAML(), etc.).
     * Allows to define default __call() behavior if you overwrite __call()
     *
     * @param string $name
     * @param mixed  $params
     *
     * @return array|string
     */
    public function __call($name, $params)
    {
        if (0 === strpos($name, 'get')) {
            $virtualColumn = substr($name, 3);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }

            $virtualColumn = lcfirst($virtualColumn);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }
        }

        if (0 === strpos($name, 'from')) {
            $format = substr($name, 4);

            return $this->importFrom($format, reset($params));
        }

        if (0 === strpos($name, 'to')) {
            $format = substr($name, 2);
            $includeLazyLoadColumns = isset($params[0]) ? $params[0] : true;

            return $this->exportTo($format, $includeLazyLoadColumns);
        }

        throw new BadMethodCallException(sprintf('Call to undefined method: %s.', $name));
    }

}
