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
SELECT khach_hang.Ho_khach_hang, khach_hang.Ten_khach_hang, 
       nhan_vien.Ho_nv, nhan_vien.Ten_nv, 
       user.Quyen
FROM user
LEFT JOIN khach_hang ON user.Ma_user = khach_hang.Ma_khach_hang
LEFT JOIN nhan_vien ON user.Ma_user = nhan_vien.Ma_nv
WHERE user.Ma_user = '$userID'";

$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $quyen = $row['Quyen'];

    if ($quyen === 'Khách hàng') {
        $ho_khach_hang = $row['Ho_khach_hang'];
        $ten_khach_hang = $row['Ten_khach_hang'];
        echo "Xin Chào, Khách hàng: $ho_khach_hang $ten_khach_hang!";
    } elseif ($quyen === 'Nhân viên') {
        $ho_nhan_vien = $row['Ho_nv'];
        $ten_nhan_vien = $row['Ten_nv'];
        echo "Xin Chào, Nhân viên: $ho_nhan_vien $ten_nhan_vien!";
    } 
}

// Đóng kết nối
mysqli_close($conn);
?>
