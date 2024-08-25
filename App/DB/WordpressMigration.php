<?php 

class WordPressMigration {
    
    protected $table_name;
    protected $columns = [];

    public function __construct($table_name) {
        global $wpdb;
        $this->table_name = $wpdb->prefix . $table_name;
    }
    
    public function string($column_name, $length = 255) {
        $this->columns[$column_name] = array('type' => 'VARCHAR', 'length' => $length);
        return $this;
    }

    public function integer($column_name) {
        $this->columns[$column_name] = array('type' => 'INT');
        return $this;
    }

    public function text($column_name) {
        $this->columns[$column_name] = array('type' => 'TEXT');
        return $this;
    }

    // Add more methods for other column types as needed

    public function nullable($column_name) {
        $this->columns[$column_name]['nullable'] = true;
        return $this;
    }

    public function primary($column_name) {
        $this->columns[$column_name]['primary'] = true;
        return $this;
    }

    public function unique($column_name) {
        $this->columns[$column_name]['unique'] = true;
        return $this;
    }

    public function create() {
        global $wpdb;
        
        $sql = "CREATE TABLE IF NOT EXISTS $this->table_name (";
        $columns_sql = [];

        foreach ($this->columns as $column_name => $column) {
            $column_sql = "`$column_name` {$column['type']}";
            
            if (isset($column['length'])) {
                $column_sql .= "({$column['length']})";
            }
            
            if (isset($column['nullable']) && $column['nullable']) {
                $column_sql .= ' NULL';
            } else {
                $column_sql .= ' NOT NULL';
            }
            
            if (isset($column['primary']) && $column['primary']) {
                $column_sql .= ' PRIMARY KEY';
            }
            
            if (isset($column['unique']) && $column['unique']) {
                $column_sql .= ' UNIQUE';
            }
            
            $columns_sql[] = $column_sql;
        }

        $sql .= implode(',', $columns_sql);
        $sql .= ") ENGINE=InnoDB;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
}

// Example usage:
// $migration = new WordPress_Migration('my_custom_table');
// $migration->string('column1')->nullable()
//           ->integer('column2')->nullable()
//           ->text('column3')
//           ->primary('id')
//           ->create();
