<?php

if (!function_exists('songlist_getToken')) {
    /**
     * @return mixed
     */
    function songlist_getToken()
    {
        $sql    = 'SELECT md5(rand()/rand()*rand()/rand()*rand()*rand()/rand()*rand()) as `salt`';
        $result = $GLOBALS['xoopsDB']->queryF($sql);
        list($salt) = $GLOBALS['xoopsDB']->fetchRow($result);

        return $salt;
    }
}

if (!function_exists('ucword')) {
    /**
     * @param $string
     * @return string
     */
    function ucword($string)
    {
        $ret = [];
        foreach (explode(' ', mb_strtolower($string)) as $part) {
            $ret[] = ucfirst($part);
        }

        return implode(' ', $ret);
    }
}

if (!function_exists('songlist_getIPData')) {
    /**
     * @param bool $ip
     * @return array
     */
    function songlist_getIPData($ip = false)
    {
        $ret = [];
        if (is_object($GLOBALS['xoopsUser'])) {
            $ret['uid']   = $GLOBALS['xoopsUser']->getVar('uid');
            $ret['uname'] = $GLOBALS['xoopsUser']->getVar('uname');
            $ret['email'] = $GLOBALS['xoopsUser']->getVar('email');
        } else {
            $ret['uid']   = 0;
            $ret['uname'] = (isset($_REQUEST['uname']) ? $_REQUEST['uname'] : '');
            $ret['email'] = (isset($_REQUEST['email']) ? $_REQUEST['email'] : '');
        }
        $ret['agent'] = $_SERVER['HTTP_USER_AGENT'];
        if (!$ip) {
            if (\Xmf\Request::hasVar('HTTP_X_FORWARDED_FOR', 'SERVER')) {
                $ip                  = (string)$_SERVER['HTTP_X_FORWARDED_FOR'];
                $ret['is_proxied']   = true;
                $proxy_ip            = $_SERVER['REMOTE_ADDR'];
                $ret['network-addy'] = @gethostbyaddr($ip);
                $ret['long']         = @ip2long($ip);
                if (is_ipv6($ip)) {
                    $ret['ip6']       = true;
                    $ret['proxy-ip6'] = true;
                    $ret['ip4']       = false;
                    $ret['proxy-ip4'] = false;
                } else {
                    $ret['ip4']       = true;
                    $ret['proxy-ip4'] = true;
                    $ret['ip6']       = false;
                    $ret['proxy-ip6'] = false;
                }
                $ret['ip']       = $ip;
                $ret['proxy-ip'] = $proxy_ip;
            } else {
                $ret['is_proxied']   = false;
                $ip                  = (string)$_SERVER['REMOTE_ADDR'];
                $ret['network-addy'] = @gethostbyaddr($ip);
                $ret['long']         = @ip2long($ip);
                if (is_ipv6($ip)) {
                    $ret['ip6'] = true;
                    $ret['ip4'] = false;
                } else {
                    $ret['ip4'] = true;
                    $ret['ip6'] = false;
                }
                $ret['ip'] = $ip;
            }
        } else {
            $ret['is_proxied']   = false;
            $ret['network-addy'] = @gethostbyaddr($ip);
            $ret['long']         = @ip2long($ip);
            if (is_ipv6($ip)) {
                $ret['ip6'] = true;
                $ret['ip4'] = false;
            } else {
                $ret['ip4'] = true;
                $ret['ip6'] = false;
            }
            $ret['ip'] = $ip;
        }
        $ret['made'] = time();

        return $ret;
    }
}

if (!function_exists('is_ipv6')) {
    /**
     * @param string $ip
     * @return bool
     */
    function is_ipv6($ip = '')
    {
        if ('' == $ip) {
            return false;
        }

        if (mb_substr_count($ip, ':') > 0) {
            return true;
        }

        return false;
    }
}

