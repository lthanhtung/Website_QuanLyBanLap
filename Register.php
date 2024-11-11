<?php
$page_title = 'Đăng ký người dùng';
include('includes/header.html');
?>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require('Connect_Database.php'); // Connect to the db.
    $errors = array(); // Initialize an error array.

    // Kiểm tra Mã người dùng
    if (empty($_POST['Manguoidung'])) {
        $errors[] = 'Bạn chưa nhập mã người dùng!';
    }
    //Truy van mã người dùng
    $sql = 'SELECT MaUser FROM user WHERE 1';
    //Kết quả truy vấn
    $result = mysqli_query($dbc, $sql);

    //Kiểm tra trùng lặp mã người dùng
    if (mysqli_num_rows($result) <> 0) {
        while ($rows = mysqli_fetch_array($result)) {
            if ($_POST['Manguoidung'] == $rows['MaUser']) {
                $errors[] = 'Người dùng đã tồn tại. Vui lòng nhập lại!';
            } else {
                $Manguoidung = mysqli_real_escape_string($dbc, trim($_POST['Manguoidung']));
            }
        }
    }

    // Kiểm tra Tên đăng nhập
    if (empty($_POST['Tendangnhap'])) {
        $errors[] = 'Tên tài khoảng không thể để trống. Vui lòng nhập!';
    } else {
        $Tendangnhap = mysqli_real_escape_string($dbc, trim($_POST['Tendangnhap']));
    }
    // Kiểm tra Mật khẩu
    if (empty($_POST['password'])) {
        $errors[] = 'Mật khẩu không thể để trống. Vui lòng nhập!';
    }elseif (strlen($_POST['password']) < 6) {
        $errors[] = 'Mật khẩu phải có ít nhất 6 ký tự. Vui lòng nhập lại!';
    }
    //Xử lý nhập lại mật khẩu
    if (empty($_POST['passwordAgain'])) {
        $errors[] = 'Vui lòng nhập lại mật khẩu';
    }
    elseif($_POST['passwordAgain'] == $_POST['password']){
        $password = mysqli_real_escape_string($dbc, trim($_POST['password']));
    }
    else{
        $errors[] = 'Nhập lại mật khẩu không đúng. Vui lòng kiểm tra lại!!';
    }

/*
    // Kiểm tra Họ người dùng
    if (empty($_POST['Honguoidung'])) {
        $errors[] = 'Bạn chưa nhập Họ. Vui lòng nhập!';
    } else {
        $Honguoidung = mysqli_real_escape_string($dbc, trim($_POST['Honguoidung']));
    }
    // Kiểm tra Tên người dùng
    if (empty($_POST['Tennguoidung'])) {
        $errors[] = 'Bạn chưa nhập Tên. Vui lòng nhập!';
    } else {
        $Tennguoidung = mysqli_real_escape_string($dbc, trim($_POST['Tennguoidung']));
    }


    // Xử lý Giới tính
    if (isset($_POST['gioitinh'])) {
        if ($_POST['gioitinh'] == 'nam') {
            $_POST['gioitinh'] = 0;
            $Gioitinh = mysqli_real_escape_string($dbc, $_POST['gioitinh']);
        } else {
            $_POST['gioitinh'] = 1;
            $Gioitinh = mysqli_real_escape_string($dbc, $_POST['gioitinh']);
        }
    }
    // Kiểm tra Địa chỉ
    if (empty($_POST['diachi'])) {
        $errors[] = 'Bạn chưa nhập Địa chỉ. Vui lòng nhập!';
    } else {
        $diachi = mysqli_real_escape_string($dbc, trim($_POST['diachi']));
    }

    // Kiểm tra Số điện thoại
    if (empty($_POST['sdt'])) {
        $errors[] = 'Bạn chưa nhập Số điện thoại. Vui lòng nhập!';
    } elseif (is_numeric($_POST['sdt'])) {
        $sdt = mysqli_real_escape_string($dbc, trim($_POST['sdt']));
    } else {
        $errors[] = 'Nhập số điện thoại không đúng vui lòng nhập lại';
    }

    // Kiểm tra Email
    if (strpos($_POST['Email'], '@') === false) {
        $errors[] = 'Email không đúng. Vui lòng nhập lại!';
    } else {
        $Email = mysqli_real_escape_string($dbc, trim($_POST['Email']));
    }
*/
    // Xử lý loại người dùng
