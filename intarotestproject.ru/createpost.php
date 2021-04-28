<?php
require "db.php";
date_default_timezone_set(date_default_timezone_get());

if (isset($_SESSION['logged_user'])): ?>
<div class="d-flex">

<div class="mr-auto p-2"> Добро пожаловать, <?php echo $_SESSION['logged_user']->firstName; ?>!
</div>
</div>

	<div class="d-flex flex-row-reverse">
	<div class="p-2"> <a class="btn btn-outline-primary" href="/logout.php">Выйти</a></div>
	<div class="p-2"><a class="btn btn-primary" href="/createpost.php">Создать пост</a></div>
    </div>
</div>
<?php
else:

    header('Location: /');
endif; ?>


<?php
$data = $_POST;

$total = count($_FILES['file']['name']);
$postExist = R::findOne('post', 'name = ?', array(
    $data['title']
));

if (isset($data["doPost"])) {
    $errors = array();

    if ($data['title'] == '') {
        $errors[] = "Введите название поста";
    }

    if ($postExist) {
        $errors[] = "Пост с таким названием уже существует";
    }

    if ($data['description'] == '') {
        $errors[] = "Заполните описание";
    }

    if ($_FILES['file']['size'][0] == 0) {
        $errors[] = "Файлы не выбраны";
    }

    if (!empty($errors)) {
        echo '<div  style="color : red; text-align: center">' . array_shift($errors) . '</div>';
    } else {
        if (!empty($_FILES['file'])) {
            R::begin();

            try {
                $post = R::dispense('post');
                $post->name = $data['title'];
                $post->description = $data['description'];
                $post->userId = $_SESSION['logged_user']->id;
                $post->creating_date = date('Y-m-d H:i:s', time());

                for ($i = 0;$i < $total;$i++) {
                    $imageId = uniqid();
                    $tmpFilePath = $_FILES['file']['tmp_name'][$i];

                    if ($tmpFilePath != "") {
                        $ext = '.' . pathinfo($_FILES['file']['name'][$i]) ['extension'];
                        ;
                        $newFilePath = "files/" . $imageId . $ext;

                        move_uploaded_file($tmpFilePath, $newFilePath);

                        $file = R::dispense('file');
                        $file->name = $imageId;
                        $file->type = $_FILES['file']['type'][$i];
                        $file->path = $newFilePath;
                        $post->ownFileList[] = $file;
                        $postId = R::store($post);
                        R::commit();

                        echo '<meta http-equiv="refresh" content="0;URL=/post.php?id=';
                        echo $postId;
                        echo '">';
                    }
                }
            } catch (Exception $e) {
                R::rollback();
            }
        }
    }
}

?>
	
	<html>
	<head>
	<title><?php echo $titleVar; ?></title>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="mybootstrap\css\bootstrap.css" rel="stylesheet">
    <script src="mybootstrap\js\jquery-3.6.0.min.js"></script>
	<script src="mybootstrap\js\bootstrap.min.js"></script>

	</head>

<div class="d-flex justify-content-center">
<form method="post" enctype="multipart/form-data">

		<p>
		<p>
		<input type="text" placeholder="Название" class="form-control" name="title" value="<?php echo @$data['title']; ?>"></input>
		<p>
		
		<p>
		<input type="text" placeholder="Описание" class="form-control" name="description" value = "<?php echo @$data['description']; ?>"></input>
		<p>
		
		<p>
		<input type="file" name="file[]" accept=".zip, .doc, .docx, .xls, .xlsx, .pdf, .jpg, .png" multiple="multiple">
		<p>
		
		<input type="submit" class="btn btn-outline-primary" name = "doPost" value="Опубликовать"></input>
		
		<a href="index.php" class="btn btn-outline-primary">Вернуться на главную</a>
		
</form>
</div>
</html>
