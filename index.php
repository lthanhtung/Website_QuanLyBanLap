<?php
session_start(); // Bắt đầu session

// Thiết lập thoi gian
$thoigiandangxuat = 100;



// Kiểm tra và cập nhật thời gian hoạt động cuối cùng của người dùng
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $thoigiandangxuat) {
    // Nếu thời gian không hoạt động quá 15 phút, hủy phiên và chuyển hướng về trang đăng nhập
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit;
}
$_SESSION['last_activity'] = time(); // Cập nhật thời gian hoạt động cuối cùng



// Kiểm tra nếu người dùng đã đăng nhập
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo '
    <!DOCTYPE html>
    <html lang="vi">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Trang Đăng nhập</title>
        <!-- Nhúng file CSS -->
    <link rel="stylesheet" href="includes/style.css?v=1" type="text/css"/>
    </head>
    <body>
        <form id="login-form" action="" method="post">
            <h2 id="login-title">ĐĂNG NHẬP</h2>
            <br>
            <p> Tên đăng nhập:</p>
            <input type="text" name="username"> <br>
            <p> Mật khẩu:</p>
            <input id="matkhau" type="password" name="password">
            <br>
            <input id="dangnhap" type="submit" name="login" value="Đăng nhập">
            <input id="danhky" type="submit" name="register" value="Đăng ký">

        </form>';

    // Xử lý đăng nhập
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        if (empty($username) || empty($password)) {
            echo "<p>Vui lòng nhập tên đăng nhập và mật khẩu!</p>";
        }
        // Kết nối cơ sở dữ liệu
        $conn = mysqli_connect('localhost', 'root', '', 'qlbanlap');
        if (!$conn) {
            die('Không thể kết nối: ' . mysqli_connect_error());
        }

        // Truy vấn kiểm tra thông tin người dùng
        $sql = "SELECT * FROM user WHERE TenDangNhap='$username' AND password='$password'";
        $result = mysqli_query($conn, $sql);

        // Kiểm tra kết quả truy vấn
        if (mysqli_num_rows($result) === 1) {
            $row = mysqli_fetch_assoc($result); // Lấy dòng kết quả
            if ($row['Quyen'] === 'Admin') { // Kiểm tra xem người dùng có phải là admin
                $_SESSION['loggedin'] = true;
                $_SESSION['username'] = $username;
                $_SESSION['user_id'] = $row['MaUser'];
                $_SESSION['last_activity'] = time(); // Khởi tạo thời gian hoạt động đầu tiên

                header("Location: index.php"); // Chuyển hướng lại trang index sau khi đăng nhập
                exit;
            } else {
                //echo "<p>Chỉ tài khoản admin mới có quyền truy cập quyền truy cập vào trang này.</p>";
                $_SESSION['user_id'] = $row['MaUser'];
                header("Location: loginSuccess_Nhanvien_Khachhang.php");
            }
        } else {
            echo "<p>Sai tên đăng nhập hoặc mật khẩu!</p>";
        }
        mysqli_close($conn);
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
        header("Location: Register.php"); // Chuyển qua trang index sau ấn đăn ký
        exit;
    }
} else {
?>





    <?php
    $page_title = 'Trang chủ';
    include('includes/header.html');
    $conn = mysqli_connect('localhost', 'root', '', 'qlbanlap');

    $userID = $_SESSION['user_id'];
    $sql = "
SELECT nhanvien.HoNV, nhanvien.TenNV 
FROM nhanvien
JOIN user ON nhanvien.MaNV = user.MaUser
WHERE user.MaUser = '$userID'";
    $result = mysqli_query($conn, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $ho_nhan_vien = $row['HoNV'];
        $ten_nhan_vien = $row['TenNV'];
        echo "Xin Chào, Admin: $ho_nhan_vien $ten_nhan_vien!";
    }
    echo '<p><a href="index.php?logout=true">Đăng xuất</a></p>';


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

    $conn = mysqli_connect('localhost', 'root', '', 'qlbanlap')

        or die('Could not connect to MySQL: ' . mysqli_connect_error());

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
    echo "</table>";

    ?>



<?php
    include('includes/footer.html');
}
?>