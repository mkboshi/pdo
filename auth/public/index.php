<?php

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

use App\Controllers\Controller;
use App\Controllers\UserController;
use App\Model\UserModel;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$uri = $_SERVER['REQUEST_URI'];
$loader = new FilesystemLoader(dirname(__DIR__) . '/templates/');
$twig = new Environment($loader);
$connection = new Controller($twig);
$userController = new UserController();
$login = (string)($_POST['login']);
$password = (string)($_POST['password']);


switch ($uri)
{
    case '/':
    {
        $connection->mainFormInvoke();
        break;
    }
    case '/auth':
    {
        if ($login != '' && $password != '')
        {
            $userController->controllerLogin($login, $password);
        }
        else
        {
            $connection->errorFormInvoke();
        }
        break;
    }

    case '/reg':
    {
        if ($login != '' && $password != '' && $userController->controllerFindUser($login, '') == false)
        {
            $userController->controllerReg($login, $password);
            header('Location: ' . 'http://192.168.20.129:808/auth' . '/');
        }
        else
        {
            $connection->errorFormInvoke();
        }
        break;
    }

    case '/logout':
    {
        header('Location: ' . 'http://192.168.20.129:808/auth' . '/');
        $userController->controllerLogout();
        break;
    }

    case '/exit':
    {
        header('Location: ' . 'http://192.168.20.129:808/auth' . '/');
        break;
    }
}


if (isset($_COOKIE['eto_ne_login']))
{
    echo('Вы были авторизованы. Ваш логин - ' . $_COOKIE['eto_ne_login'] . '.');
    $connection->loggedFormInvoke();
}

if (isset($_COOKIE['eto_ne_reg_login']) && !isset($_COOKIE['eto_ne_login']))
{
    echo('Вы были зарегистрированы. Ваш логин - ' . $_COOKIE['eto_ne_reg_login'] . '.');
}