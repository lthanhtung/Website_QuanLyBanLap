<?php
$page_title = 'Sửa thông tin laptop';
include('includes/header.html');
?>

<?php

// Kết nối cơ sở dữ liệu
$conn = mysqli_connect('localhost', 'root', '', 'qlbanlap');
if (!$conn) {
    die('Không thể kết nối: ' . mysqli_connect_error());
}
//hiển thị bang
        $sql = "
         SELECT Ma_loai , Ten_loai
        FROM loai_may 
         ";
        $result = mysqli_query($conn, $sql);
        echo "<br>";
        


        echo "<p class='header-title'>Quản lý loại máy</p>";
        echo "<div style='text-align: right;'>";
        echo "<a href='themloaimay.php' class='btn-sm btn-primary'><i class='fa fa-eye'></i>Thêm loại máy</a>";
        echo "</div>";

   
        if (mysqli_num_rows($result) > 0) {
            echo "<table>";
            echo "<tr>";
            echo "<td> <b> Mã loại </b> </td>";
            echo "<td> <b> Tên loại </b> </td>";
            echo "<td> <b> Thao tác </b> </td>";


            echo "</tr>";
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<td>{$row['Ma_loai']}</td>";
                echo "<td>{$row['Ten_loai']}</td>";
                echo "<td>";
                echo "<a href='sualoaimay.php?maloai={$row['Ma_loai']}' class='btn-sm btn-info'> <i class='fa fa-edit'></i> Chỉnh sửa </a>&nbsp;&nbsp;";   
                echo "<a href='xoaloaimay.php' class='btn-sm btn-danger'><i class='fa fa-trash'></i> Xóa </a>";
                echo "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } 

mysqli_close($conn);
?>
<a href="index.php" class="button-link">Trở về trang chủ</a>  
<?php include('includes/footer.html'); ?>