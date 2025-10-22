<?php require_once APP . '/views/layouts/header.php'; ?>
<?php require_once APP . '/views/layouts/navbar.php'; ?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-3 col-lg-2 mb-4">
            <?php require_once APP . '/views/student/sidebar.php'; ?>
        </div>

        <div class="col-md-9 col-lg-10">
            <div class="mb-4">
                <h2 class="fw-bold mb-1">Huy hiệu & Thành tích 🏆</h2>
                <p class="text-muted">Level <?= $user['profile']['level'] ?? 1 ?> - <?= number_format($user['profile']['total_xp'] ?? 0) ?> XP</p>
            </div>

            <!-- XP Progress -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Tiến độ Level</h5>
                        <span class="badge bg-primary fs-6">Level <?= $user['profile']['level'] ?? 1 ?></span>
                    </div>
                    <div class="progress" style="height: 25px;">
                        <?php 
                        $currentLevel = $user['profile']['level'] ?? 1;
                        $currentXP = $user['profile']['total_xp'] ?? 0;
                        $nextLevelXP = $currentLevel * 1000; // 1000 XP per level
                        $progressPercent = min(100, ($currentXP % 1000) / 10);
                        ?>
                        <div class="progress-bar" style="width: <?= $progressPercent ?>%">
                            <?= $currentXP ?> / <?= $nextLevelXP ?> XP
                        </div>
                    </div>
                </div>
            </div>

            <!-- Earned Badges -->
            <div class="mb-5">
                <h4 class="fw-bold mb-3">Huy hiệu đã đạt được (<?= count($earned_badges) ?>)</h4>
                <?php if (empty($earned_badges)): ?>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> Bạn chưa có huy hiệu nào. Hãy hoàn thành các nhiệm vụ để nhận huy hiệu!
                    </div>
                <?php else: ?>
                    <div class="row g-4">
                        <?php foreach ($earned_badges as $badge): ?>
                            <div class="col-md-3 col-6">
                                <div class="card border-0 shadow-sm text-center h-100">
                                    <div class="card-body">
                                        <div class="mb-3" style="font-size: 3rem;">
                                            <?= $badge['icon'] ?>
                                        </div>
                                        <h6 class="fw-bold"><?= e($badge['name']) ?></h6>
                                        <p class="text-muted small mb-2"><?= e($badge['description']) ?></p>
                                        <small class="text-muted">
                                            <i class="bi bi-calendar"></i>
                                            <?= formatDate($badge['earned_at']) ?>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Available Badges -->
            <div>
                <h4 class="fw-bold mb-3">Huy hiệu có thể đạt được</h4>
                <?php if (empty($available_badges)): ?>
                    <p class="text-muted">Bạn đã mở khóa tất cả huy hiệu! 🎉</p>
                <?php else: ?>
                    <div class="row g-4">
                        <?php foreach ($available_badges as $badge): ?>
                            <div class="col-md-3 col-6">
                                <div class="card border-0 shadow-sm text-center h-100 opacity-75">
                                    <div class="card-body">
                                        <div class="mb-3 grayscale" style="font-size: 3rem; filter: grayscale(100%);">
                                            <?= $badge['icon'] ?>
                                        </div>
                                        <h6 class="fw-bold"><?= e($badge['name']) ?></h6>
                                        <p class="text-muted small mb-2"><?= e($badge['description']) ?></p>
                                        <div class="badge bg-secondary">
                                            Cần <?= number_format($badge['xp_required']) ?> XP
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once APP . '/views/layouts/footer.php'; ?>
