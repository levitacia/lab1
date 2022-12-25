<?php
    include('settings.php');

    session_start();

    if(isset($_SESSION['auth'])){
        header('Location: index.php');
      }
    $check = 1;
    $options = [
        'cost' => 10
      ];
    if (isset($_POST['register'])) {
        $login = $_POST['login'];
        $name = $_POST['name'];
        $password = $_POST['password'];
        $query = $connection->prepare("SELECT `login` FROM `users` WHERE `login` = :login");
        $query->bindParam("login", $login, PDO::PARAM_STR);
        $query->execute();
        if ($query->rowCount() > 0) {
            $check = 0;
        }
        if ($query->rowCount() == 0) {
            $saltedPassword = password_hash($password, PASSWORD_BCRYPT, $options);
            
            
            $query = $connection->prepare("INSERT INTO users(name,login,password) VALUES (:name,:login,:saltedPassword)");
            $query->bindParam("name", $name, PDO::PARAM_STR);
            $query->bindParam("login", $login, PDO::PARAM_STR);
            $query->bindParam("saltedPassword", $saltedPassword, PDO::PARAM_STR);
            $result = $query->execute();
            if ($result) {
                $check = 1;
                $query1 = $connection->prepare("SELECT * FROM `users` WHERE `login` = :login");
                $query1->bindParam("login",  $login, PDO::PARAM_STR);
                $query1->execute();
                $result1 = $query1->fetch(PDO::FETCH_ASSOC);
                $_SESSION['auth'] = true;
                $_SESSION['id'] = $result1['id_user'];
                $_SESSION['login'] = $result1['login'];
                $_SESSION['name'] = $result1['name'];
                header('Location: index.php');
            } else {
                echo '<p class="error">Неверные данные!</p>';
            }
        }
    }
?>

<h1>Форма регистрации на сайт</h1>


<form method="post" action="" name="signup-form">
    <div class="form-element">
        <label>Login</label>
        <input type="text" name="login" pattern="[a-zA-Z0-9]+" required />
    </div>
    <div class="form-element">
        <label>Name</label>
        <input type="text" name="name" required />
    </div>
    <div class="form-element">
        <label>Password</label>
        <input type="password" name="password" required />
    </div>
    <button type="register" name="register" value="register">Register</button>
    <button type="submit" name="sumbit" value="sumbit">Log In</button>
    </div>
    <div class="error">
        <?php
        if ($check == 0){
            echo '<p class="error">Этот логин уже зарегистрирован!</p>';
        }
        ?>
    </div>
</form>

<?php
if (isset($_POST['sumbit'])) {
    header('Location: login.php');
}
?>
