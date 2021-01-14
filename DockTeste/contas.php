<?php
/**
 * Criação de Contas
 *
 */


 
// Verificar se foi enviando dados via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = (isset($_POST["id"]) && $_POST["id"] != null) ? $_POST["id"] : NULL;
    $idpessoa = (isset($_POST["idpessoa"]) && $_POST["idpessoa"] != null) ? $_POST["idpessoa"] : NULL;
    $saldo = (isset($_POST["saldo"]) && $_POST["saldo"] != null) ? $_POST["saldo"] : NULL;
    $deposito = (isset($_POST["deposito"]) && $_POST["deposito"] != null) ? $_POST["deposito"] : NULL;
    $saque = (isset($_POST["saque"]) && $_POST["saque"] != null) ? $_POST["saque"] : NULL;
    $limiteSaqueDiario = (isset($_POST["limiteSaqueDiario"]) && $_POST["limiteSaqueDiario"] != null) ? $_POST["limiteSaqueDiario"] : NULL;
    $flagAtivo = (isset($_POST["flagAtivo"]) && $_POST["flagAtivo"] != null) ? $_POST["flagAtivo"] : NULL;
    $tipoConta = (isset($_POST["tipoConta"]) && $_POST["tipoConta"] != null) ? $_POST["tipoConta"] : NULL;
    $dataCriacao = (isset($_POST["dataCriacao"]) && $_POST["dataCriacao"] != null) ? $_POST["dataCriacao"] : NULL;
} else if (!isset($id)) {
    // Se não se não foi setado nenhum valor para variável $id
    $id = (isset($_GET["id"]) && $_GET["id"] != null) ? $_GET["id"] : "";
    $idpessoa = NULL;
    $saldo = NULL;
    $deposito = NULL;
    $saque = NULL;
    $limiteSaqueDiario = NULL;
    $flagAtivo = NULL;
    $tipoConta = NULL;
    $dataCriacao = NULL;
}
 
// Cria a conexão com o banco de dados
try {
    $conexao = new PDO("mysql:host=localhost;dbname=dock", "root", "");
    $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conexao->exec("set names utf8");
} catch (PDOException $erro) {
    echo "Erro na conexão:".$erro->getMessage();
}
 
// Bloco If que Salva os dados no Banco - atua como Create e Update
if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "save" && $idpessoa != "") {
    try {
        if ($id != "") {
            $stmt = $conexao->prepare("UPDATE contas SET idpessoa=?, saldo=?, limiteSaqueDiario=?, flagAtivo=?, tipoConta=?, dataCriacao=? WHERE id = ?");
            $stmt->bindParam(7, $id);
        } else {
            $stmt = $conexao->prepare("INSERT INTO contas (idpessoa, saldo, limiteSaqueDiario, flagAtivo, tipoConta, dataCriacao) VALUES (?, ?, ?, ?, ?, ?)");
        }
        $stmt->bindParam(1, $idpessoa);
        $stmt->bindParam(2, $saldo);
        $stmt->bindParam(3, $limite);
        $stmt->bindParam(4, $flagAtivo);
        $stmt->bindParam(5, $tipoConta);
        $stmt->bindParam(6, $dataC);

        
        $limite = 600;
        $dataC = date('d/m/y');
    
    $saldo = $deposito;

     
            
        


 
        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                echo "Dados cadastrados com sucesso!";
                $id = null;
                $idpessoa = null;
                $saldo = null;
                $limiteSaqueDiario = null;
                $flagAtivo = null;
                $tipoConta = null;
                $dataCriacao = null;
            } else {
                echo "Erro ao tentar efetivar cadastro";
            }
        } else {
            throw new PDOException("Erro: Não foi possível executar a declaração sql");
        }
    } catch (PDOException $erro) {
        echo "Erro: ".$erro->getMessage();
    }
}
 
// Bloco if que recupera as informações no formulário, etapa utilizada para Alterar
if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "upd" && $id != "") {
    try {
        $stmt = $conexao->prepare("SELECT * FROM contas WHERE id = ?");
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            $rs = $stmt->fetch(PDO::FETCH_OBJ);
            $id = $rs->id;
            $idpessoa = $rs->idpessoa;
            $saldo = $rs->saldo;
            $limiteSaqueDiario = $rs->limiteSaqueDiario;
            $flagAtivo = $rs->flagAtivo;
            $tipoConta = $rs->tipoConta;
            $dataCriacao = $rs->dataCriacao;
        } else {
            throw new PDOException("Erro: Não foi possível executar a declaração sql");
        }
    } catch (PDOException $erro) {
        echo "Erro: ".$erro->getMessage();
    }
}

 
// Bloco if utilizado pela etapa Delete
if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "del" && $id != "") {
    try {
        $stmt = $conexao->prepare("DELETE FROM contas WHERE id = ?");
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            echo "Registo foi excluído com êxito";
            $id = null;
        } else {
            throw new PDOException("Erro: Não foi possível executar a declaração sql");
        }
    } catch (PDOException $erro) {
        echo "Erro: ".$erro->getMessage();
    }
}
?>
<!DOCTYPE html>
    <html>
        <head>
            <meta charset="UTF-8">
            <title>Tabela de Contas</title>
            <style>
            .limpar {
                color:black;
                 background-color: rgb(200, 200, 200);
                text-decoration: none; */
                font: 400 13.3333px Arial;
                padding: 2px;
                border-width: 1px;
                border-style: inset;
            }
            </style>
        </head>
        <body>

            <form action="?act=save" method="POST" name="form1" >
                <h1>Criar Contas</h1>
                <hr>
                <input type="hidden" name="id" <?php
                 
                // Preenche o id no campo id com um valor "value"
                if (isset($id) && $id != null || $id != "") {
                    echo "value=\"{$id}\"";
                }
                ?> />


                Nome:
               <input type="text" name="idpessoa" required <?php
 
                // Preenche o idpessoa no campo idpessoa com um valor "value"
                if (isset($idpessoa) && $idpessoa != null || $idpessoa != "") {
                   echo "value=\"{$idpessoa}\"";
                }
                ?> />

                <!-- Deposito inicial:
               <input type="number" name="saldo" <?php 
               
               //Preenche o saldo no campo saldo com um valor "value"
            //    if (isset($saldo) && $saldo != null || $saldo != "") {
            //        echo "value=\"{$saldo}\"";
            //    }
               ?> /> -->

