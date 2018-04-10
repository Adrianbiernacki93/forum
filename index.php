<?php
session_start();
require_once "connection.php";
    $id = 1;
    
if(isset($_GET['id']) && preg_match('/^[1-3]$/',$_GET['id'])) {
    $id = $_GET['id']; 
    $_SESSION['lastValidId'] = $_GET['id'];
}else
{ 
    if(isset($_SESSION['lastValidId']))
    {
    header ("location: index.php?id={$_SESSION['lastValidId']}");
    } else
    {
        header ("location: index.php?id={$id}");
    }
} 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add'])) {
        
        $userName = htmlentities($_POST['nameUser'],ENT_QUOTES,"UTF-8");
        $text = htmlentities($_POST['text'],ENT_QUOTES,"UTF-8");
        
        ($userName == '') ? $emptyName= "The field cannot be empty" : '';
        ($text == '') ? $emptyContent= "The field cannot be empty" : '';
        
        if ($userName != '' && $text != '') {
            
            $statement = $conn->prepare('INSERT INTO comments(idsection,user,content) VALUES ( :id, :name, :text)');
            $statement -> bindParam(':id',$id);
            $statement -> bindParam(':name', $userName);
            $statement -> bindParam(':text', $text);
            $statement -> execute();
            
            } 
    }

    $statement = $conn->prepare('SELECT MAX(id) FROM comments WHERE idsection= :id');
    $statement -> bindParam(':id',$id);
    $statement -> execute();
    $length = $statement->fetchColumn();

    for ($x = 0; $x <= $length; $x++) {
        if (isset($_POST[$x])) {
            $statement = $conn->prepare('delete from comments where id= :id');
            $statement -> bindParam(':id', $x);
            $statement -> execute();
        }
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>komentarze</title>
        <link href="style.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>

        <?php

        $statement = $conn->prepare('SELECT * from topic');
        $statement -> execute();
        $count = $statement -> rowCount();
        if ($count > 0) {
            echo "<nav>";
            echo "<ul>";
            while ($row = $statement->fetch()) {
                echo "<li><a href=index.php?id=" . $row['id'] . ">" . $row['heading'] . "</a></li>";
            }
            echo "</ul>";
        }
        ?>
    </nav>

    <main>
        <header>
            <?php
          
            $statement = $conn -> prepare('SELECT heading,content from topic where id= :id');
            $statement -> bindValue(':id', $id);
            $statement -> execute();
            $row = $statement->fetch();
            ?>
            <h2><?php echo $row['heading'] ?></h2>

        </header>
        <div class="mainContent">
            <p><?php echo $row['content'] ?></p>
        </div>
    </main>

    <?php
    $statement = $conn->prepare('SELECT * FROM comments where idsection= :id');
    $statement -> bindValue(':id', $id);
    $statement -> execute();
    $result = $statement -> rowCount();
    if ($result > 0) {
        while ($row = $statement->fetch()) {

            echo <<< END
        <article>
        <header>
        <h4>
END;
            echo $row['user'];
            ECHO <<< END
        </h4>
        </header>
        <div class = "commentContent">
        <p>
END;
            echo $row['content'];
            ECHO <<< END
        </p>
        <form method = "post">
        <input type="submit" value="delete" name="{$row['id']}">
        </form>
        </div>
        </article>
END;
        }
    }
    ?>
    <article>
        <header>
            <h4>
                <form method="post">
                    <input type="text" name="nameUser" placeholder="Nick">
                    <span><?php
    if (isset($emptyName)) {echo $emptyName;}
    ?>
                    </span>
            </h4>
        </header>       
        <div class="commentContent">
            <textarea placeholder="content of the commentary.." name="text"></textarea>
            <span><?php
                if (isset($emptyContent)) { echo $emptyContent; }
                ?></span>
            <input type="submit" value="Add" name="add">
            </form>
        </div>
    </article>
</body>
</html>
