<?php
class Controller {
    /**
     * Render a view inside a layout.
     * $layout = 'admin' | 'giangvien' | 'sinhvien' | 'print' | '' (no layout)
     */
    protected function render(string $view, array $data = [], string $layout = ''): void {
        extract($data);
        $viewFile = ROOT . '/app/views/' . $view . '.php';
        if ($layout) {
            require ROOT . '/app/views/layouts/' . $layout . '.php';
        } else {
            require $viewFile;
        }
    }

    protected function redirect(string $url): void {
        header('Location: ' . $url);
        exit;
    }
}