&nbsp;&nbsp;
               <!-- Limite de Saque Diário:
               <input type="number" name="limiteSaqueDiario" min="500" max="500" required<?php
 
               // Preenche o limiteSaqueDiario no campo limiteSaqueDiario com um valor "value"
            //    if (isset($limiteSaqueDiario) && $limiteSaqueDiario != null || $limiteSaqueDiario != "") {
            //        echo "value=\"{$limiteSaqueDiario}\"";
            //    }
               ?> /> -->

               <label for="flagAtivo">
               Bandeira:
               </label>
                <select name="flagAtivo">
                <option value="">Selecione</option>
                <option value="Mastercard">Mastercard</option>
                <option value="Visa">Visa</option>
                <option value="American Express">American Express</option>
                <option value="Hipercard">Hipercard</option>
                <option value="Elo">Elo</option>
                </select>
                <?php
               // Preenche o flagAtivo no campo flagAtivo com um valor "value"
               if (isset($flagAtivo) && $flagAtivo != null || $flagAtivo != "") {
                   echo "value=\"{$flagAtivo}\"";
               }
               ?>

&nbsp;&nbsp;
               <label for="tipoConta">
               Tipo de Conta
               </label>
                <select name="tipoConta">
                <option value="">Selecione</option>
                <option value="0.001">Conta Corrente(001)</option>
                <option value="0.013">Conta Poupança(013)</option>
                </select>
               <!-- <input type="text" name="tipoConta" <?php  
 
               // Preenche o tipoConta no campo tipoConta com um valor "value"
               if (isset($tipoConta) && $tipoConta != null || $tipoConta != "") {
                   echo "value=\"{$tipoConta}\"";
               }
               ?> />-->

&nbsp;&nbsp;
               <!-- Data de Criação
               <input type="date" name="dataCriacao" required <?php
 
               // Preenche o dataCriacao no campo dataCriacao com um valor "value"
                // if (isset($dataCriacao) && $dataCriacao != null || $dataCriacao != "") {
                //     echo "value=\"{date('d/m/Y')}\"";
                // }
               
               ?> /> -->

               <input type="submit" value="Confirmar" />
               <a href="http://localhost/DockTeste/contas.php" class="limpar">Limpar</a>
               

               <br><hr>
               <p style="font-weight: bold; margin: 0px;">Operações Bancárias</p>
               <p>(clique em alterar e depois confirme os dados à cima para poder operar na conta)</p>


               Depositar R$:
               <input type="number" name="deposito" min="0" <?php  
 
               //Preenche o deposito no campo deposito com um valor "value"
               if (isset($deposito) && $deposito != null || $deposito != "") {
                   echo "value=\"{$deposito}\"";
               }
               ?> />

               Saque R$:
               <input type="number" name="saque" min="0" max="600" <?php  
 
               //Preenche o ueue no campo ueue com um valor "value"
               if (isset($saque) && $saque != null || $saque != "") {
                   echo "value=\"{$saque}\"";
               }
               ?> />



               <input type="submit" value="Confirmar" />
               <a href="http://localhost/DockTeste/contas.php" class="limpar">Limpar</a>

            </form>

            <table border="1" width="100%">
                <tr>
                    <th>idpessoa</th>
                    <th>saldo</th>
                    <th>limiteSaqueDiario</th>
                    <th>flagAtivo</th>
                    <th>tipoConta</th>
                    <th>dataCriacao</th>
                    <th>Ações</th>
                </tr>
                <?php
 
                // Bloco que realiza o papel do Read - recupera os dados e apresenta na tela
                try {
                    $stmt = $conexao->prepare("SELECT * FROM contas");
                    if ($stmt->execute()) {
                        while ($rs = $stmt->fetch(PDO::FETCH_OBJ)) {
                            echo "<tr>";
                            echo "<td>".$rs->idpessoa."</td><td>R$".$rs->saldo."</td><td>R$".$rs->limiteSaqueDiario."</td><td>".$rs->flagAtivo."</td><td>".$rs->tipoConta."</td><td>".$rs->dataCriacao
                                       ."</td><td><center><a href=\"?act=upd&id=".$rs->id."\">[Alterar]</a>"
                                       ."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"
                                       ."<a href=\"?act=del&id=".$rs->id."\">[Bloquear]</a></center></td><br>";
                            echo "</tr>";
                        }
                    } else {
                        echo "Erro: Não foi possível recuperar os dados do banco de dados";
                    }
                } catch (PDOException $erro) {
                    echo "Erro: ".$erro->getMessage();
                }
                ?>
            </table>

                <br>
            <a href="http://localhost/DockTeste/"><< VOLTAR</a>
        </body>
    </html>