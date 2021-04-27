<?php
require "db.php";
$result = R::getAll("SELECT u.first_name, p.id, p.name, p.creating_date FROM users u RIGHT JOIN post  p ON u.id = p.user_id ORDER BY p.creating_date DESC");

?>

<html>
<head>
<title>Главная</title>
<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="mybootstrap\css\bootstrap.css" rel="stylesheet">
    <script src="mybootstrap\js\jquery-3.6.0.min.js"></script>
	<script src="mybootstrap\js\bootstrap.min.js"></script>

	
	
<?php if (isset($_SESSION['logged_user'])): ?>

	<div class="d-flex justify-content-start"> Добро пожаловать, <?php echo $_SESSION['logged_user']->firstName; ?>!
	</div>

	<div class="d-flex justify-content-end">
	<div class="d-flex flex-row-reverse">
	<div class="p-2"> <a class="btn btn-outline-primary" href="/logout.php">Выйти</a></div>
	<div class="p-2"><a class="btn btn-primary" href="/createpost.php">Создать пост</a></div>
	</div>
    </div>

	<?php
else: ?>

	<div class="d-flex justify-content-start"> Вы не авторизованы. </div>
	<div class="d-flex flex-row-reverse">
	<div class="p-2"><a class="btn btn-outline-primary" href = "/signup.php">Зарегистрироваться</a></div>
	<div class="p-2"><a class="btn btn-primary" href = "/login.php">Авторизоваться</a></div>
	</div>
	<?php
endif; ?>
</head>

<body>
<div class="d-flex justify-content-center">
<table id="table_id" class="table table-striped">

<thead>
        <tr>
            <th scope='col'>Пост</th>
			<th scope='col'>Автор</th>
			<th scope='col'>Дата загрузки</th>
        </tr>
    </thead>
	
<tbody>
<?php foreach ($result as $item): ?>
		<tr>	 
			<td><a class="btn btn-outline-dark" href="post.php?id=<?php echo $item['id']; ?>"</a><?php echo $item['name']; ?></td>
			<td><?php echo $item['first_name'] ?></td>
			<td><?php echo $item['creating_date'] ?></td>
		</tr>	
	
	<?php
endforeach; ?>
</tbody>	
</table>
</div>
</body>
</html>