if (!function_exists('songlist_getFilterElement')) {
    /**
     * @param        $filter
     * @param        $field
     * @param string $sort
     * @param string $op
     * @param string $fct
     * @return bool|\XoopsModules\Songlist\Form\SelectAlbumForm|\XoopsModules\Songlist\Form\SelectArtistForm|\XoopsModules\Songlist\Form\SelectCategoryForm|\XoopsModules\Songlist\Form\SelectGenreForm|\XoopsModules\Songlist\Form\SelectVoiceForm
     */
    function songlist_getFilterElement($filter, $field, $sort = 'created', $op = '', $fct = '')
    {
        $components = songlist_getFilterURLComponents($filter, $field, $sort);
        $ele        = false;
        require_once 'songlist.object.php';
        switch ($field) {
            case 'gid':
                if ('genre' !== $op) {
                    $ele = new \XoopsModules\Songlist\Form\SelectGenreForm('', 'filter_' . $field . '', $components['value'], 1, false);
                    $ele->setExtra('onchange="window.open(\''
                                   . $_SERVER['SCRIPT_NAME']
                                   . '?'
                                   . $components['extra']
                                   . '&filter='
                                   . $components['filter']
                                   . (!empty($components['filter']) ? '|' : '')
                                   . $field
                                   . ',\'+this.options[this.selectedIndex].value'
                                   . (!empty($components['operator']) ? '+\','
                                                                        . $components['operator']
                                                                        . '\'' : '')
                                   . ',\'_self\')"');
                }
                break;
            case 'vcid':
                if ('voice' !== $op) {
                    $ele = new \XoopsModules\Songlist\Form\SelectVoiceForm('', 'filter_' . $field . '', $components['value'], 1, false);
                    $ele->setExtra('onchange="window.open(\''
                                   . $_SERVER['SCRIPT_NAME']
                                   . '?'
                                   . $components['extra']
                                   . '&filter='
                                   . $components['filter']
                                   . (!empty($components['filter']) ? '|' : '')
                                   . $field
                                   . ',\'+this.options[this.selectedIndex].value'
                                   . (!empty($components['operator']) ? '+\','
                                                                        . $components['operator']
                                                                        . '\'' : '')
                                   . ',\'_self\')"');
                }
                break;
            case 'cid':
                if ('category' !== $op) {
                    $ele = new \XoopsModules\Songlist\Form\SelectCategoryForm('', 'filter_' . $field . '', $components['value'], 1, false);
                    $ele->setExtra('onchange="window.open(\''
                                   . $_SERVER['SCRIPT_NAME']
                                   . '?'
                                   . $components['extra']
                                   . '&filter='
                                   . $components['filter']
                                   . (!empty($components['filter']) ? '|' : '')
                                   . $field
                                   . ',\'+this.options[this.selectedIndex].value'
                                   . (!empty($components['operator']) ? '+\','
                                                                        . $components['operator']
                                                                        . '\'' : '')
                                   . ',\'_self\')"');
                }
                break;
            case 'pid':
                $ele = new \XoopsModules\Songlist\Form\SelectCategoryForm('', 'filter_' . $field . '', $components['value'], 1, false);
                $ele->setExtra('onchange="window.open(\''
                               . $_SERVER['SCRIPT_NAME']
                               . '?'
                               . $components['extra']
                               . '&filter='
                               . $components['filter']
                               . (!empty($components['filter']) ? '|' : '')
                               . $field
                               . ',\'+this.options[this.selectedIndex].value'
                               . (!empty($components['operator']) ? '+\','
                                                                    . $components['operator']
                                                                    . '\'' : '')
                               . ',\'_self\')"');
                break;
            case 'abid':
                if ('albums' !== $op) {
                    $ele = new \XoopsModules\Songlist\Form\SelectAlbumForm('', 'filter_' . $field . '', $components['value'], 1, false);
                    $ele->setExtra('onchange="window.open(\''
                                   . $_SERVER['SCRIPT_NAME']
                                   . '?'
                                   . $components['extra']
                                   . '&filter='
                                   . $components['filter']
                                   . (!empty($components['filter']) ? '|' : '')
                                   . $field
                                   . ',\'+this.options[this.selectedIndex].value'
                                   . (!empty($components['operator']) ? '+\','
                                                                        . $components['operator']
                                                                        . '\'' : '')
                                   . ',\'_self\')"');
                }
                break;
            case 'aid':
                if ('artists' !== $op) {
                    $ele = new \XoopsModules\Songlist\Form\SelectArtistForm('', 'filter_' . $field . '', $components['value'], 1, false);
                    $ele->setExtra('onchange="window.open(\''
                                   . $_SERVER['SCRIPT_NAME']
                                   . '?'
                                   . $components['extra']
                                   . '&filter='
                                   . $components['filter']
                                   . (!empty($components['filter']) ? '|' : '')
                                   . $field
                                   . ',\'+this.options[this.selectedIndex].value'
                                   . (!empty($components['operator']) ? '+\','
                                                                        . $components['operator']
                                                                        . '\'' : '')
                                   . ',\'_self\')"');
                }
                break;
            case 'sid':
                if ('songs' !== $op) {
                    $ele = new \XoopsModules\Songlist\Form\SelectSongForm('', 'filter_' . $field . '', $components['value'], 1, false);
                    $ele->setExtra('onchange="window.open(\''
                                   . $_SERVER['SCRIPT_NAME']
                                   . '?'
                                   . $components['extra']
                                   . '&filter='
                                   . $components['filter']
                                   . (!empty($components['filter']) ? '|' : '')
                                   . $field
                                   . ',\'+this.options[this.selectedIndex].value'
                                   . (!empty($components['operator']) ? '+\','
                                                                        . $components['operator']
                                                                        . '\'' : '')
                                   . ',\'_self\')"');
                }
                break;
            case 'name':
            case 'title':
            case 'artists':
            case 'albums':
            case 'songs':
            case 'hits':
            case 'rank':
            case 'votes':
            case 'description':
            case 'lyrics':
            case 'songid':
            case 'tags':
                $ele = new \XoopsFormElementTray('');
                $ele->addElement(new \XoopsFormText('', 'filter_' . $field . '', 11, 40, $components['value']));
                $button = new \XoopsFormButton('', 'button_' . $field . '', '[+]');
                $button->setExtra('onclick="window.open(\''
                                  . $_SERVER['SCRIPT_NAME']
                                  . '?'
                                  . $components['extra']
                                  . '&filter='
                                  . $components['filter']
                                  . (!empty($components['filter']) ? '|' : '')
                                  . $field
                                  . ',\'+$(\'#filter_'
                                  . $field
                                  . '\').val()'
                                  . (!empty($components['operator']) ? '+\','
                                                                       . $components['operator']
                                                                       . '\'' : '')
                                  . ',\'_self\')"');
                $ele->addElement($button);
                break;
        }

        return $ele;
    }
}

