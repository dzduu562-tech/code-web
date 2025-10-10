<?php
/**
 * Profile Controller - User Profile Management
 */

class Profile extends Controller {
    private $userModel;

    public function __construct() {
        $this->requireLogin();
        $this->userModel = $this->model('User');
    }

    /**
     * View profile
     */
    public function index() {
        $userId = $_SESSION['user_id'];
        $user = $this->userModel->getUserWithProfile($userId);

        if (!$user) {
            $user = [
                'id' => $userId,
                'full_name' => $_SESSION['user_name'] ?? '',
                'email' => $_SESSION['user_email'] ?? '',
                'role' => $_SESSION['role'] ?? '',
                'avatar' => $_SESSION['avatar'] ?? '',
                'phone' => '',
                'address' => '',
                'profile' => []
            ];
        }

        $data = [
            'title' => 'Hồ sơ cá nhân',
            'user' => $user
        ];

        $this->view('profile/index', $data);
    }

    /**
     * Edit profile
     */
    public function edit() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->updateProfile();
        }

        redirect('profile');
    }

    /**
     * Update profile
     */
    private function updateProfile() {
        $userId = $_SESSION['user_id'];
        
        $data = [
            'full_name' => clean($_POST['full_name'] ?? ''),
            'phone' => clean($_POST['phone'] ?? ''),
            'address' => clean($_POST['address'] ?? '')
        ];

        if ($this->userModel->updateProfile($userId, $data)) {
            $_SESSION['user_name'] = $data['full_name'];
            flash('success', 'Cập nhật hồ sơ thành công!', 'success');
        } else {
            flash('error', 'Có lỗi xảy ra!', 'danger');
        }

        redirect('profile');
    }
}
