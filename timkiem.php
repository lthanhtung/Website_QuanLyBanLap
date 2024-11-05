<?php

$page_title = 'Thêm laptop';
include('includes/header.html');


// Kết nối CSDL 
$conn = mysqli_connect('localhost', 'root', '', 'qlbanlap') 
    OR die('Could not connect to MySQL: ' . mysqli_connect_error()); 

// Truy vấn để lấy danh sách cho combobox "Tên loại máy"
$sql_loai = "SELECT Ten_loai FROM loai_may";
$result_loai = mysqli_query($conn, $sql_loai);
if (!$result_loai) {
    die("Lỗi truy vấn 'loai_may': " . mysqli_error($conn));
}

// Truy vấn để lấy danh sách cho combobox "Tên hãng"
$sql_hang = "SELECT Ten_hang FROM hang_laptop";
$result_hang = mysqli_query($conn, $sql_hang);
if (!$result_hang) {
    die("Lỗi truy vấn 'hang_laptop': " . mysqli_error($conn));
}
?>
<p class='header-title'>TÌM KIẾM SẢN PHẨM</p>

<!-- Form tìm kiếm --> 
<form action="" method="get" style="text-align:center; margin-bottom: 20px;"> 
    <input type="text" name="name" placeholder="Nhập tên laptop..." value="<?php echo isset($_GET['name']) ? htmlspecialchars($_GET['name']) : ''; ?>" /> 

    <!-- Combobox Tên loại máy -->
    <select name="ten_loai">
        <option value="">Chọn loại máy</option>
        <?php
        // Thêm các tùy chọn vào combobox "Tên loại máy"
        while ($row_loai = mysqli_fetch_assoc($result_loai)) {
            $selected = (isset($_GET['ten_loai']) && $_GET['ten_loai'] === $row_loai['Ten_loai']) ? 'selected' : '';
            echo "<option value=\"{$row_loai['Ten_loai']}\" $selected>{$row_loai['Ten_loai']}</option>";
        }
        ?>
    </select>

    <!-- Combobox Tên hãng -->
    <select name="ten_hang">
        <option value="">Chọn hãng</option>
        <?php
        // Thêm các tùy chọn vào combobox "Tên hãng"
        while ($row_hang = mysqli_fetch_assoc($result_hang)) {
            $selected = (isset($_GET['ten_hang']) && $_GET['ten_hang'] === $row_hang['Ten_hang']) ? 'selected' : '';
            echo "<option value=\"{$row_hang['Ten_hang']}\" $selected>{$row_hang['Ten_hang']}</option>";
        }
        ?>
    </select>
    <input type="submit" name="search" value="Tìm kiếm" class="search-button" />


            
        </tr> 
</form> 

<?php
// Phần xử lý tìm kiếm và hiển thị kết quả
$search_name = ''; 
$search_ten_loai = '';
$search_ten_hang = '';
$results = []; // Mảng để lưu kết quả tìm kiếm 

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['search'])) { 
    $search_name = mysqli_real_escape_string($conn, trim($_GET['name'])); 
    $search_ten_loai = mysqli_real_escape_string($conn, trim($_GET['ten_loai'])); 
    $search_ten_hang = mysqli_real_escape_string($conn, trim($_GET['ten_hang'])); 

    // Kiểm tra nếu không có giá trị nào được nhập
    if (empty($search_name) && empty($search_ten_loai) && empty($search_ten_hang)) {
        echo "<p class='error'>Vui lòng nhập ít nhất một tiêu chí tìm kiếm.</p>";
    } else {
        // Truy vấn tìm kiếm 
        $sql = "SELECT l.Ten_laptop, l.Cau_hinh, l.Hinh, l.Gia 
                FROM laptop l 
                JOIN loai_may lm ON l.Ma_loai = lm.Ma_loai 
                JOIN hang_laptop hl ON l.Ma_hang = hl.Ma_hang 
                WHERE (l.Ten_laptop LIKE '%$search_name%')
                AND (lm.Ten_loai LIKE '%$search_ten_loai%' OR '$search_ten_loai' = '')
                AND (hl.Ten_hang LIKE '%$search_ten_hang%' OR '$search_ten_hang' = '')"; 

        $result = mysqli_query($conn, $sql); 

        if ($result) {
            if (mysqli_num_rows($result) > 0) { 
                $results = $result; // Lưu kết quả tìm kiếm 
                $count = mysqli_num_rows($result);
                echo "<p class='success-message'>Sản phẩm được tìm thấy: $count</p>";  
            } else { 
                echo "<p class='error'>Không tìm thấy sản phẩm này.</p>"; 
            } 
        } else {
            echo "Lỗi truy vấn: " . mysqli_error($conn);
        }
    }
} 

// Hiển thị danh sách sản phẩm 
if (!empty($results)) { 
    echo "<table align='center' width='770' border='1' cellpadding='2' cellspacing='2' style='border-collapse:collapse'>"; 
    echo "<tr>"; // Bắt đầu hàng đầu tiên 

    while ($rows = mysqli_fetch_array($results)) { 
        echo "<td align='center' width='20%'>"; 
        echo "<img src='img/{$rows['Hinh']}' width='150' height='150'>"; 
        echo "<p><b>{$rows['Ten_laptop']}</b></p>";  
        echo "<p><b>{$rows['Cau_hinh']}</b></p>";  
        echo "<p class='price'>{$rows['Gia']}<span>đ</span></p>"; 
        echo "</td>"; 
    } 

    echo "</tr>"; // Đóng hàng cuối cùng 
    echo "</table>"; 
} 

// Đóng kết nối
mysqli_close($conn); 
?>
<a href="index.php" class="button-link">Trở về trang chủ</a>  

