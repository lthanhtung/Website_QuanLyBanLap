<?php
// This script performs an INSERT query to add a record to the users table.

$page_title = 'Cập nhập thông tin hãng laptop';
include('includes/header.html');

require('Connect_Database.php'); // Connect to the db.
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$id = $_POST['Mahang'];
	$sql = "Select * FROM hang_laptop Where Ma_hang = '$id'";

	$result = mysqli_query($dbc, $sql);
	$rows = mysqli_fetch_array($result);
	$Mahang = $id;
	$Tenhang = $_POST['Tenhang'];
	$Nuocsx = $_POST['Nuoc_sx'];
	$Email = $_POST['Email'];
	$errors = array();

	// Kiểm tra Tên hãng sản xuất
	if (empty($_POST['Tenhang'])) {
		$errors[] = 'Bạn chưa nhập tên hãng sản xuất. Vui lòng nhập!';
	} else {
		$Tenhang = mysqli_real_escape_string($dbc, trim($_POST['Tenhang']));
	}

	// Kiểm tra Nước sản xuát
	if (empty($_POST['Nuoc_sx'])) {
		$errors[] = 'Bạn chưa nhập Nước sản xuất!';
	} else {
		$Nuocsx = mysqli_real_escape_string($dbc, trim($_POST['Nuoc_sx']));
	}
	if (empty($_POST['Email'])) {
		$errors[] = 'Bạn chưa nhập Email!';
	}elseif (strpos($_POST['Email'],'@') === false) {
		$errors[] = 'Email không đúng. Vui lòng nhập lại!';
	}
	else {
		$Email = mysqli_real_escape_string($dbc, trim($_POST['Email']));
	}

	// Kiểm tra Hình
	if (isset($_FILES['logo'])) {
		if (empty($_FILES['logo']['name'])) {
			$logo = mysqli_real_escape_string($dbc, trim($rows['Logo']));
		} else {
			$file_name = $_FILES['logo']['name'];
			$file_tmp = $_FILES['logo']['tmp_name'];
			move_uploaded_file($file_tmp, __DIR__ . "\\img\\" . $file_name);
			$logo = mysqli_real_escape_string($dbc, trim($file_name));
		}
	}

	if (empty($errors)) { // If everything's OK.

		// Register the user in the database...

		// Make the query:
		$q = "UPDATE hang_laptop SET 
		Ten_hang ='$Tenhang',
		Nuoc_sx ='$Nuocsx',
		Email ='$Email',
		Logo ='$logo'
		Where Ma_Hang ='$Mahang'";
		$r = @mysqli_query($dbc, $q); // Run the query.
		// Khởi tạo session ở đầu trang
		session_start();
		if ($r) { // If it ran OK.
			// Print a message:
			$_SESSION['success_message'] = 'Đã cập nhật thông tin thành công!';
			// Chuyển hướng đến Suasanpham.php
			header("Location: quanlyhangmay.php");
			exit();
		} else { // If it did not run OK.

			// Public message:
			echo '<h1>System Error</h1>
			<p class="error">You could not be registered due to a system error. We apologize for any inconvenience.</p>';

			// Debugging message:
			echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $q . '</p>';
		} // End of if ($r) IF.

		mysqli_close($dbc); // Close the database connection.

		// Include the footer and quit the script:
		include('includes/footer.html');
		exit();
	} else { // Report the errors.

		echo '<h1>Lỗi!</h1>
		<p class="error">Thông tin các lỗi:<br />';
		foreach ($errors as $msg) { // Print each error.
			echo " - $msg<br />\n";
		}
		echo '<p><b>Please try again.</b></p>';
	} // End of if (empty($errors)) IF.
	mysqli_close($dbc); // Close the database connection.
} else { //Form được load lần đầu
	//Lấy Mahang cần edit
	$id = $_GET['Mahang'];


	//Truy vấn Thông tin về Laptop có Mahang = $Mahang
	$sql = "Select * FROM hang_laptop Where Ma_hang = '$id'";

	$result = mysqli_query($dbc, $sql);
	$rows = mysqli_fetch_array($result);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
	<link rel="stylesheet" type="text/css" href="styleThem.css">

</head>

<body>
	<p class='header-title'>CẬP NHẬT HÃNG SẢN XUẤT</p>

	<form action="" method="POST" enctype="multipart/form-data">
		<table>
			<tr hidden>
				<td>
					Mã hãng sản xuất:
				</td>
				<td><input readonly type="text" name="Mahang" size="10" maxlength="10"
						value="<?php if (isset($_POST['Mahang'])) echo $Mahang;
								else echo $rows['Ma_hang']; ?>" /></td>
			</tr>
			<tr>
				<td>
					Tên hãng sản xuất:
				</td>
				<td>
					<input type="text" name="Tenhang" size="15" maxlength="100"
						value="<?php if (isset($_POST['Tenhang'])) echo $Tenhang;
								else echo $rows['Ten_hang']; ?>" />
				</td>
			</tr>
			<tr>
				<td>
					Nước sản xuất:
				</td>
				<td><input type="text" name="Nuoc_sx" size="15"
						value="<?php if (isset($_POST['Nuoc_sx'])) echo $Nuocsx;
								else echo $rows['Nuoc_sx']; ?>" />
				</td>
			</tr>
			<tr>
				<td>
					Email:
				</td>
				<td>
					<input type="text" name="Email" size="15"
						value="<?php if (isset($_POST['Email'])) echo $Email;
								else echo $rows['Email']; ?>" />
				</td>
			</tr>
			<tr>
				<td>
					Logo:
				</td>
				<td>
					<input type="file" name="logo" size="15" maxlength="200" />
				</td>
			</tr>
			<tr>
				<td colspan="2" align="center">
					<input type="submit" name="Sua" value="Cập nhập thông tin" />
				</td>
			</tr>
		</table>
		<a href="quanlyhangmay.php" class="button-link" >Quay lại</a>
	</form>
	<?php include('includes/footer.html'); ?>
</body>

</html>