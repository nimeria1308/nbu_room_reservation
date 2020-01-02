<?php
class MyView {
    const TEMPLATE_DIR = 'templates/';
    protected $vars = [];
    protected $template_file;

    public function __construct($template_file, $vars = null) {
        $this->template_file = $template_file;
        if (is_array($vars)) {
            $this->vars = $vars;
        }
    }

    public function render() {
        require self::TEMPLATE_DIR.$this->template_file;
    }

    public function __set($name, $value) {
        $this->vars[$name] = $value;
    }

    public function __get($name) {
        if (isset($this->vars[$name])) {
            return $this->vars[$name];
        } else {
            return null;
        }
    }
}
?>
