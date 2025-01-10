<?php

include_once 'modules/database.php';
include_once 'modules/functions.php';

const FIRSTNAME_REQUIRED = 'Voornaam invullen';
const LASTNAME_REQUIRED = 'Achternaam invullen';
const EMAIL_REQUIRED = 'Email invullen';
const PASSWORD_REQUIRED = 'Password invullen';
const UNIQUE_EMAIL_REQUIRED ='Email bestaat al, een nieuwe email invullen';

$errors = [];
$inputs = [];
if(isset($_POST['send'])) {
    // sanitize and validate firstname
    $firstname = filter_input(INPUT_POST, 'firstname', FILTER_SANITIZE_SPECIAL_CHARS);

    $firstname = trim($firstname);
    if (empty($firstname)) {
        $errors['firstname'] = FIRSTNAME_REQUIRED;
    } else {
        $inputs['firstname'] = $firstname;
    }

    // sanitize and validate lastname
    $lastname = filter_input(INPUT_POST, 'lastname', FILTER_SANITIZE_SPECIAL_CHARS);

    $lastname = trim($lastname);
    if (empty($lastname)) {
        $errors['lastname'] = LASTNAME_REQUIRED;
    } else {
        $inputs['lastname'] = $lastname;
    }

    // sanitize & validate email
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);

    if ($email===false) {
        // validate email
        $errors['email'] = EMAIL_REQUIRED;
    } else {
            global $pdo;
            $sth = $pdo->prepare('SELECT * FROM user WHERE email=:email ');
            $sth->bindParam(':email', $email);
            $sth->execute();
            $result= $sth->fetchAll(PDO::FETCH_CLASS, 'User');
            if(count($result)==1){
                $errors['email']=UNIQUE_EMAIL_REQUIRED;
            } else {
                $inputs['email'] = $email;
            }

    }

    // validate password
    $password = filter_input(INPUT_POST, 'password');

    $password=trim($password);
    if (empty($password)) {
        $errors['password'] = PASSWORD_REQUIRED;
    } else {
        $inputs['password'] = $password;
    }

    if (count($errors) === 0) {
        global $pdo;

       //todo: codeer passwoord! gebruik functie password_hash()
        $sth =$pdo->prepare('INSERT INTO user  (first_name,last_name, email,password, role) 
                                    VALUES (:firstname,:lastname,:email, :password, "member")');
        $sth->bindParam(':firstname', $inputs['firstname']);
        $sth->bindParam(':lastname', $inputs['lastname']);
        $sth->bindParam(':email', $inputs['email']);
        $sth->bindParam(':password', $password);
        $result=$sth->execute();

        header("Location: index.php");
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <title>Formulieren</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand text-white fs-3" href="index.php">Security</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link active fs-5 text-white" aria-current="page" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-secondary fs-5" href="register.php">Registreren</a>
                </li>
            </ul>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="login.php">inloggen</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<header>
    <div class="container-fluid py-5 "  style="background: url('img/security.jpg'); background-size: cover">
        <div class="row py-5"></div>
    </div>
</header>
<div class="container">
    <h2>Registreren</h2>
    <form method="post" action="">
        <div class="mb-3">
            <label for="n" class="form-label">Voornaam</label>
            <input type="text" class="form-control" id="n"  name="firstname"
                   value="<?php echo $inputs['firstname'] ?? '' ?>">
            <div  class="form-text text-danger">
                <?= $errors['firstname'] ?? '' ?>
            </div>
        </div>

        <div class="mb-3">
            <label for="n" class="form-label">Achternaam</label>
            <input type="text" class="form-control" id="n"  name="lastname"
                   value="<?php echo $inputs['lastname'] ?? '' ?>">
            <div  class="form-text text-danger">
                <?= $errors['lastname'] ?? '' ?>
            </div>
        </div>

        <div class="mb-3 mt-3">
            <label for="mail" class="form-label">Email address</label>
            <input type="text" class="form-control" name="email" id="mail" value="<?php echo $inputs['email'] ?? '' ?>">
            <div  class="form-text text-danger">
                <?= $errors['email'] ?? '' ?>
            </div>
        </div>

        <div class="mb-3 mt-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" class="form-control" id="password">
            <div  class="form-text text-danger">
                <?= $errors['password'] ?? '' ?>
            </div>
        </div>


        <input type="submit" class="btn btn-primary" name="send" value="registreren">
        <a class="btn btn-primary" href="index.php">back</a>
    </form>

</div>


<footer class="bg-dark">
    <div class="container-fluid text-white">
        Workshop Security
    </div>
</footer>


</body>
</html>

