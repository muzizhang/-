<?php
namespace models;

class test extends model
{
    public function login()
    {
        $stmt = $this->_pdo->prepare("SELECT password FROM user WHERE username = ?");
        $stmt->execute([$_POST['username']]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
}