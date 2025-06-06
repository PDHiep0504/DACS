<?php

require_once 'app/models/ReviewModel.php';
require_once 'app/models/OrderModel.php';

class ReviewController
{
    private $reviewModel;
    private $orderModel;

    public function __construct()
    {
        $this->reviewModel = new ReviewModel();
        $this->orderModel = new OrderModel();
    }

    public function reviews()
    {
        // Lấy ID tài khoản từ Session
        $accountId = SessionHelper::getAccountId();

        if (!$accountId) {
            header("Location: /webbanhang/account/login");
            exit;
        }

        // Tạo đối tượng của mô hình OrderModel
        $reviewModel = new ReviewModel();

        // Lấy sản phẩm đã nhận nhưng chưa đánh giá
        $unreviewed = $reviewModel->getUnreviewedReceivedItems($accountId);

        // Lấy sản phẩm đã nhận và đã đánh giá
        $reviewed = $reviewModel->getReviewedReceivedItems($accountId);

        // Trả dữ liệu cho view
        include 'app/views/review/reviews.php';
    }


    public function save()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $order_detail_id = $_POST['order_detail_id'] ?? null;
            $rating_value = (int) ($_POST['rating'] ?? 0);
            $comment = trim($_POST['content'] ?? '');

            if (!$order_detail_id || $rating_value < 1 || $rating_value > 5 || $comment === '') {
                echo "Dữ liệu đánh giá không hợp lệ!";
                exit;
            }

            try {
                // Lưu đánh giá vào cơ sở dữ liệu
                $this->reviewModel->saveReview($order_detail_id, $rating_value, $comment);

                // Sau khi đánh giá xong, quay lại trang đánh giá
                header("Location: /webbanhang/review/reviews");
                exit;
            } catch (Exception $e) {
                echo "Lỗi khi lưu đánh giá: " . $e->getMessage();
            }
        } else {
            echo "Phương thức không hợp lệ!";
        }
    }



}
