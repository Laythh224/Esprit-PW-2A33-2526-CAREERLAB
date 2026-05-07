<?php

class BaseController {
    /**
     * Renders a view file and passes data to it.
     */
    protected function render(string $viewPath, array $data = []): void {
        // Extract data to make it available as variables in the view
        extract($data);

        // Standard layout includes
        $viewFile = __DIR__ . '/../views/' . $viewPath . '.php';

        if (file_exists($viewFile)) {
            // Layout parts
            $header = __DIR__ . '/../views/layout/header.php';
            $navbar = __DIR__ . '/../views/layout/navbar.php';
            $footer = __DIR__ . '/../views/layout/footer.php';

            if (file_exists($header)) include $header;
            if (file_exists($navbar)) include $navbar;
            
            include $viewFile;
            
            if (file_exists($footer)) include $footer;
        } else {
            die("View file not found: $viewPath");
        }
    }

    protected function clean(string $data): string {
        return htmlspecialchars(strip_tags(trim($data)));
    }

    protected function redirect(string $action, array $params = []): void {
        $url = "index.php?action=" . $action;
        foreach ($params as $key => $value) {
            $url .= "&" . urlencode($key) . "=" . urlencode($value);
        }
        header("Location: " . $url);
        exit();
    }
}
