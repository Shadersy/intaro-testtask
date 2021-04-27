<?php
require "db.php";

$data = $_POST;
if (isset($data['doLogin']))
{
    $errors = array();

    if ($data['email'] == '')
    {
        $errors[] = "Введите email!";
    }

    if ($data['password'] == '')
    {
        $errors[] = "Введите пароль";
    }

    $user = R::findOne('users', 'email = ?', array(
        $data['email']
    ));

    if ($user)
    {

        if (password_verify($data['password'], $user->password))
        {

            $_SESSION['logged_user'] = $user;
            header('Location: /');
        }
        else
        {
            $errors[] = "Неверный пароль";
        }

    }

    else

    {
        $errors[] = "Пользователя с таким email не существует";
    }

    if (!empty($errors))
    {
        echo '<div style="color : red; text-align: center;">' . array_shift($errors) . '</div>';
    }

}
?>

<?php if (isset($_SESSION['logged_user'])): ?>
	<?php echo '<meta http-equiv="refresh" content="0;URL=/index.php">'; ?>
<?php
else: ?>
	<div class="d-flex justify-content-start"> Вы не авторизованы. </div>
	<div class="d-flex flex-row-reverse">
	<div class="p-2"><a class="btn btn-outline-primary" href = "/signup.php">Зарегистрироваться</a></div>
	<div class="p-2"><a class="btn btn-primary" href = "/login.php">Авторизоваться</a></div>
	</div>
<?php
endif; ?>

<head>
<title><?php echo $titleVar; ?></title>
<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="mybootstrap\css\bootstrap.css" rel="stylesheet">
    <script src="mybootstrap\js\jquery-3.6.0.min.js"></script>
	<script src="mybootstrap\js\bootstrap.min.js"></script>

</head>

<div class="d-flex justify-content-center">
<form action="/login.php" method="POST">

<p>
	<p><strong>Введите email</strong>:</p>
	<input type="email" class="form-control" name="email" value = "<?php echo @$data['email']; ?>">
	<p>

	<p>
	<p><strong>Введите пароль</strong>:</p>
	<input type="password" class="form-control" name="password">
<p>

<p><button class="btn btn-outline-primary" type="submit" name="doLogin">Войти</button>
<a href="/index.php" class="btn btn-outline-primary">Вернуться</a>
</form>
</div>
