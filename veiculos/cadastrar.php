<?php
session_start();
ob_start();
include_once '../config/conexao.php';
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
                            html1 += '<option value="' + data[count].vei_modelo + '">' + data[count].vei_descricao + '</option>';
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

        <h3>Cadastrar veiculo</h3>
        <?php
        //Receber os dados do formulário
        $dados = filter_input_array(INPUT_POST);

        //Verificar se o veiculo clicou no botão
        if (!empty($dados['CadVeiculo'])) {
            //var_dump($dados);

            $empty_input = false;

            $dados = array_map('trim', $dados);


            if (!$empty_input) {
                $query_veiculo = "INSERT INTO tb_veiculos (vei_placa, vei_descricao, vei_modelo) VALUES (:placa, :descricao, :modelo) ";
                $cad_veiculo = $conn->prepare($query_veiculo);
                $cad_veiculo->bindParam(':placa', $dados['vei_placa'], PDO::PARAM_STR);
                $cad_veiculo->bindParam(':descricao', $dados['vei_descricao'], PDO::PARAM_STR);
                $cad_veiculo->bindParam(':modelo', $dados['vei_modelo'], PDO::PARAM_STR);
                $cad_veiculo->execute();
                if ($cad_veiculo->rowCount()) {
                    unset($dados);
                    $_SESSION['msg'] =  "<p style='color: green;'>veiculo cadastrado com sucesso!</p>";
                    header("Location: index.php");
                } else {
                    echo "<p style='color: #f00;'>Erro: veiculo não cadastrado com sucesso!</p>";
                }
            }
        }
        ?>
        <div class="panel panel-default">
            <div class="panel-body">
                <form name="cad-veiculo" method="POST" action="">

                    <div class="form-group">
                        <label>Selecione um motorista para esse veiculo</label>
                        <select name="tb_motoristas" id="tb_motoristas" class="form-control input-lg" data-live-search="true" title="Selecione o motorista"></select>
                    </div>

                    <div class="form-group">
                        <label>Selecione o modelo do veiculo</label>
                        <select name="vei_modelo" id="tb_veiculos" class="form-control input-lg" data-live-search="true" title="Selecione a veiculo"></select>
                    </div>

                    <div class="form-group">

                        <label>Placa </label><br>
                        <input type="text" name="vei_placa" class="form-control input-lg" id="vei_placa" value="<?php
                                                                                                                if (isset($dados['vei_placa'])) {
                                                                                                                    echo $dados['vei_placa'];
                                                                                                                }
                                                                                                                ?>">
                    </div>

                    <div class="form-group">
                        <label>Descrição </label><br>
                        <input type="text" name="vei_descricao" class="form-control input-lg" id="vei_descricao" value="<?php
                                                                                                                        if (isset($dados['vei_descricao'])) {
                                                                                                                            echo $dados['vei_descricao'];
                                                                                                                        }
                                                                                                                        ?>">
                    </div>

                    <input type="submit" value="Cadastrar" name="CadVeiculo" class="btn btn-success">
                </form>
            </div>
        </div>

</body>

</html>