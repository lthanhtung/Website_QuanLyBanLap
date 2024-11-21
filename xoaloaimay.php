<?php
$page_title = 'Xoa san pham';
include ('includes/header.html');

?>
<?php

// Kết nối CSDL
$conn = mysqli_connect ('localhost', 'root', '', 'qlbanlap') 
    OR die ('Could not connect to MySQL: ' . mysqli_connect_error());


// Xử lý yêu cầu xóa sản phẩm
if (isset($_GET['delete'])) {
    $maloai = $_GET['delete'];

    // Xóa mã loại trong trong bảng loai_may
       $sql_delete_loai = "DELETE FROM loai_may WHERE Ma_loai = '$maloai'";

    $result_loai = mysqli_query($conn, $sql_delete_loai);

     if ($result_loai && mysqli_affected_rows($conn) > 0) {
        echo '<p  class="success-message" >Xóa hãng thành công thành công.</p>';
    }
}

// Truy vấn để lấy lại danh sách sản phẩm sau khi xóa
$sql = 'SELECT * FROM loai_may';
$result = mysqli_query($conn, $sql);

 echo "<p class='header-title'>Xóa loại máy</p>";

   
        if (mysqli_num_rows($result) > 0) {

            echo "<table>";
            echo "<tr>";
            echo "<td> <b> Mã loại </b> </td>";
            echo "<td> <b> Tên loại </b> </td>";
            echo "<td> <b> Thao tác </b> </td>";


            echo "</tr>";
            while ($row = mysqli_fetch_array($result)) {
             echo "<tr>";

                echo "<td>{$row['Ma_loai']}</td>";
                echo "<td>{$row['Ten_loai']}</td>";
                echo "<td>";

                echo "<a href='?delete={$row['Ma_loai']}' class='delete-button' onclick=\"confirmDelete(event, '{$row['Ma_loai']}')\"><i class='fa fa-trash'></i> Xóa</a>";

                echo "</td>";
                echo "</tr>";
            }

        }
          echo "</table>";
    

mysqli_close($conn);
?>

<a href="quanlyloaimay.php" class="button-link">Trở về</a> 

<?php include('includes/footer.html'); ?>



</body>

