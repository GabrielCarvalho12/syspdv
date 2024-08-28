<?php
namespace System\Database;

use PDO;
use PDOException;

/**
 * --------------------------------------------------------------------------
 * This class is used to make the connection with the database
 * --------------------------------------------------------------------------
 * @var $pdo : Object : Stores the instance of PDO
 */
class Native
{
    private static $pdo;

    /**
     * Connects to the database using PDO
     * 
     * @return PDO
     */
    public static function connect()
    {
        if (self::$pdo === null) {
            try {
                $host = getenv('DB_HOST') ?: 'localhost';
                $dbname = getenv('DB_DATABASE') ?: 'zig';
                $username = getenv('DB_USERNAME') ?: 'root';
                $password = getenv('DB_PASSWORD') ?: 'zig';

                self::$pdo = new PDO(
                    "mysql:host=$host;dbname=$dbname",
                    $username,
                    $password,
                    array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
                );

                $errorType = getenv('APP_DISPLAY_ERRORS') === 'true' ? PDO::ERRMODE_WARNING : PDO::ERRMODE_SILENT;
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, $errorType);

            } catch (PDOException $e) {
                // Handle specific PDO exceptions
                $message = 'Database configuration Error: ';
                switch ($e->getCode()) {
                    case 2002:
                        $message .= 'This localhost does not exist on this server.';
                        break;
                    case 1049:
                        $message .= 'This database does not exist on this server.';
                        break;
                    case 1044:
                        $message .= 'Database username does not exist on this server.';
                        break;
                    case 1045:
                        $message .= 'Database password is incorrect.';
                        break;
                    default:
                        $message .= 'Connection failed: ' . $e->getMessage();
                        break;
                }

                // Log the error to a file or monitoring system instead of displaying it directly
                error_log($message);

                // Display a user-friendly message
                echo "<b>Database connection error occurred. Please try again later.</b>";
                exit;
            }
        }

        return self::$pdo;
    }
}
