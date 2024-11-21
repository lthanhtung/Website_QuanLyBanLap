<?php 
$page_title = 'Thêm laptop';
include('includes/header.html');


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	require('Connect_Database.php'); // Connect to the db.

	$errors = array(); // Initialize an error array.

	//Xử lý mã laptop(tự động)
	//Truy vấn lấy mã laptop có số lớn nhất
	$sql = "SELECT Ma_laptop FROM laptop WHERE Ma_laptop LIKE 'M%' ORDER BY Ma_laptop DESC LIMIT 1";
	//Kết quả truy vấn
	$result = mysqli_query($dbc, $sql);
	if (mysqli_num_rows($result) <> 0) {
		$rows = mysqli_fetch_array($result);
		$MaMoiNhat = $rows['Ma_laptop'];
		//Lấy phần tử số lớp nhât của malaptop có trong csdl và bỏ phần tử đầu.
		$Sothutu = (int)substr($MaMoiNhat, 1);
		$Somoi = $Sothutu + 1;
		//Tạo mã máy tự động.
		//str_pad(2,'0'STR_PAD_LEFT) thêm giá trị $somoi vào chuỗi
		//Nếu chữ số <2 thì thêm 0 vào đằng trước.
		$mamay = 'M' . str_pad($Somoi, 2, '0', STR_PAD_LEFT);
	} else {
		$mamay = 'M01';
	}


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
		$hangsx = mysqli_real_escape_string($dbc, trim($_POST['hangsx']));
	} else {
		$errors[] = 'Bạn chưa chọn hãng sản xuất. Vui lòng chọn!';
	}

	// Kiểm tra Loại máy
	if (isset($_POST['loaimay'])) {
		$tenloai = mysqli_real_escape_string($dbc, trim($_POST['loaimay']));
	} else {

		$errors[] = 'Bạn chưa chọn loại loại máy!';
	}

	// Kiểm tra Cấu hình
	if (empty($_POST['cauhinh'])) {
		$errors[] = 'Bạn chưa nhập Cấu hình máy!';
	} else {
		$cauhinh = mysqli_real_escape_string($dbc, trim($_POST['cauhinh']));
	}

	// Kiểm tra Hình
	if (empty($_POST['hinh'])) {
		$errors[] = 'Chưa chọn hình ảnh!';
	} else {
		$hinh = mysqli_real_escape_string($dbc, trim($_POST['hinh']));
	}

	// Kiểm tra Giá
	if (empty($_POST['gia'])) {
		$errors[] = 'Bạn chưa nhập giá. Vui lòng nhập!';
	} elseif (is_numeric($_POST['gia']) && $_POST['gia'] > 0) {
		$Gia = mysqli_real_escape_string($dbc, trim($_POST['gia']));
	} else {
		$errors[] = 'Nhập giá không đúng quy định vui lòng nhập lại ';
	}



	if (empty($errors)) { 

		
		$q = "INSERT INTO laptop (Ma_laptop,Ten_laptop,Trong_luong,Ma_hang,Ma_loai,Cau_hinh,Hinh,Gia) VALUES('$mamay','$tenlaptop','$trongluong','$hangsx','$tenloai','$cauhinh','$hinh', '$Gia')";
		$r = @mysqli_query($dbc, $q);
		if ($r) { 


			// Print a message:
			echo '<p class="success-message">Đã thêm loại máy thành công!</p>';


			echo '<a href="index.php" class="button-link" >Trở về trang chủ</a>';
			
			echo'<a href="themsp.php" class="button-link">Thêm tiếp sản phẩm</a>';

		} else { 

			// Public message:
			echo '<h1>System Error</h1>
			<p class="error">You could not be registered due to a system error. We apologize for any inconvenience.</p>';

			// Debugging message:
			echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $q . '</p>';
		} // End of if ($r) IF.

		mysqli_close($dbc); 

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

	mysqli_close($dbc); 

} 
?>

<p class='header-title'>THÊM VÀO SẢN PHẨM</p>
<form action="themsp.php" method="post">
	<table>
		<tr hidden>
			<td>
				Mã laptop:
			</td>
			<td><input type="text" name="mamay" size="10" maxlength="10" value="<?php if (isset($_POST['mamay'])) echo $_POST['mamay']; ?>" /></td>
		</tr>
		<tr>
			<td>
				Tên laptop:
			</td>
			<td>
				<input type="text" name="tenlaptop" size="15" maxlength="100" value="<?php if (isset($_POST['tenlaptop'])) echo $_POST['tenlaptop']; ?>" />
			</td>
		</tr>
		<tr>
			<td>
				Trọng lượng:
			</td>
			<td><input type="text" name="trongluong" size="15" value="<?php if (isset($_POST['trongluong'])) echo $_POST['trongluong']; ?>" /></td>
		</tr>

<tr>
			<td>
				Hãng sản xuất:
			</td>
			<td>
				<select name="hangsx">
					<?php
					require('Connect_Database.php');

					$sql = "Select * FROM hang_laptop";
					$result = mysqli_query($dbc, $sql);
					if (mysqli_num_rows($result) <> 0) {
						while ($row = mysqli_fetch_array($result)) {
						echo"	<option value='$row[Ma_hang]'>$row[Ten_hang]</option>";
						}
					}
					?>
				</select>
			</td>
		</tr>




		<tr>
			<td>
				Loại máy:
			</td>
			<td>
			<select name="loaimay">
			<?php
					$sql = "Select * FROM loai_may";
					$result = mysqli_query($dbc, $sql);
					if (mysqli_num_rows($result) <> 0) {
						while ($row = mysqli_fetch_array($result)) {
							echo"<option value='$row[Ma_loai]'>$row[Ten_loai]</option>";
						}
					}
					mysqli_close($dbc);
	
			?>
			</select>
			</td>
		</tr>



		<tr>
			<td>
				Cấu hình:
			</td>
			<td>
				<input type="text" name="cauhinh" size="15" value="<?php if (isset($_POST['cauhinh'])) echo $_POST['cauhinh']; ?>" />
			</td>
		</tr>

		<tr>
			<td>
				Hình:
			</td>
			<td>
				<input type="file" name="hinh" size="15" maxlength="200" value="<?php if (isset($_POST['hinh'])) echo $_POST['hinh']; ?>" />
			</td>
		</tr>
		<tr>
			<td>
				Giá:
			</td>
			<td>
				<input type="text" name="gia" size="15" maxlength="15" value="<?php if (isset($_POST['gia'])) echo $_POST['gia']; ?>" />
			</td>
		</tr>


		<tr>
			<td colspan="2" align="center">
				<input type="submit" name="submit" value="THÊM LOẠI MÁY" />
			</td>
			
		</tr>
	</table>
<a href="index.php" class="button-link">Trở về trang chủ</a>  

</form>
<?php include('includes/footer.html'); ?>