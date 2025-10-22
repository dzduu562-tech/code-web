<?php
namespace Controllers;

class ApiController extends BaseController
{
    public function notifications(): void
    {
        header('Content-Type: application/json');
        if (!\Core\Auth::check()) { echo json_encode(['unread'=>0,'since'=>date('c')]); return; }
        $st = $this->db->prepare('SELECT COUNT(*) FROM notifications WHERE user_id=? AND is_read=0');
        $st->execute([\Core\Auth::id()]);
        $unread = (int)$st->fetchColumn();
        echo json_encode(['unread'=>$unread,'since'=>date('c')]);
    }
}
