<?php
session_start();
ob_start();
include_once '../config/conexao.php';

$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);

if (empty($id)) {
    $_SESSION['msg'] = "<p style='color: #f00;'>Erro: Veiculo não encontrado!</p>";
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html>

<head lang="pt-br">
    <title>Gerenciamento de veiculos</title>
    <script src="../js/jquery.js"></script>
    <link rel="stylesheet" href="../css/bootstrap.min.css" />
    <script src="../js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../css/bootstrap-select.min.css">
    <script src="../js/bootstrap-select.min.js"></script>
</head>

<body>

    <h1 style="text-align: center;margin: 20px;">Gerenciamento de veiculos</h1>
    <a href="cadastrar.php" class="btn btn-primary" style="position: relative;left: 42%;right: 50%;">Cadastrar novo</a>
    <a href="../" class="btn btn-light" style="position: relative;left: 43%;right: 50%;">Voltar</a>
    <hr>

    <div class="container" style="text-align: center;">
        <h1>Informações detalhadas</h1>

        <?php
        $query_veiculos = "SELECT vei_id, vei_placa, vei_descricao, vei_modelo FROM tb_veiculos WHERE vei_id = $id LIMIT 1";
        $result_veiculo = $conn->prepare($query_veiculos);
        $result_veiculo->execute();

        if (($result_veiculo) and ($result_veiculo->rowCount() != 0)) {
            $row_veiculo = $result_veiculo->fetch(PDO::FETCH_ASSOC);
            //var_dump($row_veiculo);
            extract($row_veiculo);
            //echo "ID: " . $row_veiculo['id'] . "<br>";            
            echo "<table class='table table-bordered table-striped' style='top:40px;'>
            <thead>
                <tr>
                <th>ID</th>
                    <th>Descrição</th>
                    <th>Placa</th>
                    <th>Modelo</th>
                    <th>AÇÃO</th>
                </tr>
            </thead>
            <tbody>
                                    <tr>
                                    <td>$vei_id</td>
                            <td>$vei_descricao</td>
                            <td>$vei_placa</td>
                            <td>$vei_modelo</td>
                            <td>
                            <a href='visualizar.php?id=$vei_id' class='btn btn-secondary btn-sm'>Ver</a>
                                <a href='editar.php?id=$vei_id' class='btn btn-primary btn-sm'>Editar</a>
                                <a href='apagar.php?id=$vei_id' class='btn btn-danger btn-sm' onclick='return confirma()'>Excluir</a>
                            </td>
                        </tr>
                                </tbody>
        </table>";
        } else {
            $_SESSION['msg'] = "<p style='color: #f00;'>Erro: Usuário não encontrado!</p>";
            header("Location: index.php");
        }
        ?>
</body>

</html>