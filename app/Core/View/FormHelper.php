<?php
 namespace App\Core\View;

 class FormHelper
 {
        public static function open($action, $method = 'POST'): string
        {
            return "<form action=\"{$action}\" method=\"{$method}\">";
        }

        public static function close(): string
        {
            return "</form>";
        }

        public static function input($type, $name, $value = '', $attributes = []): string
        {
            $attrString = '';
            foreach ($attributes as $key => $val) {
                $attrString .= " {$key}=\"{$val}\"";
            }
            return "<input type=\"{$type}\" name=\"{$name}\" value=\"{$value}\"{$attrString}>";
        }

        public static function textarea($name, $value = '', $attributes = []): string
        {
            $attrString = '';
            foreach ($attributes as $key => $val) {
                $attrString .= " {$key}=\"{$val}\"";
            }
            return "<textarea name=\"{$name}\"{$attrString}>{$value}</textarea>";
        }

        public static function select($name, $options = [], $selected = null, $attributes = []): string
        {
            $attrString = '';
            foreach ($attributes as $key => $val) {
                $attrString .= " {$key}=\"{$val}\"";
            }
            $optionsHtml = '';
            foreach ($options as $value => $label) {
                $isSelected = ($value == $selected) ? ' selected' : '';
                $optionsHtml .= "<option value=\"{$value}\"{$isSelected}>{$label}</option>";
            }
            return "<select name=\"{$name}\"{$attrString}>{$optionsHtml}</select>";
        }


 }