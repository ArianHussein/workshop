<?php


function checkLogin($inputs):string
{
    global $pdo;

    $sql = 'SELECT * FROM `user` WHERE `email` = :e';
    $sth = $pdo->prepare($sql);
    $sth->bindParam(':e',$inputs['email']);
    $sth->setFetchMode(PDO::FETCH_CLASS,'User');
    $sth->execute();
    $user = $sth->fetch();

    //$user=false verkeerde password/username, anders $user is object
    if($user!==false)
    {
        //todo controleer of password goed is, gebruik functie password_verify()
        if (password_verify($inputs['password'], $user->password)) {
            $_SESSION['user'] =$user;
            if($_SESSION['user']->role=="admin")
            {
                return 'ADMIN';
            }
            if($_SESSION['user']->role=="member")
            {
                return 'MEMBER';
            }
            if($_SESSION['user']->role=="manager")
            {
                return 'MANAGER';
            }
        }
    }
    return 'FAILURE';
}

function isAdmin():bool
{
    //controleer of er ingelogd is en de user de rol admin heeft
    if(isset($_SESSION['user'])&&!empty($_SESSION['user']))
    {
        $user=$_SESSION['user'];
        if ($user->role == "admin")
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    return false;
}

function isMember():bool
{
    //controleer of er ingelogd is en de user de rol member heeft
    if(isset($_SESSION['user'])&&!empty($_SESSION['user']))
    {
        $user=$_SESSION['user'];
        if ($user->role == "member")
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    return false;
}

function isManager():bool
{
    //controleer of er ingelogd is en de user de rol admin heeft
    if(isset($_SESSION['user'])&&!empty($_SESSION['user']))
    {
        $user=$_SESSION['user'];
        if ($user->role == "manager")
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    return false;
}



