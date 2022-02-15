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

$query_veiculos = "SELECT vei_id, vei_placa, vei_descricao, vei_modelo FROM tb_veiculos WHERE vei_id = $id LIMIT 1";
$result_veiculo = $conn->prepare($query_veiculos);
$result_veiculo->execute();

if (($result_veiculo) and ($result_veiculo->rowCount() != 0)) {
    $row_veiculo = $result_veiculo->fetch(PDO::FETCH_ASSOC);
    //var_dump($row_veiculo);
} else {
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

    <script>
        $(document).ready(function() {

            $('select').selectpicker();

            //$('#cidades').selectpicker();

            carrega_dados('tb_motoristas');

            function carrega_dados(tipo, cat_id = '') {
                $.ajax({
                    url: "carrega_dados.php",
                    method: "POST",
                    data: {
                        tipo: tipo,
                        cat_id: cat_id
                    },
                    dataType: "json",
                    success: function(data) {
                        var html = '';
                        for (var count = 0; count < data.length; count++) {
                            html += '<option value="' + data[count].mot_id + '">' + data[count].mot_nome + '</option>';
                        }

                        var html1 = '';
                        for (var count = 0; count < data.length; count++) {
                            html1 += '<option value="' + data[count].vei_id + '">' + data[count].vei_descricao + '</option>';
                        }

                        if (tipo == 'tb_motoristas') {
                            $('#tb_motoristas').html(html);
                            $('#tb_motoristas').selectpicker('refresh');
                        } else {
                            $('#tb_veiculos').html(html1);
                            $('#tb_veiculos').selectpicker('refresh');
                        }
                    }
                })
            }

            $(document).on('change', '#tb_motoristas', function() {
                var cat_id = $('#tb_motoristas').val();
                carrega_dados('tb_veiculos', cat_id);
            });

        });
    </script>
</head>

<body>

    <h1 style="text-align: center;margin: 20px;">Gerenciamento de veiculos</h1>
    <a href="cadastrar.php" class="btn btn-primary" style="position: relative;left: 42%;right: 50%;">Cadastrar novo</a>
    <a href="../" class="btn btn-light" style="position: relative;left: 43%;right: 50%;">Voltar</a>
    <hr>

    <div class="container" style="text-align: center;">
        <h1>Editar</h1>

        <?php
        //Receber os dados do formulário
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        //Verificar se o usuário clicou no botão
        if (!empty($dados['EditVeiculo'])) {
            $empty_input = false;
            $dados = array_map('trim', $dados);
            if (in_array("", $dados)) {
                $empty_input = true;
                echo "<p style='color: #f00;'>Erro: Necessário preencher todos campos!</p>";
            } elseif (!filter_var($dados['vei_placa'])) {
                $empty_input = true;
                echo "<p style='color: #f00;'>Erro: Necessário preencher com placa válido!</p>";
            }

            if (!$empty_input) {
                $query_up_veiculo = "UPDATE tb_veiculos SET vei_placa=:placa, vei_descricao=:descricao WHERE vei_id=:id";
                $edit_veiculo = $conn->prepare($query_up_veiculo);
                $edit_veiculo->bindParam(':placa', $dados['vei_placa'], PDO::PARAM_STR);
                $edit_veiculo->bindParam(':descricao', $dados['vei_descricao'], PDO::PARAM_STR);
                $edit_veiculo->bindParam(':id', $id, PDO::PARAM_INT);
                if ($edit_veiculo->execute()) {
                    $_SESSION['msg'] = "<p style='color: green;'>Veiculo editado com sucesso!</p>";
                    header("Location: index.php");
                } else {
                    echo "<p style='color: #f00;'>Erro: Veiculo não editado com sucesso!</p>";
                }
            }
        }
        ?>


        <div class="panel panel-default">
            <div class="panel-body">
                <form id="edit-usuario" method="POST" action="">

                    <div class="form-group">
                        <label>SELECIONE UM MOTORISTA:</label>
                        <select name="tb_motoristas" id="tb_motoristas" class="form-control input-lg" data-live-search="true" title="Selecione o motorista"></select>
                    </div>

                    <div class="form-group">
                        <label>SELECIONE UM VEICULO:</label>
                        <select name="tb_veiculos" id="tb_veiculos" class="form-control input-lg" data-live-search="true" title="Selecione a veiculo"></select>
                    </div>

                    <div class="form-group">

                        <label>Placa </label><br>
                        <input type="text" name="vei_placa" class="form-control input-lg" id="vei_placa" value="<?php
                                                                                                                if (isset($dados['vei_placa'])) {
                                                                                                                    echo $dados['vei_placa'];
                                                                                                                } elseif (isset($row_veiculo['vei_placa'])) {
                                                                                                                    echo $row_veiculo['vei_placa'];
                                                                                                                }
                                                                                                                ?>">
                    </div>

                    <div class="form-group">
                        <label>Descrição </label><br>
                        <input type="text" name="vei_descricao" class="form-control input-lg" id="vei_descricao" value="<?php
                                                                                                                        if (isset($dados['vei_descricao'])) {
                                                                                                                            echo $dados['vei_descricao'];
                                                                                                                        } elseif (isset($row_veiculo['vei_descricao'])) {
                                                                                                                            echo $row_veiculo['vei_descricao'];
                                                                                                                        }
                                                                                                                        ?>">
                    </div>

                    <input type="submit" value="Salvar" name="EditVeiculo" class="btn btn-success">


                </form>
            </div>
        </div>


</body>

</html>