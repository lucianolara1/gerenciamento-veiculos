<?php
session_start();
include_once '../config/conexao.php';
?>
<!DOCTYPE html>
<html>

<head lang="pt-br">
    <title>Gerenciamento de utilizações de usos</title>
    <script src="../js/jquery.js"></script>
    <link rel="stylesheet" href="../css/bootstrap.min.css" />
    <script src="../js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../css/bootstrap-select.min.css">
    <script src="../js/bootstrap-select.min.js"></script>
</head>

<body>

    <h1 style="text-align: center;margin: 20px;">Gerenciamento de utilizações de usos</h1>
    <a href="cadastrar.php" class="btn btn-primary" style="position: relative;left: 42%;right: 50%;">Cadastrar novo uso</a>
    <a href="../" class="btn btn-light" style="position: relative;left: 43%;right: 50%;">Voltar</a>
    <hr>

    <div class="container" style="text-align: center;">

        <h1>Lista</h1>

        <table id="tabela" class="table table-bordered table-striped" style="top:40px;">
            <thead>

                <tr>
                    <th><input placeholder="Filtrar veiculo" type="text" id="txtColuna1" /></th>
                    <th><input placeholder="Filtrar motorista" type="text" id="txtColuna2" /></th>
                    <th><input placeholder="Filtrar data de uso" type="text" id="txtColuna3" /></th>
                    <th><input placeholder="Filtrar placa" type="text" id="txtColuna4" /></th>
                </tr>

                <tr>
                    <th>Descrição veiculo</th>
                    <th>Motorista</th>
                    <th>Data de uso</th>
                    <th>Placa</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                <tr>



                    <?php
                    if (isset($_SESSION['msg'])) {
                        echo $_SESSION['msg'];
                        unset($_SESSION['msg']);
                    }

                    //Receber o número da página
                    $pagina_atual = filter_input(INPUT_GET, "page", FILTER_SANITIZE_NUMBER_INT);
                    $pagina = (!empty($pagina_atual)) ? $pagina_atual : 1;
                    //var_dump($pagina);

                    //Setar a quantidade de registros por página
                    $limite_resultado = 40;

                    // Calcular o inicio da visualização
                    $inicio = ($limite_resultado * $pagina) - $limite_resultado;


                    $query_usos = "SELECT u.uve_id, u.use_data, v.vei_descricao, v.vei_placa, m.mot_nome
        FROM tb_uso_veiculo u 
        INNER JOIN tb_veiculos v ON v.vei_id = u.vei_id
        INNER JOIN tb_motorista m ON m.mot_id = u.mot_id ORDER BY uve_id DESC LIMIT $inicio, $limite_resultado";
                    $result_usos = $conn->prepare($query_usos);
                    $result_usos->execute();

                    if (($result_usos) and ($result_usos->rowCount() != 0)) {
                        while ($row_uso = $result_usos->fetch(PDO::FETCH_ASSOC)) {
                            //var_dump($row_uso);
                            extract($row_uso);
                            //echo "ID: " . $row_uso['id'] . "<br>";
                            echo "  <td>$vei_descricao</td>
                                <td>$mot_nome </td>
                                <td>$use_data </td>
                                <td>$vei_placa</td>
                                <td>
                                <a href='visualizar.php?id=$uve_id' class='btn btn-secondary btn-sm'>Ver</a>
                                    <a href='apagar.php?id=$uve_id' class='btn btn-danger btn-sm' onclick='return confirma()'>Excluir</a>
                                    </td>
                            </tr>
                                ";
                        }

                        //Contar a quantidade de registros no BD
                        $query_qnt_registros = "SELECT COUNT(uve_id) AS num_result FROM tb_uso_veiculo";
                        $result_qnt_registros = $conn->prepare($query_qnt_registros);
                        $result_qnt_registros->execute();
                        $row_qnt_registros = $result_qnt_registros->fetch(PDO::FETCH_ASSOC);

                        //Quantidade de página
                        $qnt_pagina = ceil($row_qnt_registros['num_result'] / $limite_resultado);

                        // Maximo de link
                        $maximo_link = 2;

                        echo "<a href='index.php?page=1'>Primeira</a> ";

                        for ($pagina_anterior = $pagina - $maximo_link; $pagina_anterior <= $pagina - 1; $pagina_anterior++) {
                            if ($pagina_anterior >= 1) {
                                echo "<a href='index.php?page=$pagina_anterior'>$pagina_anterior</a> ";
                            }
                        }

                        echo "$pagina ";

                        for ($proxima_pagina = $pagina + 1; $proxima_pagina <= $pagina + $maximo_link; $proxima_pagina++) {
                            if ($proxima_pagina <= $qnt_pagina) {
                                echo "<a href='index.php?page=$proxima_pagina'>$proxima_pagina</a> ";
                            }
                        }

                        echo "<a href='index.php?page=$qnt_pagina'>Última</a> ";
                    } else {
                        echo "<p style='color: #f00;'>Erro: Nenhum registro encontrado!</p>";
                    }
                    ?>


            </tbody>
        </table>


        <script>
            function confirma() {
                if (!confirm("Tem certeza que realmente deseja excluir?")) {
                    return false;
                }

                return true;
            }
        </script>

        <script>
            $(function() {
                $("#tabela input").keyup(function() {
                    var index = $(this).parent().index();
                    var nth = "#tabela td:nth-child(" + (index + 1).toString() + ")";
                    var valor = $(this).val().toUpperCase();
                    $("#tabela tbody tr").show();
                    $(nth).each(function() {
                        if ($(this).text().toUpperCase().indexOf(valor) < 0) {
                            $(this).parent().hide();
                        }
                    });
                });

                $("#tabela input").blur(function() {
                    $(this).val("");
                });
            });
        </script>



</body>

</html>