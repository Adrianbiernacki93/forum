<!DOCTYPE html>
<?php
require_once "connection.php";

(isset($_GET['id'])) ? $id = $_GET['id'] : $id = 1;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add'])) {
        if ($_POST['nameUser'] != '' && $_POST['text'] != '') {
            $name = $_POST['nameUser'];
            $text = $_POST['text'];
            $conn->query("INSERT INTO comments(idsection,user,content) VALUES ({$id},'{$name}','{$text}')");
        } else {
            $empty = "The field cannot be empty";
        }
    }

    $count = ("SELECT MAX(id) FROM comments WHERE idsection={$id}");
    $statement = $conn->prepare($count);
    $statement->execute();
    $length = $statement->fetchColumn();

    for ($x = 0; $x <= $length; $x++) {
        if (isset($_POST[$x])) {
            $conn->query("delete from comments where id={$x}");
        }
    }
}
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>komentarze</title>
        <link href="style.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>

        <?php
        $sql = "SELECT * from topic";
        $result = $conn->query($sql);
        if ($result->rowCount() > 0) {
            echo "<nav>";
            echo "<ul>";
            while ($row = $result->fetch()) {
                echo "<li><a href=index.php?id=" . $row['id'] . ">" . $row['heading'] . "</a></li>";
            }
            echo "</ul>";
        }
        ?>
    </nav>

    <main>
        <header>
            <?php
            $sql = "SELECT heading,content from topic where id= {$id}";
            $result = $conn->query($sql);
            $row = $result->fetch();
            ?>
            <h2><?php echo $row['heading'] ?></h2>

        </header>
        <div class="mainContent">
            <p><?php echo $row['content'] ?></p>
        </div>
    </main>

    <?php
    $sql = "SELECT * FROM comments where idsection={$id}";
    $result = $conn->query($sql);

    if ($result->rowCount() > 0) {
        while ($row = $result->fetch()) {

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
    if (isset($empty)) {
        echo $empty;
    }
    ?>
                    </span>
            </h4>
        </header>       
        <div class="commentContent">
            <textarea placeholder="content of the commentary.." name="text"></textarea>
            <span><?php
                if (isset($empty)) {
                    echo $empty;
                }
                ?></span>
            <input type="submit" value="Add" name="add">
            </form>
        </div>
    </article>
</body>
</html>
