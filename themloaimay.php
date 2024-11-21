<?php 
$page_title = 'Thêm laptop';
include('includes/header.html');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	require('Connect_Database.php'); // Connect to the db.

	$errors = array(); // Initialize an error array.

    //Xử lý mã laptop(tự động)
	//Truy vấn lấy mã laptop có số lớn nhất
	$sql = "SELECT Ma_loai FROM loai_may WHERE Ma_loai LIKE 'ML%' ORDER BY Ma_loai DESC LIMIT 1";
	//Kết quả truy vấn
	$result = mysqli_query($dbc, $sql);
	if (mysqli_num_rows($result) <> 0) {
		$rows = mysqli_fetch_array($result);
		$MaMoiNhat = $rows['Ma_loai'];
		//Lấy phần tử số lớp nhât của malaptop có trong csdl và bỏ phần tử đầu.
		$SoLonNhat = (int)substr($MaMoiNhat,2);
		$Somoi = $SoLonNhat  + 1;
		//Tạo mã máy tự động.
		//str_pad(2,'0'STR_PAD_LEFT) thêm giá trị $somoi vào chuỗi
		//Nếu chữ số <2 thì thêm 0 vào đằng trước.
		$maloai = 'ML'. str_pad($Somoi,2,'0',STR_PAD_LEFT);
	}else {
		$maloai = 'ML01';
	}


	// Kiểm tra Tên laptop
	if (empty($_POST['tenloai'])) {
		$errors[] = 'Bạn chưa nhập Tên loại. Vui lòng nhập!';
	}

	//Truy van tên laptop
	$sql = 'SELECT Ten_loai FROM loai_may WHERE 1';
	//Kết quả truy vấn
	$result = mysqli_query($dbc, $sql);

	//Kiểm tra trùng lặp Tên laptop
	if (mysqli_num_rows($result) <> 0) {
		while ($rows = mysqli_fetch_array($result)) {
			if ($_POST['tenloai'] == $rows['Ten_loai']) {
				$errors[] = 'Tên loại máy bị trùng vui lòng nhập lại!';
				    break; // Ngừng kiểm tra khi phát hiện lỗi

			} else {
				$tenloai = mysqli_real_escape_string($dbc, trim($_POST['tenloai']));
			}
		}
	}




	
	if (empty($errors)) { 

		$q = "INSERT INTO loai_may (Ma_loai,Ten_loai) VALUES('$maloai','$tenloai')";
		$r = @mysqli_query($dbc, $q); // Run the query.
		if ($r) { // If it ran OK.


			// Print a message:
			echo '<p class="success-message">Đã thêm loại máy thành công !</p>';


			echo '<a href="index.php" class="button-link" >Trở về</a>';

			echo '<a href="themloaimay.php" class="button-link" >Thêm tiếp loại máy</a>';

			

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

	mysqli_close($dbc); // Close the database connection.

} // End of the main Submit conditional.
?>

<p class='header-title'>THÊM LOẠI MÁY</p>
<form action="" method="post">
	<table>

		<tr hidden>
			<td>
				Mã loại:
			</td>
			<td><input type="text" name="maloai" size="10" maxlength="10" value="<?php if (isset($_POST['maloai'])) echo $_POST['maloai']; ?>" /></td>
		</tr>

		<tr>
			<td>
				Tên loại:
			</td>
			<td>
				<input type="text" name="tenloai" size="15" maxlength="100" value="<?php if (isset($_POST['tenloai'])) echo $_POST['tenloai']; ?>" />
			</td>
		</tr>

		<tr>
			<td colspan="2" align="center">
				<input type="submit" name="submit" value="THÊM LOẠI MÁY" />
			</td>
			
		</tr>
	</table>
<a href="quanlyloaimay.php" class="button-link">Trở về</a>  

</form>
<?php include('includes/footer.html'); ?>