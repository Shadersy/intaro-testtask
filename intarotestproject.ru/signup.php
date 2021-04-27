<?php
require "db.php";

$data = $_POST;

if (isset($data['doSignup']))
{

    $errors = array();

    if ($data['firstName'] == '')
    {
        $errors[] = "Введите имя!";
    }

    if (preg_match('/[^а-я -]+/msiu', $data['firstName']))
    {
        $errors[] = "Имя может быть составлено только из русских символов, пробелов и дефисов";
    }

    if ($data['email'] == '')
    {
        $errors[] = "Введите email!";
    }

    if ($data['password'] == '')
    {
        $errors[] = "Введите пароль!";
    }

    if ($data['password2'] != $data['password'])
    {
        $errors[] = "Пароли не совпадают";
    }

    if ($data['check1'] != "on")
    {
        $errors[] = "Вы не согласились на обработку персональных данных";
    }

    if (R::count('users', 'email = ?', array(
        $data['email']
    )) > 0)
    {
        $errors[] = "Пользователь с таким email уже существует";
    }

    if ((strlen($data['password']) < 6 || strlen($data['password']) > 35))
    {
        $errors[] = "Неверная длина пароля";
    }

    if (!preg_match("#[0-9]+#", $data['password']))
    {
        $errors[] = "Пароль должен содержать хотя бы одну цифру";
    }

    if (!preg_match("#[a-zA-Z^а-я]+#", $data['password']))
    {
        $errors[] = "Пароль должен содержать хотя бы одну букву";
    }

    if (empty($errors))
    {

        $user = R::dispense('users');
        $user->firstName = $data['firstName'];
        $user->email = $data['email'];
        $user->password = password_hash($data['password'], PASSWORD_DEFAULT);
        R::store($user);

        $_SESSION['logged_user'] = $user;
        echo '<div style="color : green;">"Вы успешно зарегистрированы!"</div>';
        echo '<meta http-equiv="refresh" content="0;URL=/index.php">';

    }
    else
    {
        echo '<div style="color : red; text-align: center">' . array_shift($errors) . '</div>';
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
<form action="/signup.php" method="POST">
	<p>
	<p><strong>Введите имя</strong>:</p>
	<input class="form-control" type="text" name="firstName" value = "<?php echo @$data['firstName']; ?>">
	<p>
	
	<p>
	<p><strong>Ввведите email</strong>:</p>
	<input class="form-control" type="email" name="email" value = "<?php echo @$data['email']; ?>">
	<p>
	
	<p>
	<p><strong>Введите пароль</strong>:</p>
	<input class="form-control" type="password" name="password">
	<p>
	
	<p>
	<p><strong>Повторите пароль</strong>:</p>
	<input class="form-control" type="password" name="password2">
	<p>
	
	<div class="form-check">
    <input type="checkbox" class="form-check-input" name="check1">
    <label class="form-check-label" for="check1">Согласен на обработку персональных данных</label>
	</div>
  
	<button class="btn btn-outline-primary" type="submit" name="doSignup">Зарегистрироваться</button>
	
	<a href="/index.php" class="btn btn-outline-primary">Вернуться</a>
</form>
</div>
