<?php

class BD {

    static $server = "localhost", $user = "root", $password = "", $database = "sakila";
    private $table, $idField, $fields, $showFields;
    private static $conn;

    function __construct($table, $idField, $fields = '', $showFields = '') {
        self::connect();
        $this->table = $table;
        $this->idField = $idField;
        $this->fields = $fields;
        $this->showFields = $showFields;
    }

    static function connect() {
        try {
            self::$conn = new PDO("mysql:host=" . self::$server . ";dbname=" . self::$database, self::$user, self::$password);
            self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }

    /**
     * Returns all the elements from table that meet a specified condition, if param empty, returns all entries.
     * @param array $condicion - passes the condition by collum value.
     */
    function getLines($condicion = []) {
        $getAllSql = 'Select * from ' . $this->table;
        if (!empty($condicion)) {
            $getAllSql = $getAllSql . ' where ' . join(' and ', array_map(function($v) {
                                return $v . '=:' . $v;
                            }, array_keys($condicion)));
        }
        try {
            $sql = self::$conn->prepare($getAllSql);
            $sql->execute($condicion);
            return $sql->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo "Something wrong happened: " . $e->getMessage();
        }
    }
    
    function insert($arrayToInsert) {
        $fields = implode(array_keys($arrayToInsert), ',');
        $values = implode($arrayToInsert, ',');

        try {
            $insert_cat_sql = self::$conn->prepare("insert into " . $this->table . "($fields) values('$values')");
            $insert_cat_sql->execute();
        } catch (Exception $e) {
            echo "Something wrong happened: " . $e->getMessage();
        }
    }

    function deleteById($id) {
        try {
            self::$conn->exec("delete from " . $this->table . " where " . $this->table . "_id = " . $id);
        } catch (Exception $e) {
            echo "Something wrong happened: " . $e->getMessage();
        }
    }

    /**
     * Updates an entry in the database.
     * @param int $id - id of the row to be updated
     * @param array $valores - associative array with collum names and values
     */
    function update($id, $values) {
        $fields = join(',', array_map(function($v) {
                    return $v . '=:' . $v;
                }, array_keys($values)));
        $sql = 'update ' . $this->table . ' set ' . $fields . ' where ' . $this->idField . ' = ' . $id;
        try {
            $st = self::$conn->prepare($sql);
            $st->execute($values);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

}

class Country extends BD {

    public function __construct() {
        parent::__construct('country', 'country_id');
    }

    /**
     * Insert a country in the database.
     * @param string $pais - name of the crounty we want to add.
     */
    public function insert($pais) {
        parent::insert(['country' => $pais]);
    }

}

class Actor extends BD {

    public function __construct() {
        parent::__construct('actor', 'actor_id');
    }

    /**
     * Insert an actor in the database.
     * @param string $first_name - Actor's first name
     * @param string $last_name - Actor's last name
     */
    public function insert($first_name, $last_name = '') {
        parent::insert(['first_name' => $first_name, 'last_name' => $last_name]);
    }

}
