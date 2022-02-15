<?php
session_start();
ob_start();
include_once '../config/conexao.php';

$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);

if (empty($id)) {
    $_SESSION['msg'] = "<p style='color: #f00;'>Erro: uso não encontrado!</p>";
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html>

<head lang="pt-br">
    <title>Gerenciamento de usos</title>
    <script src="../js/jquery.js"></script>
    <link rel="stylesheet" href="../css/bootstrap.min.css" />
    <script src="../js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../css/bootstrap-select.min.css">
    <script src="../js/bootstrap-select.min.js"></script>
</head>

<body>

    <h1 style="text-align: center;margin: 20px;">Gerenciamento de usos</h1>
    <a href="cadastrar.php" class="btn btn-primary" style="position: relative;left: 42%;right: 50%;">Cadastrar novo</a>
    <a href="../" class="btn btn-light" style="position: relative;left: 43%;right: 50%;">Voltar</a>
    <hr>

    <div class="container" style="text-align: center;">
        <h1>Informações detalhadas</h1>

        <?php
        $query_usos = "SELECT uve_id, vei_id, mot_id, use_data FROM tb_uso_veiculo WHERE uve_id = $id LIMIT 1";
        $result_uso = $conn->prepare($query_usos);
        $result_uso->execute();

        if (($result_uso) and ($result_uso->rowCount() != 0)) {
            $row_uso = $result_uso->fetch(PDO::FETCH_ASSOC);
            //var_dump($row_uso);
            extract($row_uso);
            //echo "ID: " . $row_uso['id'] . "<br>";            
            echo "<table class='table table-bordered table-striped' style='top:40px;'>
            <thead>
                <tr>
                <th>ID</th>
                    <th>veiculo</th>
                    <th>motorista</th>
                    <th>data de uso</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                                    <tr>
                                    <td>$uve_id</td>
                            <td>$vei_id</td>
                            <td>$mot_id</td>
                            <td>$use_data</td>
                            <td>
                            <a href='visualizar.php?id=$uve_id' class='btn btn-secondary btn-sm'>Ver</a>
                                <a href='editar.php?id=$uve_id' class='btn btn-primary btn-sm'>Editar</a>
                                <a href='apagar.php?id=$uve_id' class='btn btn-danger btn-sm' onclick='return confirma()'>Excluir</a>
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