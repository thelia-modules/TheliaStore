<?php

namespace TheliaStore\Model\Base;

use \Exception;
use \PDO;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;
use TheliaStore\Model\StoreExtension as ChildStoreExtension;
use TheliaStore\Model\StoreExtensionQuery as ChildStoreExtensionQuery;
use TheliaStore\Model\Map\StoreExtensionTableMap;

/**
 * Base class that represents a query for the 'store_extension' table.
 *
 * 
 *
 * @method     ChildStoreExtensionQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildStoreExtensionQuery orderByCode($order = Criteria::ASC) Order by the code column
 * @method     ChildStoreExtensionQuery orderByExtensionId($order = Criteria::ASC) Order by the extension_id column
 * @method     ChildStoreExtensionQuery orderByProductExtensionId($order = Criteria::ASC) Order by the product_extension_id column
 * @method     ChildStoreExtensionQuery orderByExtensionName($order = Criteria::ASC) Order by the extension_name column
 * @method     ChildStoreExtensionQuery orderByToken($order = Criteria::ASC) Order by the token column
 * @method     ChildStoreExtensionQuery orderByInstallationState($order = Criteria::ASC) Order by the installation_state column
 * @method     ChildStoreExtensionQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     ChildStoreExtensionQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method     ChildStoreExtensionQuery groupById() Group by the id column
 * @method     ChildStoreExtensionQuery groupByCode() Group by the code column
 * @method     ChildStoreExtensionQuery groupByExtensionId() Group by the extension_id column
 * @method     ChildStoreExtensionQuery groupByProductExtensionId() Group by the product_extension_id column
 * @method     ChildStoreExtensionQuery groupByExtensionName() Group by the extension_name column
 * @method     ChildStoreExtensionQuery groupByToken() Group by the token column
 * @method     ChildStoreExtensionQuery groupByInstallationState() Group by the installation_state column
 * @method     ChildStoreExtensionQuery groupByCreatedAt() Group by the created_at column
 * @method     ChildStoreExtensionQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method     ChildStoreExtensionQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildStoreExtensionQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildStoreExtensionQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildStoreExtension findOne(ConnectionInterface $con = null) Return the first ChildStoreExtension matching the query
 * @method     ChildStoreExtension findOneOrCreate(ConnectionInterface $con = null) Return the first ChildStoreExtension matching the query, or a new ChildStoreExtension object populated from the query conditions when no match is found
 *
 * @method     ChildStoreExtension findOneById(int $id) Return the first ChildStoreExtension filtered by the id column
 * @method     ChildStoreExtension findOneByCode(string $code) Return the first ChildStoreExtension filtered by the code column
 * @method     ChildStoreExtension findOneByExtensionId(int $extension_id) Return the first ChildStoreExtension filtered by the extension_id column
 * @method     ChildStoreExtension findOneByProductExtensionId(int $product_extension_id) Return the first ChildStoreExtension filtered by the product_extension_id column
 * @method     ChildStoreExtension findOneByExtensionName(string $extension_name) Return the first ChildStoreExtension filtered by the extension_name column
 * @method     ChildStoreExtension findOneByToken(string $token) Return the first ChildStoreExtension filtered by the token column
 * @method     ChildStoreExtension findOneByInstallationState(int $installation_state) Return the first ChildStoreExtension filtered by the installation_state column
 * @method     ChildStoreExtension findOneByCreatedAt(string $created_at) Return the first ChildStoreExtension filtered by the created_at column
 * @method     ChildStoreExtension findOneByUpdatedAt(string $updated_at) Return the first ChildStoreExtension filtered by the updated_at column
 *
 * @method     array findById(int $id) Return ChildStoreExtension objects filtered by the id column
 * @method     array findByCode(string $code) Return ChildStoreExtension objects filtered by the code column
 * @method     array findByExtensionId(int $extension_id) Return ChildStoreExtension objects filtered by the extension_id column
 * @method     array findByProductExtensionId(int $product_extension_id) Return ChildStoreExtension objects filtered by the product_extension_id column
 * @method     array findByExtensionName(string $extension_name) Return ChildStoreExtension objects filtered by the extension_name column
 * @method     array findByToken(string $token) Return ChildStoreExtension objects filtered by the token column
 * @method     array findByInstallationState(int $installation_state) Return ChildStoreExtension objects filtered by the installation_state column
 * @method     array findByCreatedAt(string $created_at) Return ChildStoreExtension objects filtered by the created_at column
 * @method     array findByUpdatedAt(string $updated_at) Return ChildStoreExtension objects filtered by the updated_at column
 *
 */
