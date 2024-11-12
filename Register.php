<?php
$page_title = 'Đăng ký người dùng';
include('includes/registerheader.html');
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
    $sql = 'SELECT Ma_user FROM user WHERE 1';

    //Kết quả truy vấn
    $result = mysqli_query($dbc, $sql);


    //Kiểm tra trùng lặp mã người dùng
    if (mysqli_num_rows($result) <> 0) {
        while ($rows = mysqli_fetch_array($result)) {
            if ($_POST['Manguoidung'] == $rows['Ma_user']) {
                $errors[] = 'Mã người dùng đã tồn tại. Vui lòng nhập lại!';
            } else {
                $Manguoidung = mysqli_real_escape_string($dbc, trim($_POST['Manguoidung']));
            }
        }
    }

    // Kiểm tra Tên đăng nhập
    if (empty($_POST['Ten_dang_nhap'])) {
        $errors[] = 'Tên tài khoản không thể để trống. Vui lòng nhập!';

    }
    else{

     //Kiểm tra trùng lặp tên đăng nhập
        $sql = 'SELECT Ten_dang_nhap FROM user WHERE 1';
        $result = mysqli_query($dbc, $sql);

    if (mysqli_num_rows($result) <> 0) {
        while ($rows = mysqli_fetch_array($result)) {
            if ($_POST['Ten_dang_nhap'] == $rows['Ten_dang_nhap']) {
                $errors[] = 'Tên đăng nhập đã tồn tại. Vui lòng nhập lại!';
            } else {
                $Tendangnhap = mysqli_real_escape_string($dbc, trim($_POST['Ten_dang_nhap']));
            }

        }
    }
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
            $p = "INSERT INTO user (Ma_user,Ten_dang_nhap,Mat_khau,Quyen)
            VALUES('$Manguoidung','$Tendangnhap','$password','$Loainguoidung')";
            $s = @mysqli_query($dbc, $p); 

            if ($s) { // If it ran OK.
                // Print a message:
                echo '<p class="success-message">Đăng kí tài khoản thành công !</p>';
    
    
                echo '<a href="index.php" class="button-link" >Trở về trang đăng nhập</a>';
                
                echo'<a href="Register.php" class="button-link">Đăng kí</a>';
    
            }
            mysqli_close($dbc);
            exit();
        }

    else { 

         $p = "INSERT INTO user (Ma_user,Ten_dang_nhap,Mat_khau,Quyen)
            VALUES('$Manguoidung','$Tendangnhap','$password','$Loainguoidung')";
            $s = @mysqli_query($dbc, $p);

             if ($s) { // If it ran OK.
                // Print a message:
                echo '<p class="success-message">Đăng kí tài khoản thành công !</p>';
    
    
                echo '<a href="index.php" class="button-link" >Trở về trang đăng nhập</a>';
                
                echo'<a href="Register.php" class="button-link">Đăng kí</a>';
    
            }
               mysqli_close($dbc);
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
<p class='header-title'>Đăng kí tài khoản</p>


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
                <input type="text" name="Ten_dang_nhap" size="15" maxlength="100" value="<?php if (isset($_POST['Ten_dang_nhap'])) echo $_POST['Ten_dang_nhap']; ?>" />
            </td>
     
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
        
        <tr>
            <td colspan="2" align="center">
                <input type="submit" name="submit" value="ĐĂNG KÝ" />
            </td>

        </tr>
    </table>


<a href="index.php" class="button-link">Trở về trang chủ</a>  




