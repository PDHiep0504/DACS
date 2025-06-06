<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Sizes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <style>
        #menuButton { position: absolute; top: 45px; left: 60px; z-index: 1020; }
        .offcanvas { z-index: 1045; }
        body.offcanvas-backdrop::before { content: ""; position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background-color: rgba(0,0,0,0.4); z-index: 1040; }
        .container { background-color: #f9f9f9; border-radius: 10px; padding: 20px; }
        .btn { font-weight: bold; transition: background-color 0.3s ease; }
        .btn-warning:hover { background-color: #f39c12; }
        .btn-danger:hover { background-color: #c0392b; }
        .btn-success:hover { background-color: #27ae60; }
        .d-flex .btn { margin-left: 10px; }
    </style>
</head>
<body>
    <!-- Nút menu -->
    <button class="btn btn-outline-primary" type="button" id="menuButton" data-bs-toggle="offcanvas" data-bs-target="#offcanvasMenu" aria-controls="offcanvasMenu">
        <i class="bi bi-list"></i> Menu
    </button>
    <!-- Menu bên trái (đồng nhất với index.html) -->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasMenu" aria-labelledby="offcanvasMenuLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasMenuLabel">Menu</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <div class="list-group">
                <a class="list-group-item list-group-item-action active bg-primary text-white" data-bs-toggle="collapse" href="#submenuAdmin" role="button" aria-expanded="false" aria-controls="submenuAdmin">
                    Trang quản lý <i class="bi bi-caret-down-fill float-end"></i>
                </a>
                <div class="collapse submenu" id="submenuAdmin">
                    <a href="/webbanhang/admin/" class="list-group-item list-group-item-action">Bảng Điều Khiển Quản Trị</a>
                    <a href="/webbanhang/admin/products" class="list-group-item list-group-item-action">QL Sản phẩm</a>
                    <a href="/webbanhang/admin/categories" class="list-group-item list-group-item-action">QL Danh mục</a>
                    <a href="/webbanhang/admin/sizes" class="list-group-item list-group-item-action">QL Sizes</a>
                    <a href="/webbanhang/admin/orders" class="list-group-item list-group-item-action">QL Đơn hàng</a>
                    <a href="/webbanhang/admin/accounts" class="list-group-item list-group-item-action">QL Người dùng</a>
                    <a href="/webbanhang/admin/reviews" class="list-group-item list-group-item-action">QL Đánh giá</a>
                </div>
                <a href="/webbanhang" class="list-group-item list-group-item-action bg-primary text-white">Trang chủ</a>
            </div>
        </div>
    </div>
    <div class="container mt-5">
        <h2 class="text-center mb-4 text-primary">Danh Sách Sizes</h2>
        <div class="mb-3 d-flex justify-content-end">
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addSizeModal"><i class="bi bi-plus-circle"></i> Thêm size</button>
        </div>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Size</th>
                    <th class="text-end" style="width:250px;">Chức năng</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($sizes)) { ?>
                    <tr><td colspan="3" class="text-center">Không có size nào</td></tr>
                <?php } else { foreach ($sizes as $size) { ?>
                    <tr>
                        <td><?= htmlspecialchars($size->id) ?></td>
                        <td><?= htmlspecialchars($size->size) ?></td>
                        <td class="text-end">
                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editSizeModal<?= $size->id ?>"><i class="bi bi-pencil"></i> Sửa</button>
                            <button class="btn btn-danger btn-sm ms-1" data-bs-toggle="modal" data-bs-target="#deleteSizeModal<?= $size->id ?>"><i class="bi bi-trash"></i> Xóa</button>
                        </td>
                    </tr>
                    <!-- Edit Modal -->
                    <div class="modal fade" id="editSizeModal<?= $size->id ?>" tabindex="-1" aria-labelledby="editSizeModalLabel<?= $size->id ?>" aria-hidden="true">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <form method="post" action="/webbanhang/admin/editSize">
                            <div class="modal-header">
                              <h5 class="modal-title" id="editSizeModalLabel<?= $size->id ?>">Sửa Size</h5>
                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                              <input type="hidden" name="id" value="<?= htmlspecialchars($size->id) ?>">
                              <div class="mb-3">
                                <label for="sizeEdit<?= $size->id ?>" class="form-label">Tên size</label>
                                <input type="text" class="form-control" id="sizeEdit<?= $size->id ?>" name="size" value="<?= htmlspecialchars($size->size) ?>" required>
                              </div>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                              <button type="submit" class="btn btn-primary">Lưu</button>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
                    <!-- Delete Modal -->
                    <div class="modal fade" id="deleteSizeModal<?= $size->id ?>" tabindex="-1" aria-labelledby="deleteSizeModalLabel<?= $size->id ?>" aria-hidden="true">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <form method="post" action="/webbanhang/admin/deleteSize">
                            <div class="modal-header">
                              <h5 class="modal-title" id="deleteSizeModalLabel<?= $size->id ?>">Xóa Size</h5>
                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                              <input type="hidden" name="id" value="<?= htmlspecialchars($size->id) ?>">
                              Bạn có chắc chắn muốn xóa size <strong><?= htmlspecialchars($size->size) ?></strong> không?
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                              <button type="submit" class="btn btn-danger">Xóa</button>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
                <?php }} ?>
            </tbody>
        </table>
    </div>
    <!-- Add Modal -->
    <div class="modal fade" id="addSizeModal" tabindex="-1" aria-labelledby="addSizeModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <form method="post" action="/webbanhang/admin/addSize">
            <div class="modal-header">
              <h5 class="modal-title" id="addSizeModalLabel">Thêm Size</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="mb-3">
                <label for="sizeAdd" class="form-label">Tên size</label>
                <input type="text" class="form-control" id="sizeAdd" name="size" required>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
              <button type="submit" class="btn btn-success">Thêm</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
