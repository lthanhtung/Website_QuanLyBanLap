
<?php 
session_start(); 

// Thiết lập thời gian đăng xuất tự động
$thoigiandangxuat = 10000;

if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $thoigiandangxuat) {
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit;
}
$_SESSION['last_activity'] = time(); // Cập nhật thời gian hoạt động cuối cùng

$error_message = ""; // Biến để lưu thông báo lỗi

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
 if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Kết nối cơ sở dữ liệu
        $conn = mysqli_connect('localhost', 'root', '', 'qlbanlap');
        if (!$conn) {
            die('Không thể kết nối: ' . mysqli_connect_error());
        }

        $sql = "SELECT * FROM user WHERE Ten_dang_nhap='$username' AND Mat_khau='$password'";
        $result = mysqli_query($conn, $sql);

        // Kiểm tra kết quả truy vấn
        if (mysqli_num_rows($result) === 1) {
            $row = mysqli_fetch_assoc($result); // Lấy dòng kết quả
            if ($row['Quyen'] === 'admin') { // Kiểm tra xem người dùng có phải là admin
                $_SESSION['loggedin'] = true;
                $_SESSION['username'] = $username;
                $_SESSION['user_id'] = $row['Ma_user'];
                $_SESSION['last_activity'] = time(); // Khởi tạo thời gian hoạt động đầu tiên

                header("Location: index.php"); // Chuyển hướng lại trang index sau khi đăng nhập
                exit;


                 } else {
                //echo "<p>Chỉ tài khoản admin mới có quyền truy cập quyền truy cập vào trang này.</p>";
                $_SESSION['user_id'] = $row['Ma_user'];
                header("Location: loginSuccess_Nhanvien_Khachhang.php");
            }




        } else {
            $error_message = "Sai tên đăng nhập hoặc mật khẩu!";
        }

        mysqli_close($conn);
    }
    
 echo '
    <!DOCTYPE html>
    <html lang="vi">
    <head>
        <meta charset="UTF-8">
        <title>Trang Đăng nhập</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f0f0f5;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
                flex-direction: column;
            }
            #login-form {
                width: 350px;
                padding: 20px;
                background-color: #fff;
                box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
                border-radius: 8px;
                text-align: center;
            }
            #login-title {
                font-size: 24px;
                margin-bottom: 10px;
                color: #d08989;
                font-family: cursive;
            }
            #login-form p {
                font-size: 14px;
                color: #333;
                margin: 10px 0;
                text-align: left;
            }
            #login-form input[type="text"],
            #login-form input[type="password"] {
                width: 90%;
                padding: 10px;
                margin: 5px 0 15px;
                border: 1px solid #ddd;
                border-radius: 5px;
                font-size: 14px;
            }
            #dangnhap {
                width: 100%;
                padding: 10px;
                background-color: #8d99c8;
                color: #fff;
                font-size: 16px;
                font-weight: bold;
                border: none;
                border-radius: 50px;
                cursor: pointer;
            }
            #dangnhap:hover {
                background-color: wheat;
            }
             #dangky {
                width: 100%;
                padding: 10px;
                background-color: #f683a4;
                color: #fff;
                font-size: 16px;
                font-weight: bold;
                border: none;
                border-radius: 50px;
                cursor: pointer;
            }
            #dangky:hover {
                background-color: wheat;
            }

            .error-message {
                color: #d9534f; /* Đỏ nhạt */
                font-size: 14px;
                margin-top: 10px;
                padding: 10px;
                background-color: #f8d7da;
                border: 1px solid #f5c2c7;
                border-radius: 5px;
                text-align: center;
            }
        </style>
    </head>
    <body>
        <form id="login-form" action="" method="post">
            <h2 id="login-title">ĐĂNG NHẬP</h2>
            <p>Tên đăng nhập:</p>
            <input id="tendangnhap" type="text" name="username">
            <p>Mật khẩu:</p>
            <input id="matkhau" type="password" name="password" >
            <br>
            <input id="dangnhap" type="submit" name="login" value="Đăng nhập"> <br>
            <input id="dangky" type="submit" name="register" value="Đăng ký">

            '; 

            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
        header("Location: Register.php"); // Chuyển qua trang index sau ấn đăn ký
        exit;
    }

    // Hiển thị thông báo lỗi nếu có
    if (!empty($error_message)) {
        echo "<div class='error-message'>$error_message</div>";
    }

    echo '</form></body></html>';



} else {
?>




<?php

    $page_title = 'Trang chủ';
    include ('includes/header.html');
    $conn = mysqli_connect('localhost', 'root', '', 'qlbanlap');

//xử lý hiện tên khách hàng 
    $userID = $_SESSION['user_id'];
    $sql = "
    SELECT nhan_vien.Ho_nv , nhan_vien.Ten_nv
    FROM nhan_vien
    JOIN user ON nhan_vien.Ma_nv = user.Ma_user
    WHERE user.Ma_user = '$userID'";
    $result = mysqli_query($conn, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        $ho_nhan_vien = $row['Ho_nv'];
        $ten_nhan_vien = $row['Ten_nv'];
        echo "Xin Chào, Admin: $ho_nhan_vien $ten_nhan_vien!";
    }


        //Xử lý đăng xuất

    if (isset($_GET['logout']) && $_GET['logout'] == 'true') {
        session_destroy(); // Hủy session
        header("Location: index.php"); // Chuyển hướng về trang index
        exit;
    }
?>



<br>
        <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
            <ol class="carousel-indicators">
                <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
            </ol>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img class="d-block w-100" src="img/banner3.png" alt="First slide">
                </div>
                <div class="carousel-item">
                    <img class="d-block w-100" src="img/banner2.png" alt="Second slide">
                </div>
                <div class="carousel-item">
                    <img class="d-block w-100" src="img/banner4.png" alt="Third slide">
                </div>
            </div>
            <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
        <br>


        <ul class="list-group list-group-horizontal">
          <li class="list-group-item"> <img src="img/msi.png"><br> <b>Laptop MSI</b> </li>
          <li class="list-group-item"><img src="img/acer.png"><br> <b>Laptop ACER</b> </li>
          <li class="list-group-item"><img src="img/apple.png"><br> <b>Laptop MACBOOK</b> </li>
          <li class="list-group-item"><img src="img/asus.png"><br> <b>Laptop MACBOOK</b> </li>

        </ul>
<br>

      <div class="card-deck">
  <div class="card">
    <img src="img/gammingqc.png" class="card-img-top" alt="">
    <div class="card-body">
     <h6 class="card-title">Laptop Gamming</h6>
      
    </div>
  </div>
  <div class="card">
    <img src="img/hoctapqc.png" class="card-img-top" alt="...">
    <div class="card-body">
      <h6 class="card-title">Học tập-Văn phòng</h6>
      
    </div>
  </div>
  <div class="card">
    <img src="img/doanhnhanqc.png" class="card-img-top" alt="...">
    <div class="card-body">
      <h6 class="card-title">Doanh nhân</h6>
     
    </div>
  </div>
</div>


<?php


  // Ket noi CSDL

//require("connect.php");

$conn = mysqli_connect ('localhost', 'root', '', 'qlbanlap') 

        OR die ('Could not connect to MySQL: ' . mysqli_connect_error() );

$sql = '
    SELECT Ma_laptop,Ten_laptop,Hinh,Ma_hang,Ma_loai,Trong_luong,Gia,Cau_hinh from laptop
    
';

$result = mysqli_query($conn, $sql);

echo "<p class='header-title'>DANH MỤC SẢN PHẨM</p>";  


 echo "<table align='center' width='770' border='1' cellpadding='2' cellspacing='2' style='border-collapse:collapse'>";
    // Đếm số sản phẩm trên mỗi hàng
        $counter = 0;


 if (mysqli_num_rows($result) <> 0) {

    echo "<tr>"; // Bắt đầu hàng đầu tiên
    while ($rows = mysqli_fetch_array($result)) {
        echo "<td align='center' width='20%'>";

        echo "<img src='img/{$rows['Hinh']}' width='150' height='150'>";

        echo "<p><b>{$rows['Ten_laptop']}</b></p>";  
        echo "<p><b>{$rows['Cau_hinh']}</b></p>"; 
         
       echo "<p class='price'>{$rows['Gia']}<span>đ</span></p>";

          echo "<a href='ThongtinSanpham.php?mamay={$rows[0]}' class='delete-button'>Xem chi tiết</a>";



              
        echo "</td>";
        
        $counter++;
        
        // Tạo hàng mới sau mỗi 4 sản phẩm
        if ($counter % 4 == 0) {
            echo "</tr><tr>";
        }
    }
    echo "</tr>"; // Đóng hàng cuối cùng
}
echo"</table>";

?>



<?php
include ('includes/footer.html');
}
?>