if (!function_exists('songlist_getFilterURLComponents')) {
    /**
     * @param        $filter
     * @param        $field
     * @param string $sort
     * @return array
     */
    function songlist_getFilterURLComponents($filter, $field, $sort = 'created')
    {
        $parts     = explode('|', $filter);
        $ret       = [];
        $value     = '';
        $ele_value = '';
        $operator  = '';
        foreach ($parts as $part) {
            $var = explode(',', $part);
            if (count($var) > 1) {
                if ($var[0] == $field) {
                    $ele_value = $var[1];
                    if (isset($var[2])) {
                        $operator = $var[2];
                    }
                } elseif (1 != $var[0]) {
                    $ret[] = implode(',', $var);
                }
            }
        }
        $pagenav          = [];
        $pagenav['op']    = isset($_REQUEST['op']) ? $_REQUEST['op'] : 'videos';
        $pagenav['fct']   = isset($_REQUEST['fct']) ? $_REQUEST['fct'] : 'list';
        $pagenav['limit'] = \Xmf\Request::getInt('limit', 30, 'REQUEST');
        $pagenav['start'] = 0;
        $pagenav['order'] = !empty($_REQUEST['order']) ? $_REQUEST['order'] : 'DESC';
        $pagenav['sort']  = !empty($_REQUEST['sort']) ? '' . $_REQUEST['sort'] . '' : $sort;
        $retb             = [];
        foreach ($pagenav as $key => $value) {
            $retb[] = "$key=$value";
        }

        return ['value' => $ele_value, 'field' => $field, 'operator' => $operator, 'filter' => implode('|', $ret), 'extra' => implode('&', $retb)];
    }
}

if (!function_exists('songlist_obj2array')) {
    /**
     * @param $objects
     * @return array
     */
    function songlist_obj2array($objects)
    {
        $ret = [];
        foreach ((array)$objects as $key => $value) {
            if (is_a($value, 'stdClass')) {
                $ret[$key] = songlist_obj2array($value);
            } elseif (is_array($value)) {
                $ret[$key] = songlist_obj2array($value);
            } else {
                $ret[$key] = $value;
            }
        }

        return $ret;
    }
}

if (!function_exists('songlist_shortenurl')) {
    /**
     * @param $url
     * @return mixed
     */
    function songlist_shortenurl($url)
    {
        $moduleHandler                   = xoops_getHandler('module');
        $configHandler                   = xoops_getHandler('config');
        $GLOBALS['songlistModule']       = $moduleHandler->getByDirname('songlist');
        $GLOBALS['songlistModuleConfig'] = $configHandler->getConfigList($GLOBALS['songlistModule']->getVar('mid'));

        if (!empty($GLOBALS['songlistModuleConfig']['bitly_username']) && !empty($GLOBALS['songlistModuleConfig']['bitly_apikey'])) {
            $source_url = $GLOBALS['songlistModuleConfig']['bitly_apiurl'] . '/shorten?login=' . $GLOBALS['songlistModuleConfig']['bitly_username'] . '&apiKey=' . $GLOBALS['songlistModuleConfig']['bitly_apikey'] . '&format=json&longUrl=' . urlencode($url);
            $cookies    = XOOPS_ROOT_PATH . '/uploads/songlist_' . md5($GLOBALS['songlistModuleConfig']['bitly_apikey']) . '.cookie';
            if (!$ch = curl_init($source_url)) {
                return $url;
            }
            curl_setopt($ch, CURLOPT_COOKIEJAR, $cookies);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_USERAGENT, $GLOBALS['songlistModuleConfig']['user_agent']);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $GLOBALS['songlistModuleConfig']['curl_connect_timeout']);
            curl_setopt($ch, CURLOPT_TIMEOUT, $GLOBALS['songlistModuleConfig']['curl_timeout']);
            $data = curl_exec($ch);
            curl_close($ch);
            $result = songlist_object2array(json_decode($data));
            $result['status_code'] = 200;
            if ($result['status_code']) {
                if (!empty($result['data']['url'])) {
                    return $result['data']['url'];
                }

                return $url;
            }

            return $url;
        }

        return $url;
    }
}

if (!function_exists('songlist_xml2array')) {
    /**
     * @param        $contents
     * @param int    $get_attributes
     * @param string $priority
     * @return array|void
     */
    function songlist_xml2array($contents, $get_attributes = 1, $priority = 'tag')
    {
        if (!$contents) {
            return [];
        }

        if (!function_exists('xml_parser_create')) {
            return [];
        }

        //Get the XML parser of PHP - PHP must have this module for the parser to work
        $parser = xml_parser_create('');
        xml_parser_set_option($parser, XML_OPTION_TARGET_ENCODING, 'UTF-8'); # http://minutillo.com/steve/weblog/2004/6/17/php-xml-and-character-encodings-a-tale-of-sadness-rage-and-data-loss
        xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
        xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
        xml_parse_into_struct($parser, trim($contents), $xml_values);
        xml_parser_free($parser);

        if (!$xml_values) {
            return;
        }//Hmm...

        //Initializations
        $xml_array   = [];
        $parents     = [];
        $opened_tags = [];
        $arr         = [];

        $current = &$xml_array; //Refference

        //Go through the tags.
        $repeated_tag_index = []; //Multiple tags with same name will be turned into an array
        foreach ($xml_values as $data) {
            unset($attributes, $value); //Remove existing values, or there will be trouble

            //This command will extract these variables into the foreach scope
            // tag(string), type(string), level(int), attributes(array).
            extract($data); //We could use the array by itself, but this cooler.

            $result          = [];
            $attributes_data = [];

            if (isset($value)) {
                if ('tag' === $priority) {
                    $result = $value;
                } else {
                    $result['value'] = $value;
                } //Put the value in a assoc array if we are in the 'Attribute' mode
            }

            //Set the attributes too.
            if (isset($attributes) and $get_attributes) {
                foreach ($attributes as $attr => $val) {
                    if ('tag' === $priority) {
                        $attributes_data[$attr] = $val;
                    } else {
                        $result['attr'][$attr] = $val;
                    } //Set all the attributes in a array called 'attr'
                }
            }

            //See tag status and do the needed.
            if ('open' === $type) {//The starting of the tag '<tag>'
                $parent[$level - 1] = &$current;
                if (!is_array($current) or (!in_array($tag, array_keys($current)))) { //Insert New tag
                    $current[$tag] = $result;
                    if ($attributes_data) {
                        $current[$tag . '_attr'] = $attributes_data;
                    }
                    $repeated_tag_index[$tag . '_' . $level] = 1;

                    $current = &$current[$tag];
                } else { //There was another element with the same tag name
                    if (isset($current[$tag][0])) {//If there is a 0th element it is already an array
                        $current[$tag][$repeated_tag_index[$tag . '_' . $level]] = $result;
                        $repeated_tag_index[$tag . '_' . $level]++;
                    } else {//This section will make the value an array if multiple tags with the same name appear together
                        $current[$tag]                           = [$current[$tag], $result]; //This will combine the existing item and the new item together to make an array
                        $repeated_tag_index[$tag . '_' . $level] = 2;

                        if (isset($current[$tag . '_attr'])) { //The attribute of the last(0th) tag must be moved as well
                            $current[$tag]['0_attr'] = $current[$tag . '_attr'];
                            unset($current[$tag . '_attr']);
                        }
                    }
                    $last_item_index = $repeated_tag_index[$tag . '_' . $level] - 1;
                    $current         = &$current[$tag][$last_item_index];
                }
            } elseif ('complete' === $type) { //Tags that ends in 1 line '<tag>'
                //See if the key is already taken.
                if (!isset($current[$tag])) { //New Key
                    $current[$tag]                           = $result;
                    $repeated_tag_index[$tag . '_' . $level] = 1;
                    if ('tag' === $priority and $attributes_data) {
                        $current[$tag . '_attr'] = $attributes_data;
                    }
                } else { //If taken, put all things inside a list(array)
                    if (isset($current[$tag][0]) and is_array($current[$tag])) {//If it is already an array...
                        // ...push the new element into that array.
                        $current[$tag][$repeated_tag_index[$tag . '_' . $level]] = $result;

                        if ('tag' === $priority and $get_attributes and $attributes_data) {
                            $current[$tag][$repeated_tag_index[$tag . '_' . $level] . '_attr'] = $attributes_data;
                        }
                        $repeated_tag_index[$tag . '_' . $level]++;
                    } else { //If it is not an array...
                        $current[$tag]                           = [$current[$tag], $result]; //...Make it an array using using the existing value and the new value
                        $repeated_tag_index[$tag . '_' . $level] = 1;
                        if ('tag' === $priority and $get_attributes) {
                            if (isset($current[$tag . '_attr'])) { //The attribute of the last(0th) tag must be moved as well
                                $current[$tag]['0_attr'] = $current[$tag . '_attr'];
                                unset($current[$tag . '_attr']);
                            }

                            if ($attributes_data) {
                                $current[$tag][$repeated_tag_index[$tag . '_' . $level] . '_attr'] = $attributes_data;
                            }
                        }
                        $repeated_tag_index[$tag . '_' . $level]++; //0 and 1 index is already taken
                    }
                }
            } elseif ('close' === $type) { //End of tag '</tag>'
                $current = &$parent[$level - 1];
            }
        }

        return $xml_array;
    }
}

if (!function_exists('songlist_toXml')) {
    /**
     * @param $array
     * @param $name
     * @param $standalone
     * @param $beginning
     * @param $nested
     * @return string
     */
    function songlist_toXml($array, $name, $standalone, $beginning, $nested)
    {
        if ($beginning) {
            if ($standalone) {
                header('content-type:text/xml;charset=' . _CHARSET);
            }
            $output .= '<' . '?' . 'xml version="1.0" encoding="' . _CHARSET . '"' . '?' . '>' . "\n";
            $output .= '<' . $name . '>' . "\n";
            $nested = 0;
        }

        if (is_array($array)) {
            foreach ($array as $key => $value) {
                ++$nested;
                if (is_array($value)) {
                    $output .= str_repeat("\t", 1 * $nested) . '<' . (is_string($key) ? $key : $name . '_' . $key) . '>' . "\n";
                    ++$nested;
                    $output .= songlist_toXml($value, $name, false, false, $nested);
                    $nested--;
                    $output .= str_repeat("\t", 1 * $nested) . '</' . (is_string($key) ? $key : $name . '_' . $key) . '>' . "\n";
                } else {
                    if ('' != $value) {
                        ++$nested;
                        $output .= str_repeat("\t", 1 * $nested) . '  <' . (is_string($key) ? $key : $name . '_' . $key) . '>' . trim($value) . '</' . (is_string($key) ? $key : $name . '_' . $key) . '>' . "\n";
                        $nested--;
                    }
                }
                $nested--;
            }
        } elseif ('' != $array) {
            ++$nested;
            $output .= str_repeat("\t", 1 * $nested) . trim($array) . "\n";
            $nested--;
        }

        if ($beginning) {
            $output .= '</' . $name . '>';

            return $output;
        }

        return $output;
    }
}
