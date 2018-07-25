# PHP-OOP-DATABASE-CONNECTION
PHP OOP Classes for a DB connection.
The following php files contain classes that allow a Database connection using Object oriented programming.


Class BD:
The base parent class, where all the core functions are declared.

contains:
1. static function connect() - creates conection and stores in private static $conn;
2. function getLine() - returns array of all lines of private $table table.
3. function insert($arrayToInsert) - insert into table param type array assiociative.
4. deleteById($id) - delete entry with param ID.
5. function update($id, $valores) - updates entry with param $id by values in type associative array $valores.
