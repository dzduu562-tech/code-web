<?php
class BaseController {
    protected function render(string $view, array $params = [], string $layout = 'main'): string {
        extract($params, EXTR_SKIP);
        ob_start();
        require __DIR__ . '/../views/' . $view . '.php';
        $content = ob_get_clean();
        ob_start();
        require __DIR__ . '/../views/layouts/' . $layout . '.php';
        return ob_get_clean();
    }
}
