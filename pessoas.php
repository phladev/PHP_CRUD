<?php 

Class Pessoa {

    private $pdo;
    //CONEXÃO COM O BANCO DE DADOS
    public function __construct($dbname, $host, $user, $senha) {

        try {
            $this->pdo = new PDO("mysql:dbname=".$dbname.";host=".$host,$user,$senha);
        } catch (PDOException $e) {
            echo "Erro Com o Banco de Dados: ".$e->getMessagee();
            exit();
        } catch (Exception $e) {
            echo "Erro Genérico: ".$e->getMessagee();
            exit();
        }
        
    }
    //BUSCA OS DADOS
    public function searchData() {
        $res = array();

        $cmd = $this->pdo->query("SELECT * FROM pessoa ORDER BY nome");
        $res = $cmd->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }
    //CADASTRA PESSOAS
    public function register($nome, $tel, $email) {

        //VERIFICAÇÃO EMAIL
        $cmd = $this->pdo->prepare("SELECT id from pessoa WHERE email = :e");
        $cmd->bindValue(":e", $email);
        $cmd->execute();
        if ($cmd->rowCount() > 0) { //email já está cadastrado
            return false;
        } else { //email não está cadastrado
            $cmd = $this->pdo->prepare("INSERT INTO pessoa (nome, telefone, email) VALUES (:n,:t,:e)");
            $cmd->bindValue(":n", $nome);
            $cmd->bindValue(":t", $tel);
            $cmd->bindValue(":e", $email);
            $cmd->execute();
            return true;
        }
    }
    //APAGA PESSOAS
    public function delete($id) {

        $cmd = $this->pdo->prepare("DELETE FROM pessoa WHERE id = :id");
        $cmd->bindValue(":id",$id);
        $cmd->execute();
    }
    //BUSCAR DADOS DE CERTA PESSOA
    public function searchDataPerson($id) {
        $res = array();

        $cmd = $this->pdo->prepare("SELECT * FROM pessoa WHERE id = :id");
        $cmd->bindValue(":id", $id);
        $cmd->execute();
        $res = $cmd->fetch(PDO::FETCH_ASSOC);
        return $res;
    }

    //ATUALIZAR OS DADOS
    public function update($id, $nome, $tel, $email) {

        $cmd = $this->pdo->prepare("UPDATE pessoa SET nome = :n, telefone = :t, email = :e WHERE id = :id");
        $cmd->bindValue(":n", $nome);
        $cmd->bindValue(":t", $tel);
        $cmd->bindValue(":e", $email);
        $cmd->bindValue(":id", $id);
        $cmd->execute();
    }
}

?>