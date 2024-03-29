<?php
/**
 * CodeIgnighter layout support library
 *  with Twig like inheritance blocks
 *
 * v 1.0
 *
 *
 * @author Constantin Bosneaga
 * @email  constantin@bosneaga.com
 * @url    http://a32.me/
 */
 
if (!defined('BASEPATH')) exit('No direct script access allowed');
 
class Layout {
    private $obj;
    private $layout_view;
    private $title = '';
    private $css_list = array(), $js_list = array(), $meta_list = array(), $keyword_list = array();
    private $block_list, $block_new, $block_replace = false;
 
    function Layout() {
        $this->obj =& get_instance();
        $this->layout_view = "Layout/default.php";
        // Grab layout from called controller
        if (isset($this->obj->layout_view)) $this->layout_view = $this->obj->layout_view;
    }
 
    function view($view, $data = null, $return = false) {
        // Render template
        $data['content_for_layout'] = $this->obj->load->view($view, $data, true);
        $data['title_for_layout'] = $this->title;
 
        // Render resources
        $data['js_for_layout'] = '';
        foreach ($this->js_list as $v)
            $data['js_for_layout'] .= sprintf('<script type="text/javascript" src="%s"></script>', $v);
 
        $data['css_for_layout'] = '';
        foreach ($this->css_list as $v)
            $data['css_for_layout'] .= sprintf('<link rel="stylesheet" type="text/css"  href="%s" />', $v);

        $data['meta_for_layout'] = '';
        if($this->meta_list != null){
            foreach ($this->meta_list[0] as $v)
                $data['meta_for_layout'] .= sprintf('<meta %1$s="%2$s" content="%3$s" /> ', $v['type'], $v['key'], $v['content']);
        }

        $data['keywords_for_layout'] = '';
        if($this->keyword_list != null){
            $numItems = count($this->keyword_list);
            $i = 0;
            $data['keywords_for_layout'] = '<meta name="keywords" content="';

            foreach ($this->keyword_list as $v){
                if(++$i === $numItems) {
                   $data['keywords_for_layout'] .= sprintf('%1$s', $v);
                }
                else{
                    $data['keywords_for_layout'] .= sprintf('%1$s', $v).",";
                }
            }

            $data['keywords_for_layout'] .= '">';
        }
        
        // Render template
        $this->block_replace = true;
        $output = $this->obj->load->view($this->layout_view, $data, $return);
 
        return $output;
    }
 
    /**
     * Set page title
     *
     * @param $title
     */
    function title($title) {
        $this->title = $title;
    }
 
    /**
     * Adds Javascript resource to current page
     * @param $item
     */
    function js($item) {
        $this->js_list[] = $item;
    }
 
    /**
     * Adds CSS resource to current page
     * @param $item
     */
    function css($item) {
        $this->css_list[] = $item;
    }

    /**
     * Adds Meta resource to current page
     * @param $item
     */
    function meta($item) {
        $this->meta_list[] = $item;
    }

    /**
     * Adds Keyword resource to current page
     * @param $item
     */
    function keywords($items) {
        foreach ($items as $key => $item) {
            array_push($this->keyword_list, $item);
        }
    }
 
    /**
     * Twig like template inheritance
     *
     * @param string $name
     */
    function block($name = '') {
        if ($name != '') {
            $this->block_new = $name;
            ob_start();
        } else {
            if ($this->block_replace) {
                // If block was overriden in template, replace it in layout
                if (!empty($this->block_list[$this->block_new])) {
                    ob_end_clean();
                    echo $this->block_list[$this->block_new];
                }
            } else {
                $this->block_list[$this->block_new] = ob_get_clean();
            }
        }
    }
 
}