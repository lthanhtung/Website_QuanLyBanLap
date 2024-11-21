<?php
$page_title = 'Thong tin san pham';
include('includes/header.html');
?>

<?php
require('Connect_Database.php'); // Connect to the db.
//Lấy mamay cần hiển thị
$id = $_GET['mamay'];


//Truy vấn Thông tin về Laptop có mamay = $mamay
$sql = "Select * FROM laptop Where Ma_laptop = '$id'";

$result = mysqli_query($dbc, $sql);
$rows = mysqli_fetch_array($result);
?>

<body>
    <p class='header-title'>XEM CHI TIẾT SẢN PHẨM</p>

    <form action="" method="POST">
        <table>
            <tr>
                <td colspan="2" class="success-message"><?php echo $rows['Ten_laptop']  ?> </td>
            </tr>
            <tr style="font-size: 20px;">
                <td style="width: 100px;">
                    <?php
                    echo "<img src='img/{$rows['Hinh']}' width='200'";
                    ?>
                </td>
                <td style="text-align: left;">
                    <?php
                    echo '<b>Cấu hình:</b><br>' . $rows['Cau_hinh'];
                    echo '<br> <b>Hãng sản xuất: </b>';

                    $sql_hang = "SELECT * FROM hang_laptop WHERE Ma_hang = '$rows[Ma_hang]'";

                    $result_hang = mysqli_query($dbc, $sql_hang);
                    if (mysqli_num_rows($result_hang) <> 0) {
                        while ($row_hang = mysqli_fetch_array($result_hang)) {
                        echo"$row_hang[Ten_hang]";
                        }
                    }

                    echo '<br><b>Loại máy: </b>';
                    $sql_loaimay = "SELECT * FROM loai_may WHERE Ma_loai ='$rows[Ma_loai]'";
                    $result_loaimay = mysqli_query($dbc, $sql_loaimay);
                    if (mysqli_num_rows($result_loaimay) <> 0) {
                        while ($row_loaimay = mysqli_fetch_array($result_loaimay)) {
                            echo "$row_loaimay[Ten_loai]";
                        }
                    }

                    echo '<div style="text-align: right;"><b>Trọng lượng: </b>' . $rows['Trong_luong'] . 'kg - <b>Giá: </b>' . $rows['Gia'] . ' đ' . '</div>';

                    ?>
                </td>
            </tr>
           
        </table>
        <a href="index.php" class="button-link">Trở về trang chủ</a>
    </form>
    <?php include('includes/footer.html'); ?>
</body>

</html>