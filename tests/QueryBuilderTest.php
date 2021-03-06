<?php

namespace edgardmessias\unit\db\firebird;

use edgardmessias\db\firebird\Schema;
use yii\db\Query;

/**
 * @group firebird
 */
class QueryBuilderTest extends \yiiunit\framework\db\QueryBuilderTest
{

    use FirebirdTestTrait;

    public $driverName = 'firebird';

    /**
     * @throws \Exception
     * @return QueryBuilder
     */
    protected function getQueryBuilder()
    {
        switch ($this->driverName) {
            case 'firebird':
                return new \edgardmessias\db\firebird\QueryBuilder($this->getConnection(true, false));
        }
        throw new \Exception('Test is not implemented for ' . $this->driverName);
    }

    /**
     * adjust dbms specific escaping
     * @param $sql
     * @return mixed
     */
    protected function replaceQuotes($sql)
    {
        if (!in_array($this->driverName, ['mssql', 'mysql', 'sqlite'])) {
            return str_replace('`', '', $sql);
        }
        return $sql;
    }

    /**
     * this is not used as a dataprovider for testGetColumnType to speed up the test
     * when used as dataprovider every single line will cause a reconnect with the database which is not needed here
     */
    public function columnTypes()
    {
        return [
            [Schema::TYPE_PK, 'integer NOT NULL PRIMARY KEY'],
            [Schema::TYPE_PK . '(8)', 'integer NOT NULL PRIMARY KEY'],
            [Schema::TYPE_PK . ' CHECK (value > 5)', 'integer NOT NULL PRIMARY KEY CHECK (value > 5)'],
            [Schema::TYPE_PK . '(8) CHECK (value > 5)', 'integer NOT NULL PRIMARY KEY CHECK (value > 5)'],
            [Schema::TYPE_STRING, 'varchar(255)'],
            [Schema::TYPE_STRING . '(32)', 'varchar(32)'],
            [Schema::TYPE_STRING . " CHECK (value LIKE 'test%')", "varchar(255) CHECK (value LIKE 'test%')"],
            [Schema::TYPE_STRING . "(32) CHECK (value LIKE 'test%')", "varchar(32) CHECK (value LIKE 'test%')"],
            [Schema::TYPE_STRING . ' NOT NULL', 'varchar(255) NOT NULL'],
            [Schema::TYPE_TEXT, 'blob sub_type text'],
            [Schema::TYPE_TEXT . '(255)', 'blob sub_type text'],
            [Schema::TYPE_TEXT . " CHECK (value LIKE 'test%')", "blob sub_type text CHECK (value LIKE 'test%')"],
            [Schema::TYPE_TEXT . "(255) CHECK (value LIKE 'test%')", "blob sub_type text CHECK (value LIKE 'test%')"],
            [Schema::TYPE_TEXT . ' NOT NULL', 'blob sub_type text NOT NULL'],
            [Schema::TYPE_TEXT . '(255) NOT NULL', 'blob sub_type text NOT NULL'],
            [Schema::TYPE_SMALLINT, 'smallint'],
            [Schema::TYPE_SMALLINT . '(8)', 'smallint'],
            [Schema::TYPE_INTEGER, 'integer'],
            [Schema::TYPE_INTEGER . '(8)', 'integer'],
            [Schema::TYPE_INTEGER . ' CHECK (value > 5)', 'integer CHECK (value > 5)'],
            [Schema::TYPE_INTEGER . '(8) CHECK (value > 5)', 'integer CHECK (value > 5)'],
            [Schema::TYPE_INTEGER . ' NOT NULL', 'integer NOT NULL'],
            [Schema::TYPE_BIGINT, 'bigint'],
            [Schema::TYPE_BIGINT . '(8)', 'bigint'],
            [Schema::TYPE_BIGINT . ' CHECK (value > 5)', 'bigint CHECK (value > 5)'],
            [Schema::TYPE_BIGINT . '(8) CHECK (value > 5)', 'bigint CHECK (value > 5)'],
            [Schema::TYPE_BIGINT . ' NOT NULL', 'bigint NOT NULL'],
            [Schema::TYPE_FLOAT, 'float'],
            [Schema::TYPE_FLOAT . '(16,5)', 'float'],
            [Schema::TYPE_FLOAT . ' CHECK (value > 5.6)', 'float CHECK (value > 5.6)'],
            [Schema::TYPE_FLOAT . '(16,5) CHECK (value > 5.6)', 'float CHECK (value > 5.6)'],
            [Schema::TYPE_FLOAT . ' NOT NULL', 'float NOT NULL'],
            [Schema::TYPE_DOUBLE, 'double precision'],
            [Schema::TYPE_DOUBLE . '(16,5)', 'double precision'],
            [Schema::TYPE_DOUBLE . ' CHECK (value > 5.6)', 'double precision CHECK (value > 5.6)'],
            [Schema::TYPE_DOUBLE . '(16,5) CHECK (value > 5.6)', 'double precision CHECK (value > 5.6)'],
            [Schema::TYPE_DOUBLE . ' NOT NULL', 'double precision NOT NULL'],
            [Schema::TYPE_DECIMAL, 'numeric(10,0)'],
            [Schema::TYPE_DECIMAL . '(12,4)', 'numeric(12,4)'],
            [Schema::TYPE_DECIMAL . ' CHECK (value > 5.6)', 'numeric(10,0) CHECK (value > 5.6)'],
            [Schema::TYPE_DECIMAL . '(12,4) CHECK (value > 5.6)', 'numeric(12,4) CHECK (value > 5.6)'],
            [Schema::TYPE_DECIMAL . ' NOT NULL', 'numeric(10,0) NOT NULL'],
            [Schema::TYPE_DATETIME, 'timestamp'],
            [Schema::TYPE_DATETIME . " CHECK (value BETWEEN '2011-01-01' AND '2013-01-01')", "timestamp CHECK (value BETWEEN '2011-01-01' AND '2013-01-01')"],
            [Schema::TYPE_DATETIME . ' NOT NULL', 'timestamp NOT NULL'],
            [Schema::TYPE_TIMESTAMP, 'timestamp'],
            [Schema::TYPE_TIMESTAMP . " CHECK (value BETWEEN '2011-01-01' AND '2013-01-01')", "timestamp CHECK (value BETWEEN '2011-01-01' AND '2013-01-01')"],
            [Schema::TYPE_TIMESTAMP . ' NOT NULL', 'timestamp NOT NULL'],
            [Schema::TYPE_TIME, 'time'],
            [Schema::TYPE_TIME . " CHECK (value BETWEEN '12:00:00' AND '13:01:01')", "time CHECK (value BETWEEN '12:00:00' AND '13:01:01')"],
            [Schema::TYPE_TIME . ' NOT NULL', 'time NOT NULL'],
            [Schema::TYPE_DATE, 'date'],
            [Schema::TYPE_DATE . " CHECK (value BETWEEN '2011-01-01' AND '2013-01-01')", "date CHECK (value BETWEEN '2011-01-01' AND '2013-01-01')"],
            [Schema::TYPE_DATE . ' NOT NULL', 'date NOT NULL'],
            [Schema::TYPE_BINARY, 'blob'],
            [Schema::TYPE_BOOLEAN, 'smallint'],
            [Schema::TYPE_BOOLEAN . ' DEFAULT 1 NOT NULL', 'smallint DEFAULT 1 NOT NULL'],
            [Schema::TYPE_MONEY, 'numeric(18,4)'],
            [Schema::TYPE_MONEY . '(16,2)', 'numeric(16,2)'],
            [Schema::TYPE_MONEY . ' CHECK (value > 0.0)', 'numeric(18,4) CHECK (value > 0.0)'],
            [Schema::TYPE_MONEY . '(16,2) CHECK (value > 0.0)', 'numeric(16,2) CHECK (value > 0.0)'],
            [Schema::TYPE_MONEY . ' NOT NULL', 'numeric(18,4) NOT NULL'],
        ];
    }

    public function conditionProvider()
    {
        $conditions = parent::conditionProvider();

        $conditions[46] = [ ['=', 'date', (new Query())->select('max(date)')->from('test')->where(['id' => 5])], 'date = (SELECT max(date) AS max_date FROM test WHERE id=:qp0)', [':qp0' => 5] ];
        $conditions[49] = [ ['in', ['id', 'name'], [['id' => 1, 'name' => 'foo'], ['id' => 2, 'name' => 'bar']]], '((id = :qp0 AND name = :qp1) OR (id = :qp2 AND name = :qp3))', [':qp0' => 1, ':qp1' => 'foo', ':qp2' => 2, ':qp3' => 'bar']];
        $conditions[50] = [ ['not in', ['id', 'name'], [['id' => 1, 'name' => 'foo'], ['id' => 2, 'name' => 'bar']]], '((id != :qp0 OR name != :qp1) AND (id != :qp2 OR name != :qp3))', [':qp0' => 1, ':qp1' => 'foo', ':qp2' => 2, ':qp3' => 'bar']];
        
        return $conditions;
    }
    
    public function testSelectSubquery()
    {
        $subquery = (new Query())
            ->select('COUNT(*)')
            ->from('operations')
            ->where('account_id = accounts.id');
        $query = (new Query())
            ->select('*')
            ->from('accounts')
            ->addSelect(['operations_count' => $subquery]);
        list ($sql, $params) = $this->getQueryBuilder()->build($query);
        $expected = $this->replaceQuotes('SELECT *, (SELECT COUNT(*) AS COUNT_ALL FROM `operations` WHERE account_id = accounts.id) AS `operations_count` FROM `accounts`');
        $this->assertEquals($expected, $sql);
        $this->assertEmpty($params);
    }
        
    public function testRenameTable()
    {
        $this->setExpectedException('\yii\base\NotSupportedException');

        $qb = $this->getQueryBuilder();
        $qb->renameTable('null_values', 'null_values2');
    }
    
    public function testTruncateTable()
    {
        $countBefore = (new Query())->from('animal')->count('*', $this->getConnection(false));
        $this->assertEquals(2, $countBefore);

        $qb = $this->getQueryBuilder();
        
        $sqlTruncate = $qb->truncateTable('animal');
        $this->assertEquals('DELETE FROM animal', $sqlTruncate);
        
        $this->getConnection(false)->createCommand($sqlTruncate)->execute();
        $countAfter = (new Query())->from('animal')->count('*', $this->getConnection(false));
        $this->assertEquals(0, $countAfter);
    }
    
    public function testDropColumn()
    {
        $connection = $this->getConnection(true);
        $qb = $this->getQueryBuilder();
        
        $columns = $connection->getTableSchema('type', true)->columnNames;
        array_shift($columns); //Prevent to remove all columns
        
        foreach ($columns as $column) {
            $connection->createCommand($qb->dropColumn('type', $column))->execute();
        }
        
        $schema = $connection->getTableSchema('type', true);
        foreach ($columns as $column) {
            $this->assertNotContains($column, $schema->columnNames);
        }
    }
    
    public function testRenameColumn()
    {
        $connection = $this->getConnection(true);
        $qb = $this->getQueryBuilder();
        
        $columns = $connection->getTableSchema('type', true)->columnNames;
        
        foreach ($columns as $column) {
            $connection->createCommand($qb->renameColumn('type', $column, $column.'_new'))->execute();
        }
        
        $schema = $connection->getTableSchema('type', true);
        foreach ($columns as $column) {
            $this->assertNotContains($column, $schema->columnNames);
            $this->assertContains($column.'_new', $schema->columnNames);
        }
    }
    
    public function testAlterColumn()
    {
        $connection = $this->getConnection(true);
        $qb = $this->getQueryBuilder();
        
        $connection->createCommand($qb->alterColumn('customer', 'email', Schema::TYPE_STRING . '(128) NULL'))->execute();
        $connection->createCommand($qb->alterColumn('customer', 'name', "SET DEFAULT 'NO NAME'"))->execute();
        $connection->createCommand($qb->alterColumn('customer', 'name', Schema::TYPE_STRING . "(128) NOT NULL"))->execute();
        $connection->createCommand($qb->alterColumn('customer', 'profile_id', Schema::TYPE_INTEGER . ' NOT NULL'))->execute();

        $newColumns = $connection->getTableSchema('customer', true)->columns;
        
        $this->assertEquals(true, $newColumns['email']->allowNull);
        $this->assertEquals(false, $newColumns['name']->allowNull);
        $this->assertEquals('NO NAME', $newColumns['name']->defaultValue);
        $this->assertEquals(false, $newColumns['profile_id']->allowNull);
        $this->assertEquals(0, $newColumns['profile_id']->defaultValue);
    }
        
