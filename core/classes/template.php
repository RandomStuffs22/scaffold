<?php !defined('IN_APP') and header('location: /');

class Template {

    private $_routes;
    public static $vars = array();

    public function __construct() {
        $this->_routes = new Routes;
        
        //  Set some default variables to use in the template
        $this->set(array(
            'load_time' => load_time(),
            'base' => PUBLIC_PATH,
            
            'scaffold_version' => scaffold_version()
        ));
    }

    public function render($what = '') {
        //  Set the view to load
        if(empty($what)) {
            $what = $this->_routes->parse();
        }
        
        //  Set the view variable
        if(file_exists(APP_BASE . 'views/' . $what . '.php')) {
            self::$vars['view'] = grab(APP_BASE . 'views/' . $what . '.php');
        } else {
            //  If it doesn't exist, show the error view
            self::$vars['view'] = Config::get('404_page');
        }
        
        //  And load the main template
        $template = grab(TEMPLATE_PATH, load_vars());
                
        return $this->parse($template);
    }
    
    public function parse($template) {
        //  Parse the template
        $template = preg_replace_callback('/{{([a-zA-Z0-9_]+)(\/[a-zA-Z0-9 \.,+\-_\/!\?]+)?}}/', function($matches) {
            //  Load all the available template variables
            $vars = load_vars();
            
            //  Discard the first match, and check for fallbacks
            $matches = explode('/', last($matches));
            
            //  There will always be a first key
            $match = first($matches);
            
            //  Set the fallback value, if it exists
            $fallback = isset($matches[1]) ? $matches[1] : '';
            
            //  Return matching variables, if they exist
            //  AND are not null or empty-ish
            if(isset($vars[$match]) and $vars[$match]) {
                return $vars[$match];
            }
            
            //  Try a fallback
            return $fallback;
        }, $template);
        
        return $template;
    }
    
    public function set($key, $val = '') {
        if(is_array($key)) {
            foreach($key as $k => $v) {
                $this->set($k, $v);
            }
            
            return;
        }
        
        self::$vars[$key] = $val;
    }
    
    public function get($key, $fallback = '') {
        if(isset(self::$vars[$key])) {
            return self::$vars[$key];
        }
        
        return $fallback;
    }
    
    public static function vars() {
        return self::$vars;
    }
}

function load_vars() {
    return Template::vars();
}