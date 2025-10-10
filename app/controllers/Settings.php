<?php
/**
 * Settings Controller - User Settings
 */

class Settings extends Controller {
    private $userModel;

    public function __construct() {
        $this->requireLogin();
        $this->userModel = $this->model('User');
    }

    /**
     * Settings page
     */
    public function index() {
        $data = [
            'title' => 'Cài đặt'
        ];

        $this->view('settings/index', $data);
    }

    /**
     * Change password
     */
    public function changePassword() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['user_id'];
            $currentPassword = $_POST['current_password'] ?? '';
            $newPassword = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            // Get user
            $user = $this->userModel->findById($userId);

            // Validate
            if (!$this->userModel->verifyPassword($currentPassword, $user['password'])) {
                flash('error', 'Mật khẩu hiện tại không đúng!', 'danger');
            } elseif ($newPassword !== $confirmPassword) {
                flash('error', 'Mật khẩu mới không khớp!', 'danger');
            } elseif (strlen($newPassword) < 6) {
                flash('error', 'Mật khẩu phải có ít nhất 6 ký tự!', 'danger');
            } else {
                // Change password
                if ($this->userModel->changePassword($userId, $newPassword)) {
                    flash('success', 'Đổi mật khẩu thành công!', 'success');
                } else {
                    flash('error', 'Có lỗi xảy ra!', 'danger');
                }
            }
        }

        redirect('settings');
    }
}
