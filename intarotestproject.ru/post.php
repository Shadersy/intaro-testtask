<?php
require "db.php";

if (isset($_GET[id])) {
    $id = $_GET[id];
    $data = R::getAll('SELECT p.name as postname, p.creating_date, p.description, u.first_name as username, f.path FROM post p JOIN file f ON p.id = f.post_id 
			LEFT JOIN users u ON u.id = p.user_id where p.id = ?', array(
        $id
    ));

    if (empty($data)) {
        header('Location: /');
    }
}

function file_force_download($file)
{
    if (file_exists($file)) {
        if (ob_get_level()) {
            ob_end_clean();
        }

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . basename($file));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));

        readfile($file);
        exit;
    }
}
?>


<?php if (isset($_SESSION['logged_user'])): ?>


<div class="mr-auto p-2"> Добро пожаловать, <?php echo $_SESSION['logged_user']->firstName; ?>!
</div>


	<div class="d-flex flex-row-reverse">
	<div class="mr-auto p-2"> <a class="btn btn-outline-primary" href="/logout.php">Выйти</a></div>
	<div class="mr-auto p-2"><a class="btn btn-primary" href="/createpost.php">Создать пост</a></div>
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

<head>
<title><?php echo $titleVar; ?></title>
<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="mybootstrap\css\bootstrap.css" rel="stylesheet">
    <script src="mybootstrap\js\jquery-3.6.0.min.js"></script>
	<script src="mybootstrap\js\bootstrap.min.js"></script>

</head>

<body>



<div class="d-flex justify-content-center">

<div class="card">
  <div class="card-header">
   <?php echo $data[0]['postname'] ?>
  </div>
  <div class="card-body">
    <h5 class="card-title"><?php echo $data[0]['username'] ?></h5>
    <p class="card-text">Описание: <?php echo $data[0]['description'] ?> </p>
	<p class="card-text">Дата добавления: <?php echo $data[0]['creating_date'] ?> </p>
	<p class="card-text">Прикрепленные файлы:
    <?php foreach ($data as $item): ?>
   	
		<p><a href="loadresources.php?path=<?php echo $item['path'] ?>"><?php echo $item['path'] ?></a></p>
   
    	<?php
endforeach; ?></p>
		<div class="d-flex justify-content-end">
		<a href="/index.php" class="btn btn-outline-primary">Вернуться</a>
		</div>
  </div>
</div>

</div>

</body>
</div>
