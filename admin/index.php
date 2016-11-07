<?php session_start();?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>OnePage CMS</title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" href="style.css" type="text/css" />
</head>
<body>
<div id="main">
<?php
if (empty($_POST) && isset($_GET['action'])) {
		$action = $_GET['action'];
		switch ($action) {
			case 'logout':
				session_unset();
				session_destroy();
				break;
	}
}
if (!isset($_SESSION['user'])) {
	$user = '';
	$pass = '';
	if (isset($_POST['login'])) {
		$user = strtolower(trim($_POST['user']));
		$pass = $_POST['pass'];
		$errors = array();
		if ($user == '' || $user != 'admin') {
			$errors['user'] = '';
		}
		if ($pass == '' || $pass != 'admin') {
			$errors['pass'] = '';
		}
		if (empty($errors)) {
			$_SESSION['user'] = $user;
		} else {
			echo '<p class="error">Please fill in your correct ';
			if (isset($errors['user']))
				echo 'username';
			if (count($errors) == 2)
				echo ' and ';
			if (isset($errors['pass']))
				echo 'password';
			echo '.</p>', "\n";
		}
	}
}
if (isset($_SESSION['user'])) {
	$user = $_SESSION['user'];
?>
<ul>
  <li><h1>OnePage CMS</h1></li>
  <li style="float:right"><a href="?action=logout">Logout</a></li>
  <li style="float:right"><a href="../" target="_blank">View site</a></li>
</ul>
<div id="headertext">
	<p class="l">You are logged in as <strong><?php echo $user?></strong>.</p>
</div>
<?php
$target_dir = "../img/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".&nbsp;";
        $uploadOk = 1;
    } else {
        echo "File is not an image.&nbsp;";
        $uploadOk = 0;
    }
    // Check if file already exists
	if (file_exists($target_file)) {
	    echo "Sorry, file already exists. ";
	    $uploadOk = 0;
	}
	// Check file size
	if ($_FILES["fileToUpload"]["size"] > 500000) {
	    echo "Sorry, your file is too large.&nbsp;";
	    $uploadOk = 0;
	}
	// Allow certain file formats
	if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
	&& $imageFileType != "gif" ) {
	    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.&nbsp;";
	    $uploadOk = 0;
	}
	// Check if $uploadOk is set to 0 by an error
	if ($uploadOk == 0) {
	    echo "Sorry, your file was not uploaded.&nbsp;";
	// if everything is ok, try to upload file
	} else {
	    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
	        echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
	    } else {
	        echo "Sorry, there was an error uploading your file.&nbsp;";
	    }
	}
}
?>
<form action="" method="post" enctype="multipart/form-data">
    <p>Select image to upload:</p>
    <label for="fileToUpload" class="button">Browse...</label>
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" value="Upload Image" name="submit">
</form>
<p>&nbsp;</p>
<?php
	if (isset($_POST['edit'])) {
		if (file_put_contents('homecontent.txt', $_POST['homecontent']) !== FALSE)
			echo '<p class="succes">Your changes are saved.</p>', "\n";
	}
	$homecontent = file_get_contents('homecontent.txt');
?>
<form method="post" action="">
	<p>Here you can edit your homepage text:</p>
	<textarea name="homecontent" id="homecontent" rows="20" cols="55"><?php echo test_input($homecontent)?></textarea>
	<p><button type="submit" name="edit">Save changes</button></p>
</form>
<?php } else {?>
<form method="post" action="" id="login">
	<p>
		<label for="user">Username:</label><input type="text" name="user" id="user" value="<?php echo $user?>" />
	</p>
	<p>
		<label for="pass">Password:</label><input type="password" name="pass" id="pass" value="<?php echo $pass?>" />
	</p>
	<p>
		<button type="submit" name="login">Login</button>
	</p>
</form>
<?php }
function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
?>
	<div class="footer">
		<div id="copyright">
			Copyright &copy; <script>document.write(new Date().getFullYear())</script> OnePageCMS.<br>
			Licenced under <a href="https://www.gnu.org/licenses/gpl.txt" target="_blank" style="text-decoration: none;">GPL</a>.
		</div>
		<div id="credits">
			Developed by <a rel="license" href="http://petrospe.org/" style="text-decoration: none;">petrospe.org</a>.
		</div>
	</div>
</div>
<script>
// Remove URL Tag Parameter from Address Bar
if (window.parent.location.href.match(/action=/)){
    if (typeof (history.pushState) != "undefined") {
        var obj = { Title: document.title, Url: window.parent.location.pathname };
        history.pushState(obj, obj.Title, obj.Url);
    } else {
        window.parent.location = window.parent.location.pathname;
    }
}
</script>
</body>
</html>