<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class UltimateForm {

    public static function open( $attrs = [] )
    {

        $html_attrs = self::convert_html_attrs($attrs);

        return '<form '.$html_attrs.'>';
    }

    public static function close()
    {
        return '</form>';
    }

    public static function text($name, $value = '', $attrs = array())
    {
        $html_attrs = self::convert_html_attrs($attrs);

        return '<input name="'.$name.'" type="text" '.$html_attrs.' value="'.$value.'" />';
    }
	
	public static function hidden($name, $value = '', $attrs = array())
    {
        $html_attrs = self::convert_html_attrs($attrs);

        return '<input name="'.$name.'" type="hidden" '.$html_attrs.' value="'.$value.'" />';
    }


    public static function password($name, $value, $attrs = array())
    {
        $html_attrs = self::convert_html_attrs($attrs);

        return '<input name="'.$name.'" type="password" '.$html_attrs.' value="'.$value.'" />';
    }

    public static function textarea($name, $value, $attrs = array())
    {
        $html_attrs = self::convert_html_attrs($attrs);

        return '<textarea name="'.$name.'" '.$html_attrs.'>'.$value.'</textarea>';
    }

    public static function editor($name, $value, $attrs = array())
    {
        ob_start();
        wp_editor( $value, $name, $attrs );
        $content = ob_get_clean();

        return '<div class="ultimate-editor">' . $content . '</div>';
    }

    public static function checkbox( $name, $value, $checked, $attrs = array())
    {
        $html_attrs = self::convert_html_attrs($attrs);

        $checked = $checked ? 'checked' : '';

        return '<input name="'.$name.'" '.$checked.' type="checkbox" '.$html_attrs.' value="'.$value.'" />';
    }

    public static function radio($name, $value, $attrs = array(), $checked = false)
    {
        $html_attrs = self::convert_html_attrs($attrs);

        $checked = $checked ? 'checked' : '';

        return '<input name="'.$name.'" '.$checked.' type="radio" '.$html_attrs.' value="'.$value.'" />';
    }

    public static function select($name, $selected_value, $options, $attrs = array(), $labelAsValue = false, $includeEmpty = false)
    {

        $html_options = '';

        if($includeEmpty) {
            $html_options .= '<option value="">'.__('Please Select', 'sprm').'</option>';
        }
        
        foreach( $options as $key => $option ) {
            $value = !$labelAsValue ? $key : $option; 
            $selected = $value == $selected_value ? 'selected' : '';
            $html_options .= '<option '.$selected.' value="'.$value.'">'.$option.'</option>';
        }

        $html_attrs = self::convert_html_attrs($attrs);

        return '<select name="'.$name.'" '.$html_attrs.' >'.$html_options.'</select>';

    }

    public static function convert_html_attrs($attrs = array())
    {
        $html_attrs = array();
        foreach( $attrs as $key => $attr ) {
            $html_attrs[] = $key . '="' . $attr . '"';
        }
        return implode(' ', $html_attrs);
    }
    
    public static function media($name, $value = '', $attrs = array())
    {
        $id = uniqid();
        $attrs['rel'] = $id;
        $html_attrs = self::convert_html_attrs($attrs);

        $html = '<input name="'.$name.'" type="text" '.$html_attrs.' value="'.$value.'" />';
        $html .= '<button type="button" class="_ultimate-upload-media button" data-rel="'.$id.'">Upload</button>';
        return $html;
    }
}
?>