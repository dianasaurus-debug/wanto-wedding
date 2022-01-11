<?php

if (!function_exists('convert_to_nominal')) {
    function convert_to_nominal($val)
    {
        $val = str_replace(",", ".", $val);
        $val = preg_replace('/\.(?=.*\.)/', '', $val);
        return intval($val);
    }
}
if (!function_exists('get_random_number')) {
    function get_random_number($length)
    {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
if (!function_exists('generate_unique_price')) {
    function generate_unique_price($price)
    {
        $nominal = $price; // jumlah nominal awal
        $sub = substr($nominal, -3);
        $sub2 = substr($nominal, -2);
        $sub3 = substr($nominal, -1);

        $total = get_random_number(3);
        $total2 = get_random_number(2);
        $total3 = get_random_number(1);

        if ($sub == 0) {
            $hasil = $nominal + $total;
            return [
                'hasil' => $hasil,
                'kode_unik' => $total
            ];
        } else if ($sub2 == 0) {
            $hasil = $nominal + $total2;
            return [
                'hasil' => $hasil,
                'kode_unik' => $total
            ];
        } else if ($sub3 == 0) {
            $hasil = $nominal + $total3;
            return [
                'hasil' => $hasil,
                'kode_unik' => $total
            ];
        } else {
            return [
                'hasil' => $nominal,
                'kode_unik' => null
            ];
        }
    }

}

if (!function_exists('formatHargaJuta')) {
    function formatHargaJuta($num)
    {
        // first strip any formatting;
        $num = (0 + str_replace(",", "", $num));

        // is this a number?
        if (!is_numeric($num)) return false;

        // now filter it;
//        if ($num > 1000000000000) return round(($num/1000000000000), 2);
//        elseif ($num > 1000000000) return round(($num/1000000000), 2);
//        elseif ($num > 1000000) return round(($num/1000000), 2);
//        elseif ($num > 1000) return round(($num/1000), 2);

        return round(($num / 1000000), 2);
    }
}
if (!function_exists('similarityDistance')) {
    function similarityDistance($preferences, $person1, $person2) //masukan berupa user1 dan user2
    {
        $similar = array();
        $sum = 0;
        foreach ($preferences[$person1] as $key => $value) {
            if (array_key_exists($key, $preferences[$person2]))
                $similar[$key] = 1;
        }

        if (count($similar) == 0)
            return 0;

        foreach ($preferences[$person1] as $key => $value) { //rumus mencari similiaritynya
            if (array_key_exists($key, $preferences[$person2]))
                $sum = $sum + pow($value - $preferences[$person2][$key], 2);
        }
        return 1 / (1 + sqrt($sum)); //hasil dari similarity user1 & user lain

    }
}
if (!function_exists('get_recommendations')) {
    function get_recommendations($preferences, $person) //fungsi nyari rekomendasinya
    {
        $total = array();
        $simSums = array();
        $ranks = array();
        $sim = 0;
        $recommendations = array();
        $products = array();
        foreach ($preferences as $otherPerson => $values) {
            if ($otherPerson != $person) {
                $sim = similarityDistance($preferences, $person, $otherPerson); //masukin similaritynya ke array biar gampang diakses
                array_push($recommendations, $sim);
            }

            if ($sim > 0) {
                foreach ($preferences[$otherPerson] as $key => $value) {
                    if (!array_key_exists($key, $preferences[$person])) {
                        if (!array_key_exists($key, $total)) { //dapetin semua barang/produk yang ada
                            $total[$key] = 0;
                        }

                        $total[$key] += $preferences[$otherPerson][$key];
                        if (!array_key_exists($key, $simSums)) {
                            $simSums[$key] = 0;
                        }
                        $simSums[$key] = $sim;
                    }
                }

            }
        }
        foreach ($total as $key => $value) {
            $ranks[$key] = $simSums[$key];
            array_push($products, $key);
        }
        $array_of_rank = array();
        foreach ($products as $product) {
            $array_of_product = array();
            $total_up = 0;
            $new_recommendations = array();
            $value_recom = 0;
            $k=0;
            foreach ($preferences as $otherPerson => $values) {
                if ($otherPerson != $person) {
                    $value_rating = array();
                    foreach ($preferences[$otherPerson] as $key => $value) {
                        if ($key == $product) {
                            $value_rating = $value; //nilai rating dari masing2 user
                            $value_recom = $recommendations[$k]; //nilai similiaritynya
                            break;
                        } else {
                            $value_rating = 0; //nilai 0 kalau tidak kasih rating
                            $value_recom = 0;
                        }
                    }
                    array_push($array_of_product, $value_rating); //array nilai rating yg dikasih user
                    array_push($new_recommendations, $value_recom);
                    $k++;
                }
            }
            $i=0;
            $total_down = 0;
            foreach ($new_recommendations as $recom){
                $total_up+=($array_of_product[$i] * $recom); //yang atas
                $total_down+=$recom; // yang bawah similaritynya dijumlahin
                $i++;
            }
            $temp_result = $total_up/$total_down; //atas dibagi bawah
            $array_of_rank["_{$product}"] = $temp_result; //naruh di masing2 produk

        }
        array_multisort($array_of_rank);
        return $array_of_rank;
    }
}

if (!function_exists('predict_rank')) {
    function predict_rank($sims, $user)
    {
        $ranks = array(4, 4, 2);
        $i = 0;
        $total_up = 0;
        $total_down = 0;

        foreach ($ranks as $rank) {
            $total_up += ($rank * $sims[$i]);
            $total_down += ($sims[$i]);
            $i++;
        }
        return $total_up / $total_down;
    }
}
if (!function_exists('_group_by')) {
    function _group_by($array, $key)
    {
        $return = array();
        foreach ($array as $val) {
            $return[$val[$key]][] = $val;
        }
        return $return;
    }
}
if(!function_exists('send_notification')){
    function send_notification($fcm_token, $title,$text){
        $url = 'https://fcm.googleapis.com/fcm/send';

        $FcmKey = 'AAAA3-X7Fv4:APA91bGSFy43ClzXNtmZdak-P48lSx4vrVf-Mx2jwV-QYwjNxSkSGpER5zvCuoTkj2yEIe24OMhPNjMASKIqF7gQkFhgodQqYG60XIXf3_5mWQUjX48hUqjasjtOtXJHU2nfNIHkSTWL';

        $data = [
            "registration_ids" => [$fcm_token],
            "notification" => [
                "title" => $title,
                "body" => $text,
            ],
            "data" => [
                "msgId" => "msg_12342"
            ]
        ];

        $RESPONSE = json_encode($data);

        $headers = [
            'Authorization:key=' . $FcmKey,
            'Content-Type: application/json',
        ];

        // CURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $RESPONSE);

        $output = curl_exec($ch);
        if ($output === FALSE) {
            die('Curl error: ' . curl_error($ch));
        }
        curl_close($ch);

    }
}
if (!function_exists('create_notification_data')) {
    function create_notification_data($user,$title, $text)
    {
        return $notification = \App\Models\Notification::create([
            'user_id' => $user,
            'title' => $title,
            'content' => $text
        ]);
    }
}
