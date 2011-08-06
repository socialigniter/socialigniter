<?PHP if ($_POST["hostname"]) {

$config_file = "./application/config/config.php.TEMPLATE";
$current = file_get_contents($config_file, FILE_USE_INCLUDE_PATH);
$current = str_replace("http://localhost/", $_POST["base_site"], $current);
file_put_contents("./application/config/config.php", $current);



$hostname = $_POST["hostname"];
$username= $_POST["username"];
$password= $_POST["password"];
$database_name = $_POST["database_name"];


$database_file = file_get_contents("./application/config/database.php.TEMPLATE", FILE_USE_INCLUDE_PATH);

$database_file = str_replace("['dev']['hostname'] = 'localhost'", "['dev']['hostname'] = '$hostname'", $database_file);

$database_file = str_replace("['dev']['username'] = 'root'", "['dev']['username'] = '$username'", $database_file);

$database_file = str_replace("['dev']['password'] = ''", "['dev']['password'] = '$password'", $database_file);

$database_file = str_replace("['dev']['database'] = 'social-igniter'", "['dev']['database'] = '$database_name'", $database_file);


file_put_contents("./application/config/database.php", $database_file);

copy("./application/config/routes.php.TEMPLATE","./application/config/routes.php");
copy("./application/config/custom.php.TEMPLATE","./application/config/custom.php");
copy("./application/helpers/custom_helper.php.TEMPLATE","./application/helpers/custom_helper.php");


header("Location: /");

}
else{
?>
<form method="POST">
<h1> Base Site URL</h1>

URL to your CodeIgniter root.<br>

Typically this will be your base URL, WITH a trailing slash:

<input type="text" placeholder="http://example.com/" name="base_site">

<h1>Database Connectivity Settings</h1>
The hostname of your database server. <input type="text" placeholder="localhost" name="hostname"><br>
The username used to connect to the database. <input type="text" placeholder="root" name="username"><br>
The password used to connect to the database. <input type="password" placeholder="" name="password"><br> 
The name of the database you want to connect to. <input type="text" placeholder="social-igniter" name="database_name"><br>


<input type="submit">
</form>

<?php } ?>
