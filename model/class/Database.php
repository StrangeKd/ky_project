<?php
class Database
{

    private string $db_name = 'ryandeprez_ky';
    private string $db_user = 'ryandeprez';
    private string $db_pass = '2cdf42018e58e8a00cf8dca854a02b6a';
    private string $db_host = 'db.3wa.io';
    private int $db_port = 3306;
    private $pdo;

    private function getPDO()
    {
        if ($this->pdo === null) {
            $pdo = new \PDO('mysql:dbname=' . $this->db_name . ';port=' . $this->db_port . ';host=' . $this->db_host, $this->db_user, $this->db_pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo = $pdo;
        }
        return $this->pdo;
    }

    protected function query(string $statement)
    {
        $req = $this->getPDO()->query($statement);
        $datas = $req->fetchAll();
        return $datas;
    }

    protected function prepare(string $statement, array $params, bool $one = false)
    {
        $req = $this->getPDO()->prepare($statement);
        $req->execute($params);
        if ($one) {
            $data = $req->fetch(PDO::FETCH_ASSOC);
        } else {
            $data = $req->fetchAll(PDO::FETCH_ASSOC);
        }
        return $data;
    }
}
