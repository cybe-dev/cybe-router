<?php

namespace Cybe;

class Router
{
    private $get_route = [], $post_route = [];

    private function route($route, $uri, $uri_parse)
    {
        $tmp_param = [];
        $pass_param = [];
        if (isset($route[$uri])) {
            $exc = $route[$uri];
        } else {
            $keys = array_keys($route);
            $temp_regex = "^";
            $result_uri_match = "";
            foreach ($uri_parse as $param) {
                if (!empty($param)) {
                    $current_regex = $temp_regex . "\/$param";
                    $getUriMatch = preg_grep("/$current_regex(\/|$)/", $keys);
                    if (count($getUriMatch) > 0) {
                        $temp_regex = $current_regex;
                        $result_uri_match = array_values($getUriMatch)[0];
                    } else {
                        $current_regex = $temp_regex . "\/\:([^\/\?]+)";
                        $getUriMatch = preg_grep("/$current_regex(\/|$)/", $keys);

                        if (count($getUriMatch) > 0) {
                            $temp_regex = $current_regex;
                            $result_uri_match = array_values($getUriMatch)[0];
                            $tmp_param[] = $param;
                        } else {
                            $result_uri_match = "";
                            break;
                        }
                    }
                }
            }

            if (!empty($result_uri_match) && isset($route[$result_uri_match]) && count($uri_parse) == count(explode("/", $result_uri_match))) {
                $exc = $route[$result_uri_match];
                $output_param_key = [];
                preg_match_all("/\:([^\/\?]+)/", $result_uri_match, $output_param_key);
                if (isset($output_param_key[1])) {
                    foreach ($output_param_key[1] as $key => $value) {
                        $pass_param[$value] = $tmp_param[$key];
                    }
                }
            }

        }
        $exc($pass_param);
    }

    public function get($endpoint, $callback)
    {
        $uri = substr($endpoint, -1) == "/" ? substr($endpoint, 0, -1) : $endpoint;
        $this->get_route[$uri] = $callback;
    }

    public function post($endpoint, $callback)
    {
        $uri = substr($endpoint, -1) == "/" ? substr($endpoint, 0, -1) : $endpoint;
        $this->post_route[$uri] = $callback;
    }

    public function serve()
    {
        $method = $_SERVER["REQUEST_METHOD"];
        $route = $this->get_route;
        $uri = explode("?", $_SERVER["REQUEST_URI"])[0];
        $uri = substr($uri, -1) == "/" ? substr($uri, 0, -1) : $uri;
        $uri_parse = explode("/", $uri);

        if ($method == "POST") {
            $route = $this->post_route;
        }

        $this->route($route, $uri, $uri_parse);
    }
}
