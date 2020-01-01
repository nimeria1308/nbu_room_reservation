<?php
class MyView {
    protected $template_dir = 'templates/';
    protected $vars = array();

    public function __construct($template_dir = null) {
        if ($template_dir !== null) {
            $this->template_dir = $template_dir;
        }
    }

    public function render($template_file) {
        require $this->template_dir.$template_file;
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
