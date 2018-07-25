# PHP-OOP-DATABASE-CONNECTION
PHP OOP Classes for a DB connection.
The following php files contain classes that allow a Database connection using Object oriented programming.


Class BD:
The base parent class, where all the core functions are declared.

contains:
1. static function conectar() - creates conection and stores in private static $conn;
2. function getAll() - returns array of all lines of private $table table.
3. function getById($id) - returns array of line with param ID.
4. function insert($arrayToInsert) - insert into table param type array assiociative.
5. deleteById($id) - delete entry with param ID.
6. function update($id, $valores) - updates entry with param $id by values in type associative array $valores.
7. contains setters and getter for private variables.
