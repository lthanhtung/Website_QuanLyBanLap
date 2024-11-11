<?php
session_start();

// Giả sử $userID được lưu trong session sau khi đăng nhập
$userID = $_SESSION['user_id'];

// Kết nối tới cơ sở dữ liệu
$conn = mysqli_connect('localhost', 'root', '', 'qlbanlap');

if (!$conn) {
    die("Không thể kết nối: " . mysqli_connect_error());
}

// Truy vấn thông tin loại người dùng
$sql = "
SELECT khach_hang.Ho_Khach_Hang, khach_hang.Ten_khach_hang, 
       nhanvien.HoNV, nhanvien.TenNV, 
       user.Quyen
FROM user
LEFT JOIN khach_hang ON user.MaUser = khach_hang.Ma_khach_hang
LEFT JOIN nhanvien ON user.MaUser = nhanvien.MaNV
WHERE user.MaUser = '$userID'";

$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $quyen = $row['Quyen'];

    if ($quyen === 'Khách hàng') {
        $ho_khach_hang = $row['Ho_Khach_Hang'];
        $ten_khach_hang = $row['Ten_khach_hang'];
        echo "Xin Chào, Khách hàng: $ho_khach_hang $ten_khach_hang!";
    } elseif ($quyen === 'Nhân viên') {
        $ho_nhan_vien = $row['HoNV'];
        $ten_nhan_vien = $row['TenNV'];
        echo "Xin Chào, Nhân viên: $ho_nhan_vien $ten_nhan_vien!";
    } 
}


// Đóng kết nối
mysqli_close($conn);
?>
