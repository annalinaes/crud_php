<?php

// Inclui o arquivo que contém a classe Crud
require_once('classes/Crud.php');

// Inclui o arquivo que configura a conexão com o banco de dados
require_once('conexao/conexao.php');

// Cria uma instância da classe Database para obter a conexão com o banco de dados
$database = new Database();
$db = $database->getConnection();

// Cria uma instância da classe Crud, passando a conexão como argumento
$crud = new Crud($db);

// Verifica se a ação está definida na URL (por meio do método GET)
if(isset($_GET['action'])){
    // Dependendo da ação especificada, executará uma operação CRUD
    switch($_GET['action']){
        case 'create':
            // Se a ação for 'create', chama o método 'create' da classe Crud, passando os dados do formulário via POST
            $crud->create($_POST);
            // Em seguida, lê todos os registros
            $rows = $crud->read();
            break;
        case 'read':
            // Se a ação for 'read', apenas lê todos os registros
            $rows = $crud->read();
            break;
        case 'update':
            // Se a ação for 'update', verifica se o ID do registro a ser atualizado está definido no POST
            if(isset($_POST['id'])){
                // Se o ID estiver definido, chama o método 'update' da classe Crud para atualizar o registro
                $crud->update($_POST);
            }
            // Em seguida, lê todos os registros
            $rows = $crud->read();
            break;
        case 'delete':
            // Se a ação for 'delete', verifica se o ID do registro a ser excluído está definido na URL (via GET)
            $crud->delete($_GET['id']);
            // Em seguida, lê todos os registros
            $rows = $crud->read();
            break;
        default:
            // Se a ação não for reconhecida, lê todos os registros por padrão
            $rows = $crud->read();
            break;
    }
}else{
    // Se nenhuma ação estiver definida na URL, lê todos os registros por padrão
    $rows = $crud->read();
}
?>

<!-- Aqui começa a parte HTML do código -->

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Cabeçalho HTML com metadados e estilos CSS incorporados -->
</head>
<body>

<?php
// Verifica se a ação é 'update' e se um ID foi fornecido na URL
if(isset($_GET['action']) && $_GET['action'] == 'update' && isset($_GET['id'])){
    $id = $_GET['id'];
    // Lê os detalhes do registro com o ID especificado
    $result = $crud->readOne($id);

    // Se o registro não for encontrado, exibe uma mensagem de erro e encerra a execução
    if(!$result){
        echo "Registro nao encontrado.";
        exit();
    }

    // Extrai os dados do registro para uso no formulário de atualização
    $modelo = $result['modelo'];
    $marca = $result['marca'];
    $placa = $result['placa'];
    $cor = $result['cor'];
    $ano = $result['ano'];
?>

    <!-- Formulário de atualização com os campos pré-preenchidos com os dados do registro -->
    <form action="?action=update" method="POST">
        <input type="hidden" name="id" value="<?php echo $id ?>">
        <!-- Campos de entrada para os atributos do registro -->
        <!-- ... -->
        <input type="submit" value="Atualizar" name="enviar" onclick="return confirm('Certeza que deseja atualizar?')">
    </form>

<?php
}else{
?>

    <!-- Formulário de criação de um novo registro -->
    <!-- ... -->
    <form action="?action=create" method="POST">
        <!-- Campos de entrada para os atributos do registro -->
        <!-- ... -->
        <input type="submit" value="Cadastrar" name="enviar">
    </form>

<?php
}
?>

<!-- Tabela HTML para exibir os registros de veículos -->
<table>
    <tr>
        <!-- Cabeçalhos da tabela -->
        <td>Id</td>
        <td>Modelo</td>
        <td>Marca</td>
        <td>Placa</td>
        <td>Cor</td>
        <td>Ano</td>
        <td>Ações</td>
    </tr>

    <?php
    // Loop para exibir os registros na tabela
    if(isset($rows)){
       foreach($rows as $row){
            echo "<tr>";
            // Exibe os dados do registro em cada coluna
            echo "<td>". $row['id']."</td>";
            echo "<td>". $row['modelo']."</td>";
            echo "<td>". $row['marca']."</td>";
            echo "<td>". $row['placa']."</td>";
            echo "<td>". $row['cor']."</td>";
            echo "<td>". $row['ano']."</td>";
            echo "<td>";
            // Links para editar e excluir o registro
            echo "<a href='?action=update&id=".$row['id']."'>Editar</a>";
            echo "<a href='?action=delete&id=".$row['id']."' onclick='return confirm(\"Tem certeza que deseja deletar esse registro?\")' class='delete'>Deletar</a>";
            echo "</td>";
            echo "</tr>";
        }
    }else{
        echo "Nao ha registros a serem exibidos";
    }
    ?>
</table>
</body>
</html>