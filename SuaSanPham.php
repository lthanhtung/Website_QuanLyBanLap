<?php 
$page_title = 'Sua laptop';
include('includes/header.html');
?>

<p class='header-title'>CẬP NHẬT SẢN PHẨM</p>


  <?php	


  // Ket noi CSDL

  //require("connect.php");

  $conn = mysqli_connect('localhost', 'root', '', 'qlbanlap')

    or die('Could not connect to MySQL: ' . mysqli_connect_error());

  $sql = 'SELECT Ma_laptop, Ten_laptop, Trong_luong,Ten_hang, Ten_loai, Cau_hinh,Hinh,Gia
FROM laptop, hang_laptop,loai_may
WHERE laptop.Ma_hang = hang_laptop.Ma_hang and laptop.Ma_loai = loai_may.Ma_loai';

  $result = mysqli_query($conn, $sql);


 echo "<table align='center' width='770' border='1' cellpadding='2' cellspacing='2' style='border-collapse:collapse'>";
$counter = 0;

session_start();

if (isset($_SESSION['success_message'])) {
  echo '<p class="success-message">' . $_SESSION['success_message'] . '</p>';
  // Xóa thông báo sau khi hiển thị để tránh hiển thị lại khi refresh trang
  unset($_SESSION['success_message']);
}

if (mysqli_num_rows($result) <> 0) {

    echo "<tr>"; // Bắt đầu hàng đầu tiên
    while ($rows = mysqli_fetch_array($result)) {
        echo "<td align='center' width='20%'>";

        echo "<img src='img/{$rows['Hinh']}' width='150' height='150'>";

        echo "<p><b>{$rows['Ten_laptop']}</b></p>";  
        echo "<p><b>{$rows['Cau_hinh']}</b></p>";  
        echo "<p class='price'>
                {$rows['Gia']}<span>đ</span>
              </p>";
             // Nút cập nhật sản phẩm
        echo "<a href='Suasp.php?mamay={$rows[0]}' class='delete-button'>Sửa Sản Phẩm</a>";

       

        
        echo "</td>";

        $counter++;

        // Tạo hàng mới sau mỗi 4 sản phẩm
        if ($counter % 4 == 0) {
            echo "</tr><tr>";
        }
    }
    echo "</tr>"; 
}
echo "</table>";

mysqli_close($conn);

  ?>
<a href="index.php" class="button-link">Trở về trang chủ</a>  
<?php include('includes/footer.html'); ?>
