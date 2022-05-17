<?php 
    require_once('pessoas.php');
    $p = new Pessoa("pdo","localhost","root","");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>PHP PDO</title>
</head>
<body>
    <?php 
        if (isset($_POST['name'])) { //Click no botão cadastrar ou editar
            //EDITAR
            if(isset($_GET['id_update']) && !empty($_GET['id_update'])) {
                $id_update = addslashes($_GET['id_update']);
                $name = addslashes($_POST['name']);
                $tel = addslashes($_POST['tel']);
                $email = addslashes($_POST['email']);
                if (!empty($name) && !empty($tel) && !empty($email)) {
                    //update
                    !$p->update($id_update,$name,$tel,$email);
                    header("location: index.php");
                } else {
                    ?>
                        <div>
                            <h4 class="warning">Preencha todos os campos!</h4>
                        </div> 
                    <?php
                }

            //CADASTRAR
            } else {
                $name = addslashes($_POST['name']);
                $tel = addslashes($_POST['tel']);
                $email = addslashes($_POST['email']);
                if (!empty($name) && !empty($tel) && !empty($email)) {
                    //register
                    if(!$p->register($name,$tel,$email)) {
                        echo "Email já cadastrado!";
                    }
                }   else {
                        ?>
                            <div>
                               <h4 class="warning">Preencha todos os campos!</h4>
                            </div> 
                        <?php
                }
            }
        }
    ?>
    <?php 
        if (isset($_GET['id_update'])) { //Click no botão editar
            $id_update = addslashes($_GET['id_update']);
            $res = $p->searchDataPerson($id_update);
        }
    ?>

    <section class="left">
    <form method="POST">
        <h2>Cadastrar Pessoa</h2>
        <label for="name">Nome</label>
        <input type="text" name="name" id="name" value="<?php if(isset($res)){echo $res['nome'];} ?>">
        <label for="tel">Telefone</label>
        <input type="text" name="tel" id="tel" value="<?php if(isset($res)){echo $res['telefone'];} ?>">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" value="<?php if(isset($res)){echo $res['email'];} ?>">
        <input class="btn" type="submit" value="<?php if(isset($res)){echo "Atualizar";}else{echo "Cadastrar";} ?>">
    </form>
    </section>
    <section class="right">
    <table>
            <tr class="title">
                <td>Nome</td>
                <td>Telefone</td>
                <td colspan="2">Email</td>
            </tr>
        <?php 
            $data = $p->searchData();
            if (count($data) > 0) { //Existem pessoas cadastradas
                for ($i=0; $i < count($data); $i++) {
                    echo "<tr>";
                    foreach ($data[$i] as $k => $v) {
                        if ($k != "id") {
                            echo "<td>".$v."</td>";
                        }
                    }?>
                    <td>
                        <a href="index.php?id_update=<?php echo $data[$i]['id'];?>">Editar</a>
                        <a href="index.php?id=<?php echo $data[$i]['id'];?>">Apagar</a>
                    </td>
                    <?php
                    echo "</tr>";
                }
            } else { //Está vazio
                ?>
        </table>
            <div class="warning">
                <h4>Ainda não há registros!</h4>
            </div>
        <?php
            }
        ?>
    </section>
</body>
</html>

<?php 

    if (isset($_GET['id'])) {
        $id_person = addslashes($_GET['id']);
        $p->delete($id_person);
        header("location: index.php");
    }
?>