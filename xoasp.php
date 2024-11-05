<?php # Script 3.4 - index.php
$page_title = 'Xoa san pham';
include ('includes/header.html');

?>
<p class='header-title'> XÓA SẢN PHẨM</p>

<?php

// Kết nối CSDL
$conn = mysqli_connect ('localhost', 'root', '', 'qlbanlap') 
    OR die ('Could not connect to MySQL: ' . mysqli_connect_error());


// Xử lý yêu cầu xóa sản phẩm
if (isset($_GET['delete'])) {
    $ma_laptop = $_GET['delete'];

    // Xóa các bản ghi liên quan trong ct_hoa_don trước
    $sql_delete_ct_hoa_don = "DELETE FROM ct_hoa_don WHERE Ma_laptop = '$ma_laptop'";
    $result_ct_hoa_don = mysqli_query($conn, $sql_delete_ct_hoa_don);

    if ($result_ct_hoa_don && mysqli_affected_rows($conn) > 0) {
        echo '<p class="success-message" >Xóa sản phẩm trong chi tiết hóa đơn thành công.</p>';
    }

    // Xóa sản phẩm trong bảng laptop
    $sql_delete_laptop = "DELETE FROM laptop WHERE Ma_laptop = '$ma_laptop'";
    $result_laptop = mysqli_query($conn, $sql_delete_laptop);

    if ($result_laptop && mysqli_affected_rows($conn) > 0) {
        echo '<p  class="success-message" >Xóa sản phẩm thành công.</p>';
    }
}

// Truy vấn để lấy lại danh sách sản phẩm sau khi xóa
$sql = 'SELECT Ma_laptop, Ten_laptop,Cau_hinh, Hinh, Trong_luong, Gia FROM laptop';
$result = mysqli_query($conn, $sql);

// Hiển thị danh sách sản phẩm

echo "<table align='center' width='770' border='1' cellpadding='2' cellspacing='2' style='border-collapse:collapse'>";
$counter = 0;



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
             // Nút xóa sản phẩm
        echo "<a href='?delete={$rows['Ma_laptop']}' class='delete-button' onclick=\"confirmDelete(event, '{$rows['Ma_laptop']}')\">Xóa sản phẩm</a>";


       

        
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




</body>

