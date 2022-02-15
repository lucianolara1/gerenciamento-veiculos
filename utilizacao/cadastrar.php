<?php
session_start();
ob_start();
include_once '../config/conexao.php';
?>
<!DOCTYPE html>
<html>

<head lang="pt-br">
    <title>Gerenciamento de Uso</title>
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

    <h1 style="text-align: center;margin: 20px;">Gerenciamento de Uso</h1>
    <a href="cadastrar.php" class="btn btn-primary" style="position: relative;left: 42%;right: 50%;">Cadastrar novo</a>
    <a href="../" class="btn btn-light" style="position: relative;left: 43%;right: 50%;">Voltar</a>
    <hr>

    <div class="container" style="text-align: center;">

        <h3>Cadastrar novo uso</h3>
        <?php
        //Receber os dados do formulário
        $dados = filter_input_array(INPUT_POST);

        //Verificar se clicou no botão
        if (!empty($dados['CadVeiculo'])) {
            //var_dump($dados);

            $empty_input = false;

            $dados = array_map('trim', $dados);


            if (!$empty_input) {
                $query_uso = "INSERT INTO tb_uso_veiculo (vei_id, mot_id, use_data) VALUES (:veiculo, :motorista, :datauso) ";
                $cad_uso = $conn->prepare($query_uso);
                $cad_uso->bindParam(':veiculo', $dados['vei_id'], PDO::PARAM_STR);
                $cad_uso->bindParam(':motorista', $dados['mot_id'], PDO::PARAM_STR);
                $cad_uso->bindParam(':datauso', $dados['use_data'], PDO::PARAM_STR);
                $cad_uso->execute();
                if ($cad_uso->rowCount()) {
                    unset($dados);
                    $_SESSION['msg'] =  "<p style='color: green;'>Uso cadastrado com sucesso!</p>";
                    header("Location: index.php");
                } else {
                    echo "<p style='color: #f00;'>Erro: Uso não cadastrado com sucesso!</p>";
                }
            }
        }
        ?>
        <div class="panel panel-default">
            <div class="panel-body">
                <form name="cad-veiculo" method="POST" action="">

                    <div class="form-group">
                        <label>Selecione um motorista para o veiculo</label>
                        <select name="mot_id" id="tb_motoristas" class="form-control input-lg" data-live-search="true" title="Selecione o motorista"></select>
                    </div>

                    <div class="form-group">
                        <label>Selecione o veiculo que foi usado</label>
                        <select name="vei_id" id="tb_veiculos" class="form-control input-lg" data-live-search="true" title="Selecione o veiculo"></select>
                    </div>

                    <div class="form-group">

                        <label>Data de uso </label><br>
                        <input type="date" name="use_data" class="form-control input-lg" id="use_data" value="<?php
                                                                                                                if (isset($dados['use_data'])) {
                                                                                                                    echo $dados['use_data'];
                                                                                                                }
                                                                                                                ?>">
                    </div>

                    <input type="submit" value="Cadastrar" name="CadVeiculo" class="btn btn-success">
                </form>
            </div>
        </div>

</body>

</html>