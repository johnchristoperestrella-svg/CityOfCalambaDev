<?php
/**
 * Database Connection Class
 */

class Database {
    private $connection;
    private $config;

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
     * Uses ? placeholders and type-safe parameter binding
     */
    public function executeInsert($table, $data) {
        $columns = array_keys($data);
        $values = array_values($data);
        $placeholders = implode(',', array_fill(0, count($columns), '?'));
        $types = $this->getTypes($values);

        $sql = "INSERT INTO {$table} (" . implode(',', $columns) . ") 
                VALUES ({$placeholders})";

        $stmt = $this->connection->prepare($sql);
        if (!$stmt) {
            throw new Exception('Prepare Error: ' . $this->connection->error);
        }

        if (!empty($values)) {
            $refs = [];
            foreach ($values as $key => &$value) {
                $refs[$key] = &$value;
            }
            array_unshift($refs, $types);
            call_user_func_array([$stmt, 'bind_param'], $refs);
        }

        if (!$stmt->execute()) {
            throw new Exception('Execute Error: ' . $stmt->error);
        }

        return $this->connection->insert_id;
    }

    /**
     * Execute UPDATE with type-safe parameters
     */
    public function executeUpdate($table, $data, $whereId) {
        $columns = array_keys($data);
        $nonNullColumns = [];
        $nonNullValues = [];
        
        // Separate NULL and non-NULL values
        foreach ($columns as $column) {
            if ($data[$column] === null) {
                // NULL values are handled in SQL directly
                continue;
            }
            $nonNullColumns[] = $column;
            $nonNullValues[] = $data[$column];
        }

        // Build SET clause with NULL values handled in SQL
        $setClauses = [];
        foreach ($columns as $column) {
            if ($data[$column] === null) {
                $setClauses[] = "$column = NULL";
            } else {
                $setClauses[] = "$column = ?";
            }
        }
        
        $nonNullValues[] = $whereId;
        $setClauseStr = implode(', ', $setClauses);
        $types = $this->getTypes($nonNullValues);

        $sql = "UPDATE {$table} SET {$setClauseStr} WHERE id = ?";
        
        $stmt = $this->connection->prepare($sql);
        if (!$stmt) {
            throw new Exception('Prepare Error: ' . $this->connection->error);
        }

        // Only bind if there are parameters
        if (!empty($nonNullValues)) {
            $params = [$types];
            foreach ($nonNullValues as &$value) {
                $params[] = &$value;
            }
            call_user_func_array([$stmt, 'bind_param'], $params);
        }

        if (!$stmt->execute()) {
            throw new Exception('Execute Error: ' . $stmt->error);
        }

        return true;
    }

    /**
     * Execute DELETE with type-safe parameters
     */
    public function executeDelete($table, $whereId) {
        $sql = "DELETE FROM {$table} WHERE id = ?";
        $stmt = $this->connection->prepare($sql);
        
        if (!$stmt) {
            throw new Exception('Prepare Error: ' . $this->connection->error);
        }

        $stmt->bind_param('i', $whereId);
        
        if (!$stmt->execute()) {
            throw new Exception('Execute Error: ' . $stmt->error);
        }

        return true;
    }

    /**
     * Find single record by ID using prepared statement
     */
    public function find($table, $id) {
        $sql = "SELECT * FROM {$table} WHERE id = ?";
        $stmt = $this->connection->prepare($sql);
        
        if (!$stmt) {
            return null;
        }

        $stmt->bind_param('i', $id);
        $stmt->execute();
        
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    /**
     * Helper: Determine type string for prepared statement
     */
    private function getTypes($values) {
        $types = "";
        foreach ($values as $value) {
            if (is_int($value)) {
                $types .= 'i';
            } elseif (is_float($value)) {
                $types .= 'd';
            } else {
                $types .= 's';
            }
        }
        return $types;
    }
}
