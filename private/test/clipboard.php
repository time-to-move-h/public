<?php
$file = 'data.txt';
$current = '';
if (isset($_POST["data"])) {
    $data = filter_var($_POST["data"], FILTER_SANITIZE_STRING);
    // Écrit le résultat dans le fichier
    file_put_contents($file, $data);
}

if(is_file($file)){
    $current = file_get_contents($file);
}

?>


<html>
<body>

<form action="clipboard.php" method="post">
    Data: <input type="text" name="data"><br>
    Value : <?php echo $current; ?><br><br>
    <? echo  '<img class="qrcode" src="/util/qrcode?s=qrl&w=500&h=500&&d=' . $current . '" data-token="' . $current . '">'; ?><br>
    <input type="submit">
</form>

</body>
</html>
