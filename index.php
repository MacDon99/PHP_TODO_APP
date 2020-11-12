<?php

$filePath = "tasks.json";

$exampleTasks = json_decode(file_get_contents($filePath), true);


if ($exampleTasks == null) {
    $exampleTasks = [
        ["id" => 0, "label" => "Umyć zęby"],
        ["id" => 1, "label" => "Spytać sąsiada o dżem"],
        ["id" => 2, "label" => "Namówić Kamila na wyjaz na ryby"],
        ["id" => 3, "label" => "Zadzwonić do grubego"],
        ["id" => 4, "label" => "Zjeśc masło"]
    ];
}

if (isset($_GET["indexes"])) {
    $fromQuery = $_GET["indexes"];
    $toDelete = explode(',', $fromQuery[0]);
    foreach ($toDelete as $item) {
        foreach ($exampleTasks as $task) {
            if ($task["id"] == $item) {
                unset($exampleTasks[$item]);
            }
        }
    }
}

$maxId = 0;
foreach ($exampleTasks as &$task) {
    if ($maxId < $task["id"]) {
        $maxId = $task["id"];
    }
}
unset($task);

if (isset($_GET["newItem"])) {
    $exampleTasks[] = ["id" => $maxId + 1, "label" => $_GET["newItem"]];
}
$tasksFromFile = file($filePath);

file_put_contents($filePath, json_encode($exampleTasks, JSON_PRETTY_PRINT));
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <div style="background: grey; width: 500px; display:flex; align-items: center; justify-content: flex-start; flex-direction: column;">
        <h2 style="color: white;">To do list</h2>
        <form action="index.php">
            <div style="display: flex; flex-direction: row; padding: 10px;">
                <input type="text" name="newItem" />
                <input type="submit" value="Add" />
            </div>
        </form>
        <div style="background: white; width: 60%; margin-bottom:50px;">
            <form action="index.php" style="display: flex; flex-direction: column;">

                <?php
                foreach ($exampleTasks as &$task) {
                    echo "<p style=\"align-self: center;\" data-task-id=\"";
                    echo $task["id"];
                    echo "\" onClick=\"changeColor(this)\">";
                    echo $task["label"];
                    echo "</p><hr style=\"background: black; width: 90%;\">";
                }
                unset($task);
                ?>
                <input type="hidden" id="indexesToDelete" name="indexes[]" />
                <input type="submit" value="Clear" style="align-self: center;"/>
            </form>

        </div>
    </div>
    <script>
        var indexes = [];

        function changeColor(item) {
            item.style.textDecoration = "line-through";
            var currentId = item.getAttribute("data-task-id");
            indexes.push(currentId);
            document.getElementById('indexesToDelete').value = indexes;
            return false;
        }
    </script>
</body>

</html>