if (isset($_POST['Manguoidung'])) {
    $maNguoiDung = $_POST['Manguoidung'];

    // Kiểm tra mã người dùng để xác định loại
    if (strpos($maNguoiDung, 'KH') === 0) {
        $Loainguoidung = 'Khách hàng';
    } elseif (strpos($maNguoiDung, 'NV') === 0) {
        $Loainguoidung = 'Nhân viên';
    } else {
        $errors[] = 'Mã người dùng không hợp lệ. Vui lòng nhập lại!';
    }
    if (!isset($errors)) {
        $Loainguoidung = mysqli_real_escape_string($dbc, trim($Loainguoidung));
    }
}

    if (empty($errors)) {
        // Đăng ký người dùng vào database
        if ($Loainguoidung == 'Khách hàng') {
            /*
            //Tạo insert đến bảng khách hàng
            $q = "INSERT INTO khach_hang (Ma_khach_hang,Ho_Khach_Hang,Ten_khach_hang,Gioi_tinh,Dia_chi,Dien_thoai,Email)
            VALUES('$Manguoidung','$Honguoidung','$Tennguoidung','$Gioitinh','$diachi','$sdt', '$Email')";
            $r = @mysqli_query($dbc, $q); // Run the query.
            */
            //Tạo insert đến bảng User
            $p = "INSERT INTO user (MaUser,TenDangNhap,password,Quyen)
            VALUES('$Manguoidung','$Tendangnhap','$password','$Loainguoidung')";
            $s = @mysqli_query($dbc, $p); // Run the query.
            if ($s) { // If it ran OK.
                // Print a message:
                echo '<p class="success-message">Chào mừng bạn đã đến với trang web. Hãy trở về trang chủ để đăng nhập</p>';
    
    
                echo '<a href="index.php" class="button-link" >Trở về trang đăng nhập</a>';
                
                //echo'<a href="themsp.php" class="button-link">Thêm tiếp sản phẩm</a>';
    
            }else { // If it did not run OK.
    
                // Public message:
                echo '<h1>Lỗi</h1>
                <p class="error">Bạn không thể được đăng ký do lỗi. Chúng tôi xin lỗi vì sự bất tiện này..</p>';
    
                // Debugging message:
                echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $q . '</p>';
            } // End of if ($r) IF.
            mysqli_close($dbc); // Close the database connection.
            // Include the footer and quit the script:
            include('includes/footer.html');
            exit();
        }
        else{
            /*
            //Tạo insert đến bảng nhân viên
            $q = "INSERT INTO nhanvien (MaNV,HoNV,TenNV,gioiTinh,DiaChi,Sodienthoai,Email)
            VALUES('$Manguoidung','$Honguoidung','$Tennguoidung','$Gioitinh','$diachi','$sdt', '$Email')";
            $r = @mysqli_query($dbc, $q); // Run the query.
            */
            //Tạo insert đến bảng User
            $p = "INSERT INTO user (MaUser,TenDangNhap,password,Quyen)
            VALUES('$Manguoidung','$Tendangnhap','$password','$Loainguoidung')";
            $s = @mysqli_query($dbc, $p); // Run the query.
            if ($s) { // If it ran OK.
                // Print a message:
                echo '<p class="success-message">Chào mừng bạn đã đến với trang web. Hãy trở về trang chủ để đăng nhập</p>';
    
    
                echo '<a href="index.php" class="button-link" >Trở về trang đăng nhập</a>';
                
                //echo'<a href="themsp.php" class="button-link"></a>';
    
            }
            else { // If it did not run OK.
    
                // Public message:
                echo '<h1>Lỗi</h1>
                <p class="error">Bạn không thể được đăng ký do lỗi. Chúng tôi xin lỗi vì sự bất tiện này..</p>';
    
                // Debugging message:
                echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $q . '</p>';
            } // End of if ($r) IF.
            mysqli_close($dbc); // Close the database connection.
            // Include the footer and quit the script:
            include('includes/footer.html');
            exit();
        }
    }
    else { // Report the errors.

		echo '
		<p class="error">Thông tin các lỗi:<br />';
		foreach ($errors as $msg) { // Print each error.
			echo " - $msg<br />\n";
		}
		echo '<p><b>Please try again.</b></p>';
	} // End of if (empty($errors)) IF.

	mysqli_close($dbc); // Close the database connection.
