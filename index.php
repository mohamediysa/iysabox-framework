<?php

require __DIR__ . "/vendor/autoload.php";

$paths = [];

require __DIR__ . "/app/routes.php";

function route(string $path, callable $a)
{
    global $paths;
    $paths[$path] = $a;
}

run();

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
        $pattern = '/\/\{[^}]+\}/';
        $result = preg_replace($pattern, '', $path);
        $check_path = explode("/", $result);
        $check_uri = array();
        foreach ($uri_arr as $index => $value) {
            if ($index % 2 == 0) {
                $check_uri[] = $value;
            }
        }
        if (checkEqualValues($check_uri, $check_path)) {

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
    } else {
        error404();
    }
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

function checkEqualValues($array1, $array2)
{
    // Check if arrays have the same length
    if (count($array1) !== count($array2)) {
        return false;
    }
    // Iterate over the arrays and compare values at each index
    for ($i = 0; $i < count($array1); $i++) {
        if ($array1[$i] !== $array2[$i]) {
            return false; // Values at index $i are not equal
        }
    }
    return true; // All values are equal
}




################################
//                            //
//      HELPER FUNCTIONS      //
//                            //
################################


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



function string_starts_with($haystack, $needles)
{
    foreach ((array) $needles as $needle) {
        if ($needle != '' && mb_strpos($haystack, $needle) === 0) {
            return true;
        }
    }

    return false;
}


function errors(bool $display = true)
{
    if ($display) {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
    } else {
        ini_set('display_errors', 0);
        ini_set('display_startup_errors', 0);
        error_reporting(0);
    }
}

function error404()
{
    json(["message" => "404 Page not found"], 404);
    exit;
}

function json(array $data, int $status_code = 200): void
{

    header(getHttpStatusHeader($status_code));
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data);
    exit;
}

function view(string $page_path, array $data = [])
{
    foreach ($data as $key => $value) {
        $$key = $value;
    }
    require __DIR__ . "/app/html/" . $page_path . ".php";
}


function get(string $param, bool $escape = true)
{
    if ($param != "uri" && isset($_GET[$param])) {
        $data[$param] = $escape ? htmlspecialchars($_GET[$param]) : $_GET[$param];
        return $data;
    } else {
        return null;
    }
}

function post(string $param, bool $escape = true)
{
    if (isset($_GET[$param])) {
        $data[$param] = $escape ? htmlspecialchars($_POST[$param]) : $_POST[$param];
        return $data;
    } else {
        return null;
    }
}

function all_request(bool $escape = true): array
{
    $req = [];
    foreach ($_REQUEST as $param => $request) {
        if ($param != "uri") {
            if ($escape) {
                $req[$param] = htmlspecialchars($request);
            } else {
                $req[$param] = $request;
            }
        }
    }
    return $req;
}

function br(): string
{
    return "<br>";
}

function pre(): string
{
    return "<pre>";
}


function getHttpStatusHeader(int $statusCode): string
{
    $statusHeader = '';
    switch ($statusCode) {
        case 200:
            $statusHeader = 'HTTP/1.1 200 OK';
            break;
        case 400:
            $statusHeader = 'HTTP/1.1 400 Bad Request';
            break;
        case 404:
            $statusHeader = 'HTTP/1.1 404 Not Found';
            break;
        case 500:
            $statusHeader = 'HTTP/1.1 500 Internal Server Error';
            break;
        default:
            $statusHeader = 'HTTP/1.1 ' . $statusCode;
            break;
    }

    return $statusHeader;
}

//credit to https://github.com/hedii/helpers


function string_random($length = 32)
{
    return bin2hex(random_bytes($length / 2));
}



function string_without($haystack, $needle)
{
    if (!empty($haystack) && !empty($needle)) {
        if (strpos($haystack, $needle) !== false) {
            return str_replace($needle, '', $haystack);
        }
    }
    return $haystack;
}


function string_before(string $haystack, string $delimiter)
{
    if (!empty($haystack) && !empty($delimiter)) {
        if (strpos($haystack, $delimiter) !== false) {
            // separate $haystack in two strings and put each string in an array
            $filter = explode($delimiter, $haystack);
            if (isset($filter[0])) {
                // return the string before $delimiter
                return $filter[0];
            }
            return false;
        }
        return false;
    }
    return false;
}


function string_after($haystack, $delimiter)
{
    if (!empty($haystack) && !empty($delimiter)) {
        if (strpos($haystack, $delimiter) !== false) {
            // separate $haystack in two strings and put each string in an array
            $filter = explode($delimiter, $haystack);
            if (isset($filter[1])) {
                // return the string after $delimiter
                return $filter[1];
            }
            return false;
        }
        return false;
    }

    return false;
}





function string_ends_with($haystack, $needles)
{
    foreach ((array) $needles as $needle) {
        if ((string) $needle === mb_substr($haystack, -string_length($needle))) {
            return true;
        }
    }

    return false;
}


function string_length($value)
{
    return mb_strlen($value);
}


function string_is($pattern, $value)
{
    if ($pattern == $value) {
        return true;
    }

    $pattern = preg_quote($pattern, '#');

    // Asterisks are translated into zero-or-more regular expression wildcards
    // to make it convenient to check if the strings starts with the given
    // pattern such as "library/*", making any string check convenient.
    $pattern = str_replace('\*', '.*', $pattern);

    return (bool) preg_match('#^' . $pattern . '\z#u', $value);
}



function string_contains($haystack, $needles)
{
    foreach ((array) $needles as $needle) {
        if ($needle != '' && mb_strpos($haystack, $needle) !== false) {
            return true;
        }
    }

    return false;
}



function string_finish($value, $cap)
{
    $quoted = preg_quote($cap, '/');

    return preg_replace('/(?:' . $quoted . ')+$/u', '', $value) . $cap;
}



function is_url($string)
{
    return (bool) filter_var($string, FILTER_VALIDATE_URL);
}



function class_basename($class)
{
    $class = is_object($class) ? get_class($class) : $class;
    return basename(str_replace('\\', '/', $class));
}


function is_windows_os()
{
    return strtolower(substr(PHP_OS, 0, 3)) === 'win';
}
