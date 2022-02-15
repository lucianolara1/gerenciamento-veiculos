<?php
session_start();
ob_start();
include_once '../config/conexao.php';

$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);

if (empty($id)) {
    $_SESSION['msg'] = "<p style='color: #f00;'>Erro: Motorista não encontrado!</p>";
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html>

<head lang="pt-br">
    <title>Gerenciamento de motoristas</title>
    <script src="../js/jquery.js"></script>
    <link rel="stylesheet" href="../css/bootstrap.min.css" />
    <script src="../js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../css/bootstrap-select.min.css">
    <script src="../js/bootstrap-select.min.js"></script>
</head>

<body>

    <h1 style="text-align: center;margin: 20px;">Gerenciamento de motoristas</h1>
    <a href="cadastrar.php" class="btn btn-primary" style="position: relative;left: 42%;right: 50%;">Cadastrar novo</a>
    <a href="../" class="btn btn-light" style="position: relative;left: 43%;right: 50%;">Voltar</a>
    <hr>

    <div class="container" style="text-align: center;">
        <h1>Informações detalhadas</h1>

        <?php
        $query_motoristas = "SELECT mot_id, mot_nome, mot_cpf, mot_habilitacao FROM tb_motorista WHERE mot_id = $id LIMIT 1";
        $result_motorista = $conn->prepare($query_motoristas);
        $result_motorista->execute();

        if (($result_motorista) and ($result_motorista->rowCount() != 0)) {
            $row_motorista = $result_motorista->fetch(PDO::FETCH_ASSOC);
            //var_dump($row_motorista);
            extract($row_motorista);
            //echo "ID: " . $row_motorista['id'] . "<br>";            
            echo "<table class='table table-bordered table-striped' style='top:40px;'>
            <thead>
                <tr>
                <th>ID</th>
                    <th>Nome</th>
                    <th>CPF</th>
                    <th>Habilitação</th>
                    <th>AÇÃO</th>
                </tr>
            </thead>
            <tbody>
                                    <tr>
                                    <td>$mot_id</td>
                            <td>$mot_nome</td>
                            <td>$mot_cpf</td>
                            <td>$mot_habilitacao</td>
                            <td>
                            <a href='visualizar.php?id=$mot_id' class='btn btn-secondary btn-sm'>Ver</a>
                                <a href='editar.php?id=$mot_id' class='btn btn-primary btn-sm'>Editar</a>
                                <a href='apagar.php?id=$mot_id' class='btn btn-danger btn-sm' onclick='return confirma()'>Excluir</a>
                            </td>
                        </tr>
                                </tbody>
        </table>";
        } else {
            $_SESSION['msg'] = "<p style='color: #f00;'>Erro: Motorista não encontrado!</p>";
            header("Location: index.php");
        }
        ?>
</body>

</html>