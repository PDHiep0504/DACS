<?php include 'app/views/shares/header.php'; ?>

<style>
    body {
        background: #f8f9fa;
        font-family: 'Poppins', sans-serif;
    }

    .container {
        margin-top: 50px;
        margin-bottom: 50px; /* Thêm khoảng cách dưới để tránh footer */
    }

    h1 {
        font-size: 2.5rem;
        font-weight: bold;
        color: #333;
        text-align: center;
        margin-bottom: 20px;
    }

    .intro-text {
        font-size: 1.2rem;
        line-height: 1.6;
        color: #555;
    }

    .intro-section {
        margin-top: 30px;
    }

    .intro-section p {
        font-size: 1.1rem;
        color: #444;
    }

    .button-wrapper {
        text-align: left;
        margin-top: 30px;
        margin-bottom: 50px; /* Thêm khoảng cách dưới */
    }

    .btn-custom {
        background-color: #007bff;
        color: white;
        padding: 12px 25px;
        border-radius: 30px;
        text-decoration: none;
        font-weight: bold;
    }

    .btn-custom:hover {
        background-color: #0056b3;
    }

    .about-section {
        margin-top: 40px;
    }

    .about-section h2 {
        font-size: 2rem;
        color: #333;
        font-weight: bold;
    }

    .about-section p {
        font-size: 1.1rem;
        line-height: 1.7;
        color: #555;
    }

    .vision-section h2 {
        font-size: 2rem;
        color: #333;
        font-weight: bold;
        margin-top: 40px;
    }

    .vision-section p {
        font-size: 1.1rem;
        line-height: 1.7;
        color: #555;
    }
</style>

<div class="container">
    <h1>Giới thiệu về Website</h1>
    <div class="intro-text">
        <p>
            Chào mừng bạn đến với website của chúng tôi, nơi cung cấp những sản phẩm thể thao chất lượng cao
            với giá cả hợp lý. Chúng tôi chuyên cung cấp các mặt hàng thể thao, bao gồm áo bóng đá, giày thể thao,
            và các thiết bị thể thao khác. Mục tiêu của chúng tôi là mang đến cho bạn những sản phẩm tốt nhất
            với trải nghiệm mua sắm tuyệt vời nhất.
        </p>
        <p>
            Chúng tôi luôn đảm bảo rằng mỗi sản phẩm đều được kiểm tra kỹ lưỡng về chất lượng trước khi đến tay khách hàng.
            Ngoài ra, dịch vụ chăm sóc khách hàng của chúng tôi sẵn sàng hỗ trợ bạn 24/7 để đảm bảo sự hài lòng tuyệt đối.
        </p>
    </div>

    <div class="about-section">
        <h2>Về Chúng Tôi</h2>
        <p>
            Chúng tôi hiểu rằng việc chọn lựa sản phẩm thể thao chất lượng là rất quan trọng đối với mỗi người.
            Với đội ngũ chuyên nghiệp và đam mê thể thao, chúng tôi cung cấp các sản phẩm từ các thương hiệu uy tín
            và luôn cam kết chất lượng. Chúng tôi không ngừng cải tiến và mở rộng danh mục sản phẩm để phục vụ nhu cầu
            của khách hàng. Mục tiêu của chúng tôi là không chỉ cung cấp sản phẩm, mà còn mang lại giá trị thực sự
            cho những người đam mê thể thao trên khắp đất nước.
        </p>
    </div>

    <div class="vision-section">
        <h2>Sứ Mệnh và Tầm Nhìn</h2>
        <p>
            Chúng tôi cam kết không chỉ cung cấp sản phẩm chất lượng mà còn giúp nâng cao phong trào thể thao và sức khỏe cộng đồng.
            Sứ mệnh của chúng tôi là mang lại cho mọi người, mọi lứa tuổi, cơ hội tiếp cận với các sản phẩm thể thao chất lượng,
            giúp cải thiện sức khỏe và tinh thần. Tầm nhìn của chúng tôi là trở thành một trong những nhà cung cấp hàng đầu
            trong lĩnh vực thể thao, không ngừng phát triển và nâng cao chất lượng sản phẩm, dịch vụ.
        </p>
    </div>

    <div class="button-wrapper">
        <a href="/webbanhang/product" class="btn-custom">Quay lại trang chủ</a>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>
