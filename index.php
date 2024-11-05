<?php # Script 3.4 - index.php
$page_title = 'Trang chủ';
include ('includes/header.html');

?>

<br>
        <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
            <ol class="carousel-indicators">
                <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
            </ol>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img class="d-block w-100" src="img/banner4.png" alt="First slide">
                </div>
                <div class="carousel-item">
                    <img class="d-block w-100" src="img/banner2.png" alt="Second slide">
                </div>
                <div class="carousel-item">
                    <img class="d-block w-100" src="img/banner3.png" alt="Third slide">
                </div>
            </div>
            <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
        <br>

        <ul class="list-group list-group-horizontal">
          <li class="list-group-item"> <img src="img/msi.png"><br> <b>Laptop MSI</b> </li>
          <li class="list-group-item"><img src="img/acer.png"><br> <b>Laptop ACER</b> </li>
          <li class="list-group-item"><img src="img/apple.png"><br> <b>Laptop MACBOOK</b> </li>
          <li class="list-group-item"><img src="img/asus.png"><br> <b>Laptop MACBOOK</b> </li>

        </ul>
<br>

      <div class="card-deck">
  <div class="card">
    <img src="img/gammingqc.png" class="card-img-top" alt="">
    <div class="card-body">
     <h6 class="card-title">Laptop Gamming</h6>
      
    </div>
  </div>
  <div class="card">
    <img src="img/hoctapqc.png" class="card-img-top" alt="...">
    <div class="card-body">
      <h6 class="card-title">Học tập-Văn phòng</h6>
      
    </div>
  </div>
  <div class="card">
    <img src="img/doanhnhanqc.png" class="card-img-top" alt="...">
    <div class="card-body">
      <h6 class="card-title">Doanh nhân</h6>
     
    </div>
  </div>
</div>


<?php


  // Ket noi CSDL

//require("connect.php");

$conn = mysqli_connect ('localhost', 'root', '', 'qlbanlap') 

        OR die ('Could not connect to MySQL: ' . mysqli_connect_error() );

$sql = '
    SELECT Ma_laptop,Ten_laptop,Hinh,Ma_hang,Ma_loai,Trong_luong,Gia,Cau_hinh from laptop
    
';

$result = mysqli_query($conn, $sql);

echo "<p class='header-title'>DANH MỤC SẢN PHẨM</p>";  


 echo "<table align='center' width='770' border='1' cellpadding='2' cellspacing='2' style='border-collapse:collapse'>";
    // Đếm số sản phẩm trên mỗi hàng
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

              
        echo "</td>";
        
        $counter++;
        
        // Tạo hàng mới sau mỗi 5 sản phẩm
        if ($counter % 4 == 0) {
            echo "</tr><tr>";
        }
    }
    echo "</tr>"; // Đóng hàng cuối cùng
}
echo"</table>";

?>



<?php
include ('includes/footer.html');
?>