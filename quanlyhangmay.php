<?php
$page_title = 'Quản lý hãng laptop';
include('includes/header.html');
?>

<?php
// Kết nối cơ sở dữ liệu
$conn = mysqli_connect('localhost', 'root', '', 'qlbanlap');
if (!$conn) {
    die('Không thể kết nối: ' . mysqli_connect_error());
}


$sql = "SELECT * FROM hang_laptop";
$result = mysqli_query($conn, $sql);


echo "<p class='header-title'>Quản lý hãng máy</p>";
echo "<div style='text-align: right;'>";
        echo "<a href='themhangmay.php' class='btn-sm btn-primary'><i class='fa fa-eye'></i>Thêm hãng máy</a>";
        echo "</div>";

session_start();
if (isset($_SESSION['success_message'])) {
  echo '<p class="success-message">' . $_SESSION['success_message'] . '</p>';
  // Xóa thông báo sau khi hiển thị để tránh hiển thị lại khi refresh trang
  unset($_SESSION['success_message']);
}


if (mysqli_num_rows($result) > 0) {
    echo "<table style='width: 120%'>";
    echo "<tr align='center'>";
    echo "<td><b>Mã hãng</b></td>";
    echo "<td><b>Tên hãng</b></td>";
    echo "<td><b>Nước sản xuất</b></td>";
    echo "<td><b>Email</b></td>";
    echo "<td><b>Logo</b></td>";
    echo "<td><b>Chức năng</b></td>";
    echo "</tr>";
    while ($row = mysqli_fetch_array($result)) {
        echo "<tr>";
        echo "<td >{$row['Ma_hang']}</td>";
        echo "<td>{$row['Ten_hang']}</td>";
        echo "<td>{$row['Nuoc_sx']}</td>";
        echo "<td>{$row['Email']}</td>";
        echo "<td>";
        echo "<img src='img/{$row['Logo']}' width='150px'";    
        echo "</td>";
        echo "<td>";
        echo "<a href='suahangmay.php?Mahang={$row['Ma_hang']}' class='btn-sm btn-info'> <i class='fa fa-edit'></i> Cập nhập </a>&nbsp;&nbsp;";
        echo "<a href='xoahangmay.php' class='btn-sm btn-danger'> <i class='fa fa-trash'></i> Xóa </a>";
        echo "</td>";
    }
    echo "</table>";
}



mysqli_close($conn);
?>
    <a href="index.php" class="button-link">Quay Lại</a>
<?php include('includes/footer.html'); ?>
<body>
</body>