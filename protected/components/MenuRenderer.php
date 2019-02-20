<?php

class MenuRenderer
{
    public static function render(array $items, $level = 0)
    {
        $html = '';

        foreach($items as $item)
        {
            $url = empty($item['url']) ? '#' : $item['url'];
            $liClass = (!empty($item['liClass']) ? $item['liClass'] : '');

            $html .= '<li class="' . $liClass . ' ' . (self::isItemActive($item) ? 'active' : '') . '">';
            $html .= '    <a href="' . $url . '">';

            if(!empty($item['icon']))
            {
                $html .= '        <i class="fa ' . $item['icon'] . '"></i>';
            }

            $html .= $level > 0 ? $item['label'] : '<span class="nav-label ' . (!empty($item['class']) ? $item['class'] : '') . '">' . $item['label'] . '</span>';
            if(isset($item['small']))
            {
                if(is_array($item['small']))
                {
                    $html .= '<span class="pull-right label label-' . $item['small']['class'] . '">' . $item['small']['text'] . '</span>';
                }
                else
                {
                    $html .= '<span class="pull-right label label-info">' . $item['small'] . '</span>';
                }
            }

            if(!empty($item['items']))
            {
                $html .= '    <span class="fa arrow"></span>';
            }

            $html .= '    </a>';

            if(!empty($item['items']))
            {
                if($level == 0)
                {
                    $html .= '    <ul class="nav nav-second-level collapse">';
                }
                else if($level == 1)
                {
                    $html .= '    <ul class="nav nav-third-level collapse">';
                }
                else
                {
                    throw new CHttpException(400, 'Menu cannot have more than 3 levels.');
                }

                $html .=    self::render($item['items'], $level + 1);
                $html .= '    </ul>';
            }

            $html .= '</li>';
        }

        return $html;
    }

    private static function isItemActive(array $item)
    {
        if(empty($item['url']))
        {
            if(!empty($item['items']))
            {
                //check if this item has any sub items active
                return count(array_filter($item['items'], function($item) {
                    return self::isItemActive($item);
                })) > 0;
            }

            return false;
        }

        return $item['url'] == Yii::app()->request->requestUri;
    }
}