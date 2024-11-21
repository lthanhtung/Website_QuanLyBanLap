<?php # Script 3.4 - index.php
$page_title = 'Xoa san pham';
include ('includes/header.html');

?>

<?php

// Kết nối CSDL
$conn = mysqli_connect ('localhost', 'root', '', 'qlbanlap') 
    OR die ('Could not connect to MySQL: ' . mysqli_connect_error());


// Xử lý yêu cầu xóa sản phẩm
if (isset($_GET['delete'])) {
   $mahang = $_GET['delete'];

    // Xóa trong bảng hang_laptop
    $sql_delete_hang = "DELETE FROM hang_laptop WHERE Ma_hang = '$mahang'";
    $result_hang = mysqli_query($conn, $sql_delete_hang);

    if ($result_hang && mysqli_affected_rows($conn) > 0) {
        echo '<p  class="success-message" >Xóa hãng máy thành công.</p>';
    }
}


// Hiển thị thông tin hãng sản xuất
$sql = "SELECT * FROM hang_laptop";
$result = mysqli_query($conn, $sql);



echo "<p class='header-title'>XÓA HÃNG MÁY</p>";
session_start();
if (isset($_SESSION['success_message'])) {
  echo '<p class="success-message">' . $_SESSION['success_message'] . '</p>';
  // Xóa thông báo sau khi hiển thị để tránh hiển thị lại khi refresh trang
  unset($_SESSION['success_message']);
}


if (mysqli_num_rows($result) > 0) {
    echo "<table>";
    echo "<tr align='center'>";
    echo "<td><b>Mã hãng</b></td>";
    echo "<td><b>Tên hãng</b></td>";
    echo "<td><b>Nước sản xuất</b></td>";
    echo "<td><b>Email</b></td>";
    echo "<td><b>Chức năng</b></td>";
    echo "</tr>";
    while ($row = mysqli_fetch_array($result)) {
        echo "<tr>";
        echo "<td >{$row['Ma_hang']}</td>";
        echo "<td>{$row['Ten_hang']}</td>";
        echo "<td>{$row['Nuoc_sx']}</td>";
        echo "<td>{$row['Email']}</td>";
        echo "<td>";
        echo "<a href='?delete={$row['Ma_hang']}' class='btn-sm btn-danger' onclick=\"confirmDelete(event,'{$row['Ma_hang']}')\"> <i class='fa fa-trash'></i> Xóa</a>";
        
    }
    echo "</table>";
}

mysqli_close($conn);
?>

<a href="quanlyhangmay.php" class="button-link">Trở về</a> 

<?php include('includes/footer.html'); ?>

<tr ></tr>
</body>