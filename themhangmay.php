<?php
$page_title = 'Thêm hãng laptop';
include('includes/header.html');


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	require('Connect_Database.php'); // Connect to the db.

	$errors = array(); // Initialize an error array.

	//Xử lý mã laptop(tự động)
	//Truy vấn lấy mã laptop có số lớn nhất
	$sql = "SELECT Ma_hang FROM hang_laptop WHERE Ma_hang LIKE 'MH%' ORDER BY Ma_hang DESC LIMIT 1";
	//Kết quả truy vấn
	$result = mysqli_query($dbc, $sql);
	if (mysqli_num_rows($result) <> 0) {
		$rows = mysqli_fetch_array($result);
		$MaMoiNhat = $rows['Ma_hang'];
		//Lấy phần tử số lớp nhât của malaptop có trong csdl và bỏ phần tử đầu.
		$SoLonNhat = (int)substr($MaMoiNhat,2);
		$Somoi = $SoLonNhat  + 1;
		//Tạo mã máy tự động.
		//str_pad(2,'0'STR_PAD_LEFT) thêm giá trị $somoi vào chuỗi
		//Nếu chữ số <2 thì thêm 0 vào đằng trước.
		$mahang = 'MH'. str_pad($Somoi,2,'0',STR_PAD_LEFT);
	}else {
		$mahang = 'MH01';
	}


	// Kiểm tra Tên Hãng sản xuất
	if (empty($_POST['tenhang'])) {
		$errors[] = 'Bạn chưa nhập tên hãng. Vui lòng nhập!';
	} else {
		$sql_hang = "Select * FROM hang_laptop";
		$result_hang = mysqli_query($dbc, $sql_hang);
		if (mysqli_num_rows($result_hang) <>0) {
			while($rows_hang = mysqli_fetch_array($result_hang)){
				if ($_POST['tenhang'] == $rows_hang['Ten_hang']) {
					$errors[] = 'Tên hãng bị trùng vui lòng nhập lại!';
				}
				else{
					$tenhang = mysqli_real_escape_string($dbc, trim($_POST['tenhang']));
				}
			}
		}
	}

	
    // Kiểm tra Tên nước sản xuất
	if (empty($_POST['Nuoc_sx'])) {
		$errors[] = 'Bạn chưa nhập nước sản xuất. Vui lòng nhập!';
	} else {
		$Nuoc_sx = mysqli_real_escape_string($dbc, trim($_POST['Nuoc_sx']));
	}


	// Kiểm tra Email
	if (empty($_POST['Email'])) {
		$errors[] = 'Bạn chưa nhập Email!. Vui lòng nhập Email';
	} else {
		$Email = mysqli_real_escape_string($dbc, trim($_POST['Email']));
	}

	// Kiểm tra Hình
	if (empty($_POST['logo'])) {
		$errors[] = 'Chưa chọn logo cho hãng!';
	} else {
		$logo = mysqli_real_escape_string($dbc, trim($_POST['logo']));
	}


	if (empty($errors)) { 

		// Make the query:
		$q = "INSERT INTO hang_laptop (Ma_hang, Ten_hang, Nuoc_sx,Email, Logo) VALUES('$mahang','$tenhang','$Nuoc_sx','$Email','$logo')";
		$r = @mysqli_query($dbc, $q); // Run the query.
		if ($r) { // If it ran OK.


			// Print a message:
			echo '<p class="success-message">Đã thêm hãng sản xuất thành công!</p>';


			echo '<a href="quanlyhangmay.php" class="button-link" >Quay lại</a>';

			echo '<a href="themhangmay.php" class="button-link">Thêm tiếp Hãng sản xuất</a>';
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

		echo '
		<p class="error">Thông tin các lỗi:<br />';
		foreach ($errors as $msg) { // Print each error.
			echo " - $msg<br />\n";
		}
		echo '<p><b>Please try again.</b></p>';
	} // End of if (empty($errors)) IF.

	mysqli_close($dbc); 

} 
?>

<p class='header-title'>THÊM hãng máy</p>
<form action="" method="post">
	<table>
		<tr hidden>
			<td>
				Mã hãng sản xuất:
			</td>
			<td><input type="text" name="mahangsx" size="10" maxlength="10" value="<?php if (isset($_POST['mahangsx'])) echo $_POST['mahangsx']; ?>" /></td>
		</tr>

		<tr>
			<td>
				Tên hãng sản xuất:
			</td>
			<td>
				<input type="text" name="tenhang" size="15" maxlength="100" value="<?php if (isset($_POST['tenhang'])) echo $_POST['tenhang']; ?>" />
			</td>
		</tr>
		<tr>
			<td>
				Nước sản xuất:
			</td>
			<td><input type="text" name="Nuoc_sx" size="15" value="<?php if (isset($_POST['Nuoc_sx'])) echo $_POST['Nuoc_sx']; ?>" /></td>
		</tr>
		

	
		<tr>
			<td>
				Email:
			</td>
			<td>
				<input type="text" name="Email" size="15" value="<?php if (isset($_POST['Email'])) echo $_POST['Email']; ?>" />
			</td>
		</tr>

		<tr>
			<td>
				Logo:
			</td>
			<td>
				<input type="file" name="logo" size="15" maxlength="200" value="<?php if (isset($_POST['logo'])) echo $_POST['logo']; ?>" />
			</td>
		</tr>

		<tr>
			<td colspan="2" align="center">
				<input type="submit" name="submit" value="THÊM HÃNG MÁY" />
			</td>

		</tr>
	</table>
	<a href="quanlyhangmay.php" class="button-link">Quay Lại</a>

</form>
<?php include('includes/footer.html'); ?>