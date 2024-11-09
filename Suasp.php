<?php
// This script performs an INSERT query to add a record to the users table.

$page_title = 'Sửa thông tin laptop';
include('includes/header.html');

require('Connect_Database.php'); // Connect to the db.
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$id = $_POST['mamay'];
	$sql = "Select * FROM laptop Where Ma_laptop = '$id'";

	$result = mysqli_query($dbc, $sql);
	$rows = mysqli_fetch_array($result);
	$mamay = $id;
	$tenlaptop = $_POST['tenlaptop'];
	$trongluong = $_POST['trongluong'];
	$cauhinh = $_POST['cauhinh'];
	$gia = $_POST['gia'];
	$errors = array();

	// Kiểm tra Tên laptop
	if (empty($_POST['tenlaptop'])) {
		$errors[] = 'Bạn chưa nhập Tên laptop. Vui lòng nhập!';
	} else {
		$tenlaptop = mysqli_real_escape_string($dbc, trim($_POST['tenlaptop']));
	}

	// Kiểm tra Trọng lượng
	if (empty($_POST['trongluong'])) {
		$errors[] = 'Bạn chưa nhập trọng lượng. Vui lòng nhập!';
	} elseif (is_numeric($_POST['trongluong']) && $_POST['trongluong'] > 0) {
		$trongluong = mysqli_real_escape_string($dbc, trim($_POST['trongluong']));
	} else {
		$errors[] = 'Nhập trọng lượng không đúng vui lòng nhập lại';
	}

	// Kiểm tra Hãng sản xuất
	if (isset($_POST['hangsx'])) {
		switch ($_POST['hangsx']) {
			case 'Acer':
				$_POST['hangsx'] = 'MH01';
				break;
			case 'Macbook':
				$_POST['hangsx'] = 'MH02';
				break;
			case 'Asus':
				$_POST['hangsx'] = 'MH03';
				break;
			default:
				$_POST['hangsx'] = 'MH04';
		}
		$hangsx = mysqli_real_escape_string($dbc, trim($_POST['hangsx']));
	}

	// Kiểm tra Loại máy
	if (isset($_POST['loaimay'])) {
		switch ($_POST['loaimay']) {
			case 'Vanphong':
				$_POST['loaimay'] = 'ML01';
				break;
			case 'Gaming':
				$_POST['loaimay'] = 'ML02';
				break;
			case 'Doanhnhan':
				$_POST['loaimay'] = 'ML03';
				break;
		}
		$tenloai = mysqli_real_escape_string($dbc, trim($_POST['loaimay']));
	}
	// Kiểm tra Cấu hình
	if (empty($_POST['cauhinh'])) {
		$errors[] = 'Bạn chưa nhập Cấu hình máy!';
	} else {
		$cauhinh = mysqli_real_escape_string($dbc, trim($_POST['cauhinh']));
	}

	// Kiểm tra Hình
	if(isset($_FILES['hinh'])){
		if (empty($_FILES['hinh']['name'])) {
		   $hinh = mysqli_real_escape_string($dbc, trim($rows['Hinh']));
		}
		else
		{
		$file_name = $_FILES['hinh']['name'];
		$file_tmp =$_FILES['hinh']['tmp_name'];
		move_uploaded_file($file_tmp, __DIR__ . "\\img\\" . $file_name);
		   $hinh = mysqli_real_escape_string($dbc, trim($file_name));
		}
	}



	/*
	if (empty($_POST['hinh'])) {
		$errors[] = 'Chưa chọn hình ảnh!';
	} else {
		$hinh = mysqli_real_escape_string($dbc, trim($_POST['hinh']));
	}

	*/
	// Kiểm tra Giá
	if (empty($_POST['gia'])) {
		$errors[] = 'Bạn chưa nhập giá. Vui lòng nhập!';
	} elseif (is_numeric($_POST['gia']) && $_POST['gia'] > 0) {
		$Gia = mysqli_real_escape_string($dbc, trim($_POST['gia']));
	} else {
		$errors[] = 'Nhập giá không đúng quy định vui lòng nhập lại ';
	}
	if (empty($errors)) { // If everything's OK.

		// Register the user in the database...

		// Make the query:
		$q = "UPDATE laptop SET 
		Ten_laptop ='$tenlaptop',
		Trong_luong ='$trongluong',
		Ma_hang ='$hangsx',
		Ma_loai ='$tenloai',
		Cau_hinh= '$cauhinh',
		Hinh = '$hinh',
		Gia ='$Gia'
		Where Ma_laptop ='$mamay'";
		$r = @mysqli_query($dbc, $q); // Run the query.
		// Khởi tạo session ở đầu trang
		session_start();
		if ($r) { // If it ran OK.
			// Print a message:
			$_SESSION['success_message'] = 'Đã cập nhật thông tin thành công!';
			// Chuyển hướng đến Suasanpham.php
			header("Location: Suasanpham.php");
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
	//Lấy mamay cần edit
	$id = $_GET['mamay'];


	//Truy vấn Thông tin về Laptop có mamay = $mamay
	$sql = "Select * FROM laptop Where Ma_laptop = '$id'";

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
	<p class='header-title'>CẬP NHẬT SẢN PHẨM</p>

	<form action="Suasp.php" method="POST" enctype="multipart/form-data">
		<table>
			<tr>
				<td>
					Mã máy:
				</td>
				<td><input readonly type="text" name="mamay" size="10" maxlength="10"
						value="<?php if (isset($_POST['mamay'])) echo $mamay;
								else echo $rows['Ma_laptop']; ?>" /></td>
			</tr>
			<tr>
				<td>
					Tên laptop:
				</td>
				<td>
					<input type="text" name="tenlaptop" size="15" maxlength="100"
						value="<?php if (isset($_POST['tenlaptop'])) echo $tenlaptop;
								else echo $rows['Ten_laptop']; ?>" />
				</td>
			</tr>
			<tr>
				<td>
					Trọng lượng:
				</td>
				<td><input type="text" name="trongluong" size="15"
						value="<?php if (isset($_POST['trongluong'])) echo $trongluong;
								else echo $rows['Trong_luong']; ?>" />
				</td>
			</tr>
			<tr>
				<td>
					Hãng sản xuất:
				</td>
				<td>
					<select name="hangsx">

						<option value="Acer" <?php if (isset($_POST['hangsx']) && $_POST['hangsx'] == 'Acer') echo 'selected'; ?>>
							Acer
						</option>

						<option value="Macbook" <?php if (isset($_POST['hangsx']) && $_POST['hangsx'] == 'Macbook') echo 'selected'; ?>>

							Macbook

						</option>

						<option value="Asus" <?php if (isset($_POST['hangsx']) && $_POST['hangsx'] == 'Asus') echo 'selected'; ?>>

							Asus

						</option>

						<option value="MSI" <?php if (isset($_POST['hangsx']) && $_POST['hangsx'] == 'MSI') echo 'selected'; ?>>

							MSI

						</option>

					</select>
				</td>
			</tr>

			<tr>
				<td>
					Loại máy:
				</td>
				<td>
					<input type="radio" name="loaimay" value="Vanphong" <?php if (isset($_POST['loaimay']) && $_POST['loaimay'] == 'Vanphong') echo 'checked="checked"'; ?> checked /> Văn phòng
					<input type="radio" name="loaimay" value="Gaming" <?php if (isset($_POST['loaimay']) && $_POST['loaimay'] == 'Gaming') echo 'checked="checked"'; ?> /> Gaming
					<input type="radio" name="loaimay" value="Doanhnhan" <?php if (isset($_POST['loaimay']) && $_POST['loaimay'] == 'Doanhnhan') echo 'checked="checked"'; ?> />Doanh nhân
				</td>
			</tr>
			<tr>
				<td>
					Cấu hình:
				</td>
				<td>
					<input type="text" name="cauhinh" size="15"
						value="<?php if (isset($_POST['cauhinh'])) echo $cauhinh;
								else echo $rows['Cau_hinh']; ?>" />
				</td>
			</tr>

			<tr>
				<td>
					Hình:
				</td>
				<td>
					<input type="file" name="hinh" size="15" maxlength="200"/>
				</td>
			</tr>
			<tr>
				<td>
					Giá:
				</td>
				<td>
					<input type="text" name="gia" size="15" maxlength="15"
						value="<?php if (isset($_POST['gia'])) echo $gia;
								else echo $rows['Gia']; ?>" />
				</td>
			</tr>


			<tr>
				<td colspan="2" align="center">
					<input type="submit" name="Sua" value="Cập nhập thông tin" />
				</td>
			</tr>
		</table>
	</form>
	<?php include('includes/footer.html'); ?>
</body>

</html>