<?php
session_start();

const EMAIL_REQUIRED = 'Email invullen';
const PASSWORD_REQUIRED = 'Password invullen';
const CREDENTIALS_NOT_VALID = 'Verkeerde email en/of password';

include_once 'modules/database.php';
include_once 'modules/functions.php';

$errors = [];
$inputs = [];
if(isset($_POST['email']) && isset($_POST['password'])){
    // sanitize & validate email
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);

    if ($email===false) {
        // validate email
        $errors['email'] = EMAIL_REQUIRED;
    } else {
        $inputs['email'] = $email;
    }


// validate password
    $password = filter_input(INPUT_POST, 'password');

    if (empty($password)) {
        $errors['password'] = PASSWORD_REQUIRED;
    } else {
        $inputs['password'] = $password;
    }


// controleer email en password
    if (count($errors) === 0) {

        $result = checkLogin($inputs);

        switch ($result) {
            case 'ADMIN':
                header("Location: admin.php");
                break;
            case 'MEMBER':
                header("Location: member.php");
                break;
            case 'FAILURE':
                $errors['credentials']=CREDENTIALS_NOT_VALID;
                break;
        }
    }
}
?>
<!DOCTYPE html>

<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>SmartPhone4u Home</title>
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
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

<main>
    <div class="container-lg">
        <h2>Inloggen</h2>
        <?php if(!empty($errors['credentials'])): ?>
            <div  class="alert alert-danger">
                <?= $errors['credentials'] ?? '' ?>
            </div>
        <?php endif;?>

        <form method="post">
            <div class="mb-3 mt-3">
                <label for="mail" class="form-label">Email address</label>
                <input type="email" class="form-control" name="email" id="mail" value="<?php echo $inputs['email'] ?? '' ?>">
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

            <button type="submit" name="login" class="btn btn-primary mb-5">Login</button>
        </form>
    </div>
</main>
<footer class="bg-dark">
    <div class="container-fluid text-white">
        Workshop Security
    </div>
</footer>

</body>
</html>

