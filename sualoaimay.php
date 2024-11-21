<?php

$page_title = 'Sửa loại máy';
include('includes/header.html');


	require('Connect_Database.php'); 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	$id = $_POST['maloai'];
	$sql = "Select * FROM loai_may Where Ma_loai = '$id'";

	$result = mysqli_query($dbc, $sql);
	$rows = mysqli_fetch_array($result);
	$maloai = $id;
	$tenloai = $_POST['tenloai'];
	
	$errors = array();

	// Kiểm tra Tên laptop
	if (empty($_POST['tenloai'])) {
		$errors[] = 'Bạn chưa nhập Tên loại máy. Vui lòng nhập!';
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

		$q = "UPDATE loai_may SET 
		Ten_loai ='$tenloai'
		WHERE Ma_loai ='$maloai'";


		$r = @mysqli_query($dbc, $q); 
		session_start();
		if ($r) { 
			echo '<p class="success-message">Đã cập nhật thành công !</p>';

			echo '<a href="quanlyloaimay.php" class="button-link" >Trở về</a>';

			
		} else { 

			echo '<h1>System Error</h1>
			<p class="error">You could not be registered due to a system error. We apologize for any inconvenience.</p>';

			// Debugging message:
			echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $q . '</p>';
		} 
		mysqli_close($dbc); // Close the database connection.

		// Include the footer and quit the script:
		include('includes/footer.html');
		exit();
	} else { // Report the errors.

		echo '
		<p class="error">Thông tin các lỗi:<br />';
		foreach ($errors as $msg) { 
			echo " - $msg<br />\n";
		}
		echo '<p><b>Please try again.</b></p>';
	} // End of if (empty($errors)) IF.
	mysqli_close($dbc); // Close the database connection.
} else { 
	$id = $_GET['maloai'];


	//Truy vấn Thông tin về Laptop có mamay = $mamay
	$sql = "Select * FROM loai_may Where Ma_loai = '$id'";

	$result = mysqli_query($dbc, $sql);
	$rows = mysqli_fetch_array($result);
}
?>
<p class='header-title'>CẬP NHẬT LOẠI MÁY</p>

	<form action="" method="POST">
		<table>
			<tr>
				<td>
					Mã loại :
				</td>
				<td><input readonly type="text" name="maloai" size="10" maxlength="10"
						value="<?php if (isset($_POST['maloai'])) echo $maloai;
								else echo $rows['Ma_loai']; ?>" /></td>
			</tr>
			<tr>
				<td>
					Tên loại:
				</td>
				<td>
					<input type="text" name="tenloai" size="15" maxlength="100"
						value="<?php if (isset($_POST['tenloai'])) echo $tenloai;
								else echo $rows['Ten_loai']; ?>" />
				</td>
			</tr>

			

			<tr>
				<td colspan="2" align="center">
					<input type="submit" name="Sua" value="Cập nhập thông tin" />
				</td>
			</tr>
		</table>
	</form>
	<a href="quanlyloaimay.php" class="button-link">Trở về</a>  

	<?php include('includes/footer.html'); ?>
</body>

</html>