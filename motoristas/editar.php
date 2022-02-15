<?php
session_start();
ob_start();
include_once '../config/conexao.php';

$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);

if (empty($id)) {
    $_SESSION['msg'] = "<p style='color: #f00;'>Erro: motorista não encontrado!</p>";
    header("Location: index.php");
    exit();
}

$query_motoristas = "SELECT mot_id, mot_nome, mot_cpf, mot_habilitacao FROM tb_motorista WHERE mot_id = $id LIMIT 1";
$result_motorista = $conn->prepare($query_motoristas);
$result_motorista->execute();

if (($result_motorista) and ($result_motorista->rowCount() != 0)) {
    $row_motorista = $result_motorista->fetch(PDO::FETCH_ASSOC);
    //var_dump($row_motorista);
} else {
    $_SESSION['msg'] = "<p style='color: #f00;'>Erro: motorista não encontrado!</p>";
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
        <h1>Editar</h1>

        <?php
        //Receber os dados do formulário
        $dados = filter_input_array(INPUT_POST);

        //Verificar se o usuário clicou no botão
        if (!empty($dados['EditMotorista'])) {
            $empty_input = false;
            $dados = array_map('trim', $dados);
            if (in_array("", $dados)) {
                $empty_input = true;
                echo "<p style='color: #f00;'>Erro: Necessário preencher todos campos!</p>";
            } elseif (!filter_var($dados['mot_cpf'])) {
                $empty_input = true;
                echo "<p style='color: #f00;'>Erro: Necessário preencher com cpf válido!</p>";
            }

            if (!$empty_input) {
                $query_up_motorista = "UPDATE tb_motorista SET mot_nome=:nome, mot_cpf=:cpf, mot_habilitacao=:habilitacao WHERE mot_id=:id";
                $edit_motorista = $conn->prepare($query_up_motorista);
                $edit_motorista->bindParam(':nome', $dados['mot_nome'], PDO::PARAM_STR);
                $edit_motorista->bindParam(':cpf', $dados['mot_cpf'], PDO::PARAM_STR);
                $edit_motorista->bindParam(':habilitacao', $dados['mot_habilitacao'], PDO::PARAM_STR);
                $edit_motorista->bindParam(':id', $id, PDO::PARAM_INT);
                if ($edit_motorista->execute()) {
                    $_SESSION['msg'] = "<p style='color: green;'>motorista editado com sucesso!</p>";
                    header("Location: index.php");
                } else {
                    echo "<p style='color: #f00;'>Erro: motorista não editado com sucesso!</p>";
                }
            }
        }
        ?>


        <div class="panel panel-default">
            <div class="panel-body">
                <form id="edit-motorista" method="POST" action="">

                    <div class="form-group">

                        <label>Nome </label><br>
                        <input type="text" name="mot_nome" class="form-control input-lg" id="mot_nome" value="<?php
                                                                                                                if (isset($dados['mot_nome'])) {
                                                                                                                    echo $dados['mot_nome'];
                                                                                                                } elseif (isset($row_motorista['mot_nome'])) {
                                                                                                                    echo $row_motorista['mot_nome'];
                                                                                                                }
                                                                                                                ?>">
                    </div>

                    <div class="form-group">
                        <label>CPF </label><br>
                        <input type="text" name="mot_cpf" class="form-control input-lg" id="mot_cpf" value="<?php
                                                                                                            if (isset($dados['mot_cpf'])) {
                                                                                                                echo $dados['mot_cpf'];
                                                                                                            } elseif (isset($row_motorista['mot_cpf'])) {
                                                                                                                echo $row_motorista['mot_cpf'];
                                                                                                            }
                                                                                                            ?>">
                    </div>

                    <div class="form-group form-row">
                        <label>Habilitação</label>
                        <select required="" class="form-control col-sm-8" name="mot_habilitacao" id="mot_habilitacao" value=value="<?php
                                                                                                                                    if (isset($dados['mot_habilitacao'])) {
                                                                                                                                        echo $dados['mot_habilitacao'];
                                                                                                                                    } elseif (isset($row_motorista['mot_habilitacao'])) {
                                                                                                                                        echo $row_motorista['mot_habilitacao'];
                                                                                                                                    }
                                                                                                                                    ?>">

                            <option style="text-align: center;">Selecione a categoria</option>
                            <option value="1">Categoria A</option>
                            <option value="2">Categoria B</option>
                            <option value="3">Categoria C</option>
                            <option value="4">Categoria D</option>
                            <option value="5">Categoria E</option>

                        </select>
                    </div>

                    <input type="submit" value="Salvar" name="EditMotorista" class="btn btn-success">


                </form>
            </div>
        </div>


</body>

</html>