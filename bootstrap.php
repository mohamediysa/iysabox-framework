<?php
require __DIR__ . "/vendor/autoload.php";
require __DIR__ . "/helpers.php";

$paths = [];

function run()
{
    global $paths;
    $uri = @$_REQUEST["uri"] ? rtrim($_REQUEST["uri"], '/\\') : "/";
    $uri_arr = explode("/", $uri);
    $uri_param_regex = "/" . $uri_arr[0] . "\/\{.+\}/";
    if (array_key_exists($uri, $paths)) {
        call_user_func($paths[$uri]);
    } else if (pathWithParam($uri_param_regex) && array_key_exists(1, $uri_arr)) {
        $path =  pathWithParam($uri_param_regex);
        $reflectionFunc = new ReflectionFunction($paths[$path]);
        $parameters = $reflectionFunc->getParameters();
        $paramNames = [];
        foreach ($parameters as $param) {
            $paramNames[] = $param->getName();
        }
        $keyVal = [];
        $values = [];
        for ($i = 0; $i < count($uri_arr); $i++) {
            if ($i % 2 == 1) {
                $values[$i] = $uri_arr[$i];
            }
        }
        $values = array_values($values);

        preg_match_all('/{([^}]*)}/', $path, $matches);
        if (!empty($matches[1])) {
            foreach ($matches[1] as $value) {
                if (string_between($value, "/", "/")) {
                    $i = array_search($value, $matches[1]);
                    if (preg_match($value, $values[$i])) {
                        $keyVal[$paramNames[$i]] = $values[$i];
                    } else {
                        error404();
                    }
                } else if (string_starts_with($value, ":")) {
                    $i = array_search($value, $matches[1]);
                    if ($value == ":num" && is_numeric($values[$i])) {
                        $keyVal[$paramNames[$i]] = $values[$i];
                    } else if ($value == ":alpha" && !is_numeric($values[$i])) {
                        $keyVal[$paramNames[$i]] = $values[$i];
                    } else if ($value == ":alpha_num" && preg_match('/^[a-zA-Z0-9]+$/', $values[$i])) {
                        $keyVal[$paramNames[$i]] = $values[$i];
                    } else {
                        error404();
                    }
                } else {
                    $i = array_search($value, $matches[1]);
                    $keyVal[$paramNames[$i]] = $values[$i];
                }
            }
        }
        foreach ($keyVal as $key => $value) {
            if ($value) {
                call_user_func_array($paths[$path], $keyVal);
            } else {
                error404();
            }
        }
    } else {
        error404();
    }
}

function route(string $path, callable $a)
{
    global $paths;
    $paths[$path] = $a;
}



function pathWithParam(string $uri_param_regex)
{
    global $paths;
    $real_path = null;
    foreach ($paths as $path => $fn) {
        if (preg_match($uri_param_regex, $path)) {
            $real_path = $path;
            break;
        } else {
            $real_path = false;
        }
    }
    return $real_path;
}

function extractParameter($pattern)
{
    $matches = [];
    preg_match_all('/{([^}]*)}/', $pattern, $matches);
    if (isset($matches[1])) {
        $parameter = $matches[1];
        return $parameter;
    }
    return null;
}

if (!function_exists('string_between')) {
    /**
     * @param string $haystack
     * @param string $delimiter1
     * @param string $delimiter2
     * @return bool|string
     */
    function string_between($haystack, $delimiter1, $delimiter2)
    {
        if (!empty($haystack) && !empty($delimiter1) && !empty($delimiter2)) {
            if (strpos($haystack, $delimiter1) !== false && strpos($haystack, $delimiter2) !== false) {
                // separate $haystack in two strings and put each string in an array
                $pre_filter = explode($delimiter1, $haystack);
                if (isset($pre_filter[1])) {
                    // remove everything after the $delimiter2 in the second line of the
                    // $pre_filter[] array
                    $post_filter = explode($delimiter2, $pre_filter[1]);
                    if (isset($post_filter[0])) {
                        // return the string between $delimiter1 and $delimiter2
                        return $post_filter[0];
                    }
                    return false;
                }
                return false;
            }
            return false;
        }

        return false;
    }
}


if (!function_exists('string_starts_with')) {
    /**
     * Determine if a given string starts with a given substring.
     *
     * @param  string $haystack
     * @param  string|array $needles
     * @return bool
     */
    function string_starts_with($haystack, $needles)
    {
        foreach ((array) $needles as $needle) {
            if ($needle != '' && mb_strpos($haystack, $needle) === 0) {
                return true;
            }
        }

        return false;
    }
}
