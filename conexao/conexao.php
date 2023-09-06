<?php

// Define uma classe chamada Database
class Database{
    // Define propriedades privadas para armazenar as informações de conexão com o banco de dados
    private $host = "localhost"; // Endereço do servidor de banco de dados
    private $db_name = "aula3crud"; // Nome do banco de dados
    private $username = "root"; // Nome de usuário do banco de dados
    private $senha = ""; // Senha do banco de dados
    private $conn; // Armazena a conexão PDO

    // Método público para obter uma conexão com o banco de dados
    public function getConnection(){
        $this->conn = null; // Inicializa a conexão como nula

        try{
            // Tenta criar uma nova instância da classe PDO para estabelecer a conexão com o banco de dados
            $this->conn = new PDO("mysql:host=". $this->host.";dbname=".$this->db_name, $this->username, $this->senha);
           
            // Define o modo de erro para exceção, o que significa que o PDO lançará exceções em caso de erros
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }catch(PDOException $e){
            // Em caso de erro na conexão, exibe uma mensagem de erro
            echo "Erro na conexão: ". $e->getMessage();
        }
       
        // Retorna a conexão estabelecida ou nula em caso de erro
        return $this->conn;
    }
}
?>