abstract class StoreExtensionQuery extends ModelCriteria
{
    
    /**
     * Initializes internal state of \TheliaStore\Model\Base\StoreExtensionQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'thelia', $modelName = '\\TheliaStore\\Model\\StoreExtension', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildStoreExtensionQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildStoreExtensionQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof \TheliaStore\Model\StoreExtensionQuery) {
            return $criteria;
        }
        $query = new \TheliaStore\Model\StoreExtensionQuery();
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
     * @return ChildStoreExtension|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = StoreExtensionTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(StoreExtensionTableMap::DATABASE_NAME);
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
     * @return   ChildStoreExtension A model object, or null if the key is not found
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT ID, CODE, EXTENSION_ID, PRODUCT_EXTENSION_ID, EXTENSION_NAME, TOKEN, INSTALLATION_STATE, CREATED_AT, UPDATED_AT FROM store_extension WHERE ID = :p0';
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
            $obj = new ChildStoreExtension();
            $obj->hydrate($row);
            StoreExtensionTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildStoreExtension|array|mixed the result, formatted by the current formatter
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
     * @return ChildStoreExtensionQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(StoreExtensionTableMap::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return ChildStoreExtensionQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(StoreExtensionTableMap::ID, $keys, Criteria::IN);
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
     * @return ChildStoreExtensionQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(StoreExtensionTableMap::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(StoreExtensionTableMap::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StoreExtensionTableMap::ID, $id, $comparison);
    }

    /**
     * Filter the query on the code column
     *
     * Example usage:
     * <code>
     * $query->filterByCode('fooValue');   // WHERE code = 'fooValue'
     * $query->filterByCode('%fooValue%'); // WHERE code LIKE '%fooValue%'
     * </code>
     *
     * @param     string $code The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildStoreExtensionQuery The current query, for fluid interface
     */
    public function filterByCode($code = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($code)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $code)) {
                $code = str_replace('*', '%', $code);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(StoreExtensionTableMap::CODE, $code, $comparison);
    }

    /**
     * Filter the query on the extension_id column
     *
     * Example usage:
     * <code>
     * $query->filterByExtensionId(1234); // WHERE extension_id = 1234
     * $query->filterByExtensionId(array(12, 34)); // WHERE extension_id IN (12, 34)
     * $query->filterByExtensionId(array('min' => 12)); // WHERE extension_id > 12
     * </code>
     *
     * @param     mixed $extensionId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildStoreExtensionQuery The current query, for fluid interface
     */
    public function filterByExtensionId($extensionId = null, $comparison = null)
    {
        if (is_array($extensionId)) {
            $useMinMax = false;
            if (isset($extensionId['min'])) {
                $this->addUsingAlias(StoreExtensionTableMap::EXTENSION_ID, $extensionId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($extensionId['max'])) {
                $this->addUsingAlias(StoreExtensionTableMap::EXTENSION_ID, $extensionId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StoreExtensionTableMap::EXTENSION_ID, $extensionId, $comparison);
    }

    /**
     * Filter the query on the product_extension_id column
     *
     * Example usage:
     * <code>
     * $query->filterByProductExtensionId(1234); // WHERE product_extension_id = 1234
     * $query->filterByProductExtensionId(array(12, 34)); // WHERE product_extension_id IN (12, 34)
     * $query->filterByProductExtensionId(array('min' => 12)); // WHERE product_extension_id > 12
     * </code>
     *
     * @param     mixed $productExtensionId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildStoreExtensionQuery The current query, for fluid interface
     */
    public function filterByProductExtensionId($productExtensionId = null, $comparison = null)
    {
        if (is_array($productExtensionId)) {
            $useMinMax = false;
            if (isset($productExtensionId['min'])) {
                $this->addUsingAlias(StoreExtensionTableMap::PRODUCT_EXTENSION_ID, $productExtensionId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($productExtensionId['max'])) {
                $this->addUsingAlias(StoreExtensionTableMap::PRODUCT_EXTENSION_ID, $productExtensionId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StoreExtensionTableMap::PRODUCT_EXTENSION_ID, $productExtensionId, $comparison);
    }

    /**
     * Filter the query on the extension_name column
     *
     * Example usage:
     * <code>
     * $query->filterByExtensionName('fooValue');   // WHERE extension_name = 'fooValue'
     * $query->filterByExtensionName('%fooValue%'); // WHERE extension_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $extensionName The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildStoreExtensionQuery The current query, for fluid interface
     */
    public function filterByExtensionName($extensionName = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($extensionName)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $extensionName)) {
                $extensionName = str_replace('*', '%', $extensionName);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(StoreExtensionTableMap::EXTENSION_NAME, $extensionName, $comparison);
    }

    /**
     * Filter the query on the token column
     *
     * Example usage:
     * <code>
     * $query->filterByToken('fooValue');   // WHERE token = 'fooValue'
     * $query->filterByToken('%fooValue%'); // WHERE token LIKE '%fooValue%'
     * </code>
     *
     * @param     string $token The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildStoreExtensionQuery The current query, for fluid interface
     */
    public function filterByToken($token = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($token)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $token)) {
                $token = str_replace('*', '%', $token);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(StoreExtensionTableMap::TOKEN, $token, $comparison);
    }

    /**
     * Filter the query on the installation_state column
     *
     * Example usage:
     * <code>
     * $query->filterByInstallationState(1234); // WHERE installation_state = 1234
     * $query->filterByInstallationState(array(12, 34)); // WHERE installation_state IN (12, 34)
     * $query->filterByInstallationState(array('min' => 12)); // WHERE installation_state > 12
     * </code>
     *
     * @param     mixed $installationState The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildStoreExtensionQuery The current query, for fluid interface
     */
    public function filterByInstallationState($installationState = null, $comparison = null)
    {
        if (is_array($installationState)) {
            $useMinMax = false;
            if (isset($installationState['min'])) {
                $this->addUsingAlias(StoreExtensionTableMap::INSTALLATION_STATE, $installationState['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($installationState['max'])) {
                $this->addUsingAlias(StoreExtensionTableMap::INSTALLATION_STATE, $installationState['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StoreExtensionTableMap::INSTALLATION_STATE, $installationState, $comparison);
    }

    /**
     * Filter the query on the created_at column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE created_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $createdAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildStoreExtensionQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(StoreExtensionTableMap::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(StoreExtensionTableMap::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StoreExtensionTableMap::CREATED_AT, $createdAt, $comparison);
    }

    /**
     * Filter the query on the updated_at column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE updated_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $updatedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildStoreExtensionQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(StoreExtensionTableMap::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(StoreExtensionTableMap::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(StoreExtensionTableMap::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   ChildStoreExtension $storeExtension Object to remove from the list of results
     *
     * @return ChildStoreExtensionQuery The current query, for fluid interface
     */
    public function prune($storeExtension = null)
    {
        if ($storeExtension) {
            $this->addUsingAlias(StoreExtensionTableMap::ID, $storeExtension->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the store_extension table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(StoreExtensionTableMap::DATABASE_NAME);
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
            StoreExtensionTableMap::clearInstancePool();
            StoreExtensionTableMap::clearRelatedInstancePool();

            $con->commit();
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }

        return $affectedRows;
    }

    /**
     * Performs a DELETE on the database, given a ChildStoreExtension or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or ChildStoreExtension object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(StoreExtensionTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(StoreExtensionTableMap::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();
            

        StoreExtensionTableMap::removeInstanceFromPool($criteria);
        
            $affectedRows += ModelCriteria::delete($con);
            StoreExtensionTableMap::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }
    }

    // timestampable behavior
    
    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     ChildStoreExtensionQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(StoreExtensionTableMap::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }
    
    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     ChildStoreExtensionQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(StoreExtensionTableMap::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }
    
    /**
     * Order by update date desc
     *
     * @return     ChildStoreExtensionQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(StoreExtensionTableMap::UPDATED_AT);
    }
    
    /**
     * Order by update date asc
     *
     * @return     ChildStoreExtensionQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(StoreExtensionTableMap::UPDATED_AT);
    }
    
    /**
     * Order by create date desc
     *
     * @return     ChildStoreExtensionQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(StoreExtensionTableMap::CREATED_AT);
    }
    
    /**
     * Order by create date asc
     *
     * @return     ChildStoreExtensionQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(StoreExtensionTableMap::CREATED_AT);
    }

} // StoreExtensionQuery