//    print_r($errors);
}






?>


<form action="Register.php" method="post" enctype="multipart/form-data">
    <table>
        <tr>
            <td>
                Mã người dùng:
            </td>
            <td><input type="text" name="Manguoidung" size="10" maxlength="10" value="<?php if (isset($_POST['Manguoidung'])) echo $_POST['Manguoidung']; ?>" /></td>
        </tr>

        <tr>
            <td>
                Tên đăng nhập:
            </td>
            <td>
                <input type="text" name="Tendangnhap" size="15" maxlength="100" value="<?php if (isset($_POST['Tendangnhap'])) echo $_POST['Tendangnhap']; ?>" />
            </td>
        </tr>
        <tr>
            <td>
               Mật khẩu:
            </td>
            <td><input style="width: 600px;" type="password" name="password" size="15" value="<?php if (isset($_POST['password'])) echo $_POST['password']; ?>" /></td>
        </tr>
        <tr>
            <td>
               Nhập lại mật khẩu:
            </td>
            <td><input style="width: 600px;" type="password" name="passwordAgain" size="15" value="<?php if (isset($_POST['passwordAgain'])) echo $_POST['passwordAgain']; ?>" /></td>
        </tr>
<!--
        <tr>
            <td>
                Họ người dùng:
            </td>
            <td>
                <input type="text" name="Honguoidung" size="15" maxlength="100" value="<?php if (isset($_POST['Honguoidung'])) echo $_POST['Honguoidung']; ?>" />
            </td>
        </tr>
        <tr>
            <td>
                Tên người dùng:
            </td>
            <td><input type="text" name="Tennguoidung" size="15" value="<?php if (isset($_POST['Tennguoidung'])) echo $_POST['Tennguoidung']; ?>" /></td>
        </tr>
        <tr>
            <td>
                Giới tính:
            </td>
            <td>
                <input type="radio" name="gioitinh" value="nam" <?php if (isset($_POST['gioitinh']) && $_POST['gioitinh'] == 'nam') echo 'checked="checked"'; ?> checked /> Nam
                <input type="radio" name="gioitinh" value="nu" <?php if (isset($_POST['gioitinh']) && $_POST['gioitinh'] == 'nu') echo 'checked="checked"'; ?> /> Nữ
            </td>
        </tr>
        <tr>
            <td>
                Địa chỉ:
            </td>
            <td>
                <input type="text" name="diachi" size="15" value="<?php if (isset($_POST['diachi'])) echo $_POST['diachi']; ?>" />
            </td>
        </tr>

        <tr>
            <td>
                Số điện thoại:
            </td>
            <td>
                <input type="text" name="sdt" size="15" value="<?php if (isset($_POST['sdt'])) echo $_POST['sdt']; ?>" />
            </td>
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
                Loại người dùng:
            </td>
            <td>
                <input type="radio" name="loainguoidung" value="khachhang" <?php if (isset($_POST['loainguoidung']) && $_POST['loainguoidung'] == 'khachhang') echo 'checked="checked"'; ?> checked /> Khách hàng
                <input type="radio" name="loainguoidung" value="nhanvien" <?php if (isset($_POST['loainguoidung']) && $_POST['loainguoidung'] == 'nhanvien') echo 'checked="checked"'; ?> /> Nhân viên
            </td>
        </tr>
-->        

        <tr>
            <td colspan="2" align="center">
                <input type="submit" name="submit" value="ĐĂNG KÝ" />
            </td>

        </tr>
    </table>
    <a href="index.php" class="button-link">Trở lại</a>






    <?php include('includes/footer.html'); ?>