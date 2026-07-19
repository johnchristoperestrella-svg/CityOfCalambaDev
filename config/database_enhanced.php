<?php
/**
 * Enhanced Database Connection Class with Prepared Statements
 * Supports both legacy and secure query patterns
 */

class Database {
    private $connection;
    private $config;
    private $lastStmt = null;

    public function __construct() {
        $this->config = [
            'driver' => env('DB_CONNECTION', 'mysql'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', 3306),
            'database' => env('DB_DATABASE', 'calamba_popdev'),
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
        ];
        $this->connect();
    }

    public function connect() {
        try {
            $this->connection = new mysqli(
                $this->config['host'],
                $this->config['username'],
                $this->config['password'],
                $this->config['database']
            );

            if ($this->connection->connect_error) {
                throw new Exception('Connection failed: ' . $this->connection->connect_error);
            }

            $this->connection->set_charset($this->config['charset']);
        } catch (Exception $e) {
            die('Database Connection Error: ' . $e->getMessage());
        }
    }

    // ========== LEGACY METHODS (for backward compatibility) ==========
    
    public function query($sql) {
        $result = $this->connection->query($sql);
        if (!$result && $this->connection->error) {
            throw new Exception('Query Error: ' . $this->connection->error);
        }
        return $result;
    }

    public function prepare($sql) {
        return $this->connection->prepare($sql);
    }

    public function escape($value) {
        return $this->connection->real_escape_string($value);
    }

    public function lastInsertId() {
        return $this->connection->insert_id;
    }

    public function affectedRows() {
        return $this->connection->affected_rows;
    }

    public function close() {
        if ($this->connection) {
            $this->connection->close();
        }
    }

    // ========== NEW SECURE METHODS (Prepared Statements) ==========

    /**
     * Execute a prepared statement with parameters
     * 
     * @param string $sql SQL query with ? placeholders
     * @param string $types Type string (s=string, i=integer, d=double, b=blob)
     * @param array $params Array of parameter values
     * @return mysqli_result|bool Query result or boolean for INSERT/UPDATE/DELETE
     * 
     * EXAMPLE:
     *   $db->execute(
     *     "SELECT * FROM users WHERE email = ? AND status = ?",
     *     "ss",
     *     ["user@example.com", "active"]
     *   )
     */
    public function execute($sql, $types, $params = []) {
        try {
            $stmt = $this->connection->prepare($sql);
            
            if (!$stmt) {
                throw new Exception('Prepare Error: ' . $this->connection->error);
            }

            // Only bind params if there are any
            if (!empty($params)) {
                // Build reference array for bind_param (required by PHP)
                $refs = [];
                foreach ($params as $key => &$value) {
                    $refs[$key] = &$value;
                }

                // Call bind_param with unpacked parameters
                array_unshift($refs, $types);
                call_user_func_array([$stmt, 'bind_param'], $refs);
            }

            if (!$stmt->execute()) {
                throw new Exception('Execute Error: ' . $stmt->error);
            }

            $this->lastStmt = $stmt;
            return $stmt;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Execute INSERT and return last insert ID
     * 
     * @param string $table Table name
     * @param array $data Associative array of column => value
     * @return int|bool Last insert ID or false on failure
     * 
     * EXAMPLE:
     *   $db->executeInsert('users', [
     *     'email' => 'test@example.com',
     *     'name' => 'Test User',
     *     'age' => 25
     *   ])
     */
    public function executeInsert($table, $data) {
        $columns = array_keys($data);
        $values = array_values($data);
        $placeholders = implode(',', array_fill(0, count($columns), '?'));
        $types = $this->getTypes($values);

        $sql = "INSERT INTO {$table} (" . implode(',', $columns) . ") 
                VALUES ({$placeholders})";

        $stmt = $this->execute($sql, $types, $values);
        return $stmt ? $this->connection->insert_id : false;
    }

    /**
     * Execute UPDATE with WHERE condition
     * 
     * @param string $table Table name
     * @param array $data Columns to update (column => value)
     * @param int $whereId ID condition (WHERE id = ?)
     * @return bool Success/failure
     * 
     * EXAMPLE:
     *   $db->executeUpdate('users', ['name' => 'New Name', 'age' => 30], 5)
     */
    public function executeUpdate($table, $data, $whereId) {
        $columns = array_keys($data);
        $values = array_values($data);
        $values[] = $whereId; // Add ID for WHERE clause

        $setClause = implode(' = ?, ', $columns) . ' = ?';
        $types = $this->getTypes($values);

        $sql = "UPDATE {$table} SET {$setClause} WHERE id = ?";
        
        $stmt = $this->execute($sql, $types, $values);
        return $stmt ? true : false;
    }

    /**
     * Execute DELETE with WHERE condition
     * 
     * @param string $table Table name
     * @param int $whereId ID condition (WHERE id = ?)
     * @return bool Success/failure
     * 
     * EXAMPLE:
     *   $db->executeDelete('users', 5)
     */
    public function executeDelete($table, $whereId) {
        $sql = "DELETE FROM {$table} WHERE id = ?";
        $stmt = $this->execute($sql, "i", [$whereId]);
        return $stmt ? true : false;
    }

    /**
     * Find single record by ID
     * 
     * @param string $table Table name
     * @param int $id Record ID
     * @return array|null Record as associative array or null
     * 
     * EXAMPLE:
     *   $user = $db->find('users', 5)
     */
    public function find($table, $id) {
        $sql = "SELECT * FROM {$table} WHERE id = ?";
        $stmt = $this->execute($sql, "i", [$id]);
        
        if (!$stmt) {
            return null;
        }
        
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    /**
     * Find all records with optional WHERE conditions
     * 
     * @param string $table Table name
     * @param array $where Conditions (optional): [['column', 'operator', 'value'], ...]
     * @param array $orderBy Columns to order by (optional): ['created_at' => 'DESC', ...]
     * @param int $limit Limit results (optional)
     * @return array Results as associative array
     * 
     * EXAMPLE:
     *   $users = $db->findAll('users', 
     *     [['status', '=', 'active']],
     *     ['created_at' => 'DESC'],
     *     50
     *   )
     */
    public function findAll($table, $where = [], $orderBy = [], $limit = null) {
        $sql = "SELECT * FROM {$table}";

        // Build WHERE clause
        if (!empty($where)) {
            $conditions = [];
            $params = [];
            $types = "";

            foreach ($where as $condition) {
                list($column, $operator, $value) = $condition;
                $conditions[] = "{$column} {$operator} ?";
                $params[] = $value;
                $types .= $this->getType($value);
            }

            $sql .= " WHERE " . implode(" AND ", $conditions);
        }

        // Build ORDER BY clause
        if (!empty($orderBy)) {
            $orderClauses = [];
            foreach ($orderBy as $column => $direction) {
                $orderClauses[] = "{$column} {$direction}";
            }
            $sql .= " ORDER BY " . implode(", ", $orderClauses);
        }

        // Add LIMIT
        if ($limit) {
            $sql .= " LIMIT {$limit}";
        }

        if (!empty($where)) {
            $stmt = $this->execute($sql, $types, $params);
            $result = $stmt->get_result();
        } else {
            $result = $this->connection->query($sql);
        }

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Get the result from the last executed statement
     * 
     * @return mysqli_result|null
     */
    public function getResult() {
        return $this->lastStmt ? $this->lastStmt->get_result() : null;
    }

    /**
     * Count rows matching conditions
     * 
     * @param string $table Table name
     * @param array $where Conditions (optional)
     * @return int Record count
     */
    public function count($table, $where = []) {
        $sql = "SELECT COUNT(*) as cnt FROM {$table}";
        $params = [];
        $types = "";

        if (!empty($where)) {
            $conditions = [];
            foreach ($where as $condition) {
                list($column, $operator, $value) = $condition;
                $conditions[] = "{$column} {$operator} ?";
                $params[] = $value;
                $types .= $this->getType($value);
            }
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }

        if (!empty($params)) {
            $stmt = $this->execute($sql, $types, $params);
            $result = $stmt->get_result();
        } else {
            $result = $this->connection->query($sql);
        }

        $row = $result->fetch_assoc();
        return (int)$row['cnt'];
    }

    // ========== HELPER METHODS ==========

    /**
     * Determine type character(s) for values
     * Returns string like "ssi" for (string, string, integer)
     */
    private function getTypes($values) {
        $types = "";
        foreach ($values as $value) {
            $types .= $this->getType($value);
        }
        return $types;
    }

    /**
     * Determine type character for a single value
     * s = string, i = integer, d = double, b = blob
     */
    private function getType($value) {
        if (is_int($value)) {
            return 'i';
        } elseif (is_float($value)) {
            return 'd';
        } elseif (is_string($value)) {
            return 's';
        }
        return 's'; // Default to string
    }

    /**
     * Get connection object (use carefully)
     */
    public function getConnection() {
        return $this->connection;
    }

    /**
     * Get error message from last operation
     */
    public function getError() {
        return $this->lastStmt ? $this->lastStmt->error : $this->connection->error;
    }
}
