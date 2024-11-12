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

    <form action="Suasp.php" method="POST">
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
                    switch ($rows['Ma_hang']) {
                        case 'MH01':
                            echo 'Acer';
                            break;
                        case 'MH02':
                            echo 'Macbook';
                            break;
                        case 'MH03':
                            echo 'Asus';
                            break;
                        case 'MH04':
                            echo 'MSI';
                            break;
                    }
                    echo '<br><b>Loại máy: </b>';
                    switch ($rows['Ma_loai']) {
                        case 'ML01':
                            echo 'Văn phòng';
                            break;
                        case 'ML02':
                            echo 'Gamming';
                            break;
                        case 'ML03':
                            echo 'Doanh nhân';
                            break;
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