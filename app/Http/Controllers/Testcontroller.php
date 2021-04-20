<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Testcontroller extends Controller
{
    public function index(Request $request)
    {
        $url = 'https://run.mocky.io/v3/0d6aab31-bb68-4d89-acc5-bc4148a3cff3';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = json_decode(curl_exec($ch), TRUE);
        curl_close($ch);
        $arr = $response['data'];
        foreach ($arr as $keys => $data) {
            if (isset($request->name) && !empty($request->name)) {
                if (strtolower($request->name) != strtolower($data['name'])) {
                    unset($arr[$keys]);
                }
            }
            if (isset($request->city) && !empty($request->city)) {
                if (strtolower($request->city) != strtolower($data['city'])) {
                    unset($arr[$keys]);
                }
            }
            if (isset($request->min_price) && !empty($request->min_price) && isset($request->max_price) && !empty($request->max_price) && $request->max_price > $request->min_price) {
                if (($data['price'] >= intval($request->min_price)) && ($data['price'] <= intval($request->max_price))) {
                    continue;
                } else {
                    unset($arr[$keys]);
                }

            }
        }
        if (isset($request->sort) && !empty($request->sort)) {
            if ($request->sort == 'name') {
                $column_sort = array_column($arr, 'name');
            }
            if ($request->sort == 'price') {
                $column_sort = array_column($arr, 'price');
            }
            array_multisort($column_sort, SORT_ASC, SORT_REGULAR, $arr);
        }
        return $arr;
    }

}
