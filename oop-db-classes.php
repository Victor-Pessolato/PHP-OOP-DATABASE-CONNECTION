<?php

class BD {

    static $servidor = "localhost", $usuario = "root", $password = "", $database = "sakila";
    private $table, $idField, $fields, $showFields;
    private static $conn;

    function __construct($tabla, $idField, $fields = '', $showFields = '') {
        self::conectar();
        $this->table = $tabla;
        $this->idField = $idField;
        $this->fields = $fields;
        $this->showFields = $showFields;
    }

    static function conectar() {
        try {
            self::$conn = new PDO("mysql:host=" . self::$servidor . ";dbname=" . self::$database, self::$usuario, self::$password);
            self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }

    function getAll($condicion = []) {
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

    /**
     * Esta funcion nos devuelve el elemento de la tabla que tenga el id que lo pasamos por parametro
     * @param int $id  - El id que buscaremos en la tabla.
     */
    function getById($id) {
        try {
            $sql = self::$conn->prepare("select * from " . $this->table . " where " . $this->table . "_id = " . $id);
            $sql->execute();
            $lineas = $sql->fetch(PDO::FETCH_ASSOC);
            return $lineas;
        } catch (Exception $e) {
            echo "Something wrong happened: " . $e->getMessage();
        }
    }

    function insert($arrayToInsert) {
        $campos = implode(array_keys($arrayToInsert), ',');
        $valores = implode($arrayToInsert, ',');

        try {
            $insert_cat_sql = self::$conn->prepare("insert into " . $this->table . "($campos) values('$valores')");
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
    function update($id, $valores) {
        $campos = join(',', array_map(function($v) {
                    return $v . '=:' . $v;
                }, array_keys($valores)));
        $sql = 'update ' . $this->table . ' set ' . $campos . ' where ' . $this->idField . ' = ' . $id;
        try {
            $st = self::$conn->prepare($sql);
            $st->execute($valores);
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