    public function testDropIndex()
    {
        $connection = $this->getConnection(true);
        $qb = $this->getQueryBuilder();
        
        $this->assertEquals('DROP INDEX idx_int_col', $qb->dropIndex('idx_int_col', 'type'));
        
        $columns = $connection->getTableSchema('type', true)->columnNames;
        
        foreach ($columns as $column) {
            $result = $connection->createCommand($qb->createIndex('idx_' .$column, 'type', $column))->execute();
        }
        
        foreach ($columns as $column) {
            $result = $connection->createCommand($qb->dropIndex('idx_' .$column, 'type'))->execute();
        }
    }
    
    public function testResetSequence()
    {
        $connection = $this->getConnection(true);
        $qb = $this->getQueryBuilder();
        
        $this->assertEquals('ALTER SEQUENCE seq_animal_id RESTART WITH 3', $qb->resetSequence('animal'));
        $this->assertEquals('ALTER SEQUENCE seq_animal_id RESTART WITH 10', $qb->resetSequence('animal', 10));
        
        $this->assertEquals('ALTER SEQUENCE gen_profile_id RESTART WITH 3', $qb->resetSequence('profile'));
        $this->assertEquals('ALTER SEQUENCE gen_profile_id RESTART WITH 10', $qb->resetSequence('profile', 10));
        
        $this->assertEquals(2, (new Query())->from('profile')->max('id', $connection));
        
        $connection->createCommand()->insert('profile', ['description' => 'profile customer 3'])->execute();
        $this->assertEquals(3, (new Query())->from('profile')->max('id', $connection));
        
        $connection->createCommand($qb->resetSequence('profile'))->execute();
        $connection->createCommand()->insert('profile', ['description' => 'profile customer 4'])->execute();
        $this->assertEquals(4, (new Query())->from('profile')->max('id', $connection));
        
        $connection->createCommand($qb->resetSequence('profile', 10))->execute();
        $connection->createCommand()->insert('profile', ['description' => 'profile customer 11'])->execute();
        $this->assertEquals(11, (new Query())->from('profile')->max('id', $connection));
    }
    
    public function testCreateTableWithAutoIncrement()
    {
        $qb = $this->getQueryBuilder();
        if ($qb->db->getTableSchema('autoincrement_table', true) !== null) {
            $this->getConnection(false)->createCommand($qb->dropTable('autoincrement_table'))->execute();
        }
        $columns = [
            'id' => Schema::TYPE_PK,
            'description' => Schema::TYPE_STRING,
        ];
        $this->getConnection(false)->createCommand($qb->createTable('autoincrement_table', $columns))->execute();
        $qb->db->getTableSchema('autoincrement_table', true); //Force update schema
        
        $this->assertEquals(1, $this->getConnection(false)->getSchema()->insert('autoincrement_table', ['description' => 'auto increment 1'])['id']);
        $this->assertEquals(2, $this->getConnection(false)->getSchema()->insert('autoincrement_table', ['description' => 'auto increment 2'])['id']);
        $this->assertEquals(3, $this->getConnection(false)->getSchema()->insert('autoincrement_table', ['description' => 'auto increment 3'])['id']);
        $this->assertEquals(4, $this->getConnection(false)->getSchema()->insert('autoincrement_table', ['description' => 'auto increment 4'])['id']);
        
        $this->assertEquals(4, (new Query())->from('autoincrement_table')->max('id', $this->getConnection(false)));
        
        //Drop and recreate, for test sequences
        $this->getConnection(false)->createCommand($qb->dropTable('autoincrement_table'))->execute();
        $this->getConnection(false)->createCommand($qb->createTable('autoincrement_table', $columns))->execute();
        
        $this->assertEquals(1, $this->getConnection(false)->getSchema()->insert('autoincrement_table', ['description' => 'auto increment 1'])['id']);
        $this->assertEquals(2, $this->getConnection(false)->getSchema()->insert('autoincrement_table', ['description' => 'auto increment 2'])['id']);
        $this->assertEquals(3, $this->getConnection(false)->getSchema()->insert('autoincrement_table', ['description' => 'auto increment 3'])['id']);
        $this->assertEquals(4, $this->getConnection(false)->getSchema()->insert('autoincrement_table', ['description' => 'auto increment 4'])['id']);
        
        $this->assertEquals(4, (new Query())->from('autoincrement_table')->max('id', $this->getConnection(false)));
    }
}
