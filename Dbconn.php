<?php
require_once 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

class Dbconn
{
    protected $db;

    public function __construct()
    {
        $db_host = $_ENV['DB_HOST'];
        $db_name = $_ENV['DB_NAME'];
        $db_user = $_ENV['DB_USER'];
        $db_pass = $_ENV['DB_PASS'];
        $options = [PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];

        try {
            $this->db = new PDO("mysql:host=$db_host; dbname=$db_name; charset=utf8", $db_user, $db_pass, $options);
        } catch (Exception $ex) {
            echo $ex->getMessage();
            die;
        }
    }


    /**
     * 
     * @param String $name
     * @param String $email
     * @param String $phone
     * @param String $from
     * @param String $to_see
     * @param String $img_url
     * @return boolean
     */
    public function insertNewVisitor($name, $email, $phone, $from, $to_see, $img_url)
    {
        //create an instance of the class that communicates with db
        $query = $this->db->prepare("INSERT INTO visitors (name, email, phone, img_url, where_from, to_see, check_in_time, check_out_time) 
            VALUES (:name, :email, :phone, :img_url, :from, :to_see, NOW(), NULL)");

        $inserted = $query->execute([
            ':name' => $name,
            ':email' => $email,
            ':phone' => $phone,
            ':from' => $from,
            ':to_see' => $to_see,
            ':img_url' => $img_url
        ]);

        if ($inserted) {
            return $this->db->lastInsertId();
        } else {
            return FALSE;
        }
    }



    /**
     * 
     * @return boolean
     */
    public function getAllVisitors($order_by, $order_format, $start, $limit)
    {
        try {
            $q = $this->db->prepare("SELECT * FROM visitors ORDER BY {$order_by} {$order_format} LIMIT {$start}, {$limit}");

            $q->execute();

            if ($q->rowCount()) {
                return $q->fetchAll(PDO::FETCH_ASSOC);
            } else {
                return FALSE;
            }
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }



    /**
     * 
     * @param type $pass_id
     * @return boolean
     */
    public function checkout($pass_id)
    {
        $query = $this->db->prepare("UPDATE visitors SET status = 1, check_out_time = NOW() WHERE id = :pass_id AND status != 1");

        $query->execute([':pass_id' => $pass_id]);

        if ($query->rowCount()) {
            return TRUE;
        } else {
            return FALSE;
        }
    }



    /**
     * 
     * @param type $value
     * @return boolean
     */
    public function visitorSearch($value)
    {
        try {
            $q = $this->db->prepare("SELECT * FROM visitors 
                WHERE id LIKE '%{$value}%'
                || name LIKE '%{$value}%'
                || email LIKE '%{$value}%'
                || phone LIKE '%{$value}%'
                || where_from LIKE '%{$value}%'
                || to_see LIKE '%{$value}%'");

            $q->execute();

            if ($q->rowCount()) {
                return $q->fetchAll(PDO::FETCH_ASSOC);
            } else {
                return FALSE;
            }
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }
}
