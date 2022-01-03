<?php

if (!function_exists('convert_to_nominal')) {
    function convert_to_nominal($val)
    {
        $val = str_replace(",",".",$val);
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
        $sub = substr($nominal,-3);
        $sub2 = substr($nominal,-2);
        $sub3 = substr($nominal,-1);

        $total =  get_random_number(3);
        $total2 =  get_random_number(2);
        $total3 =  get_random_number(1);

        if($sub==0){
            $hasil =  $nominal + $total;
            return [
                'hasil' => $hasil,
                'kode_unik' => $total
            ];
        } else if($sub2 == 0){
            $hasil = $nominal + $total2;
            return [
                'hasil' => $hasil,
                'kode_unik' => $total
            ];
        } else if($sub3 == 0){
            $hasil = $nominal + $total3;
            return [
                'hasil' => $hasil,
                'kode_unik' => $total
            ];
        }else{
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
        $num = (0+str_replace(",", "", $num));

        // is this a number?
        if (!is_numeric($num)) return false;

        // now filter it;
//        if ($num > 1000000000000) return round(($num/1000000000000), 2);
//        elseif ($num > 1000000000) return round(($num/1000000000), 2);
//        elseif ($num > 1000000) return round(($num/1000000), 2);
//        elseif ($num > 1000) return round(($num/1000), 2);

        return round(($num/1000000), 2);
    }
    if (!function_exists('similarityDistance')) {
        function similarityDistance($preferences, $person1, $person2)
        {
            $similar = array();
            $sum = 0;

            foreach($preferences[$person1] as $key=>$value)
            {
                if(array_key_exists($key, $preferences[$person2]))
                    $similar[$key] = 1;
            }

            if(count($similar) == 0)
                return 0;

            foreach($preferences[$person1] as $key=>$value)
            {
                if(array_key_exists($key, $preferences[$person2]))
                    $sum = $sum + pow($value - $preferences[$person2][$key], 2);
            }

            return  1/(1 + sqrt($sum));

        }
    }
    if (!function_exists('get_recommendations')) {
        function get_recommendations($preferences, $person)
        {
            $total = array();
            $simSums = array();
            $ranks = array();
            $sim = 0;

            foreach($preferences as $otherPerson=>$values)
            {
                if($otherPerson != $person)
                {
                    $sim = similarityDistance($preferences, $person, $otherPerson);
                }

                if($sim > 0)
                {
                    foreach($preferences[$otherPerson] as $key=>$value)
                    {
                        if(!array_key_exists($key, $preferences[$person]))
                        {
                            if(!array_key_exists($key, $total)) {
                                $total[$key] = 0;
                            }
                            $total[$key] += $preferences[$otherPerson][$key] * $sim;

                            if(!array_key_exists($key, $simSums)) {
                                $simSums[$key] = 0;
                            }
                            $simSums[$key] += $sim;
                        }
                    }

                }
            }

            foreach($total as $key=>$value)
            {
                $ranks[$key] = $simSums[$key];
            }

            array_multisort($ranks, SORT_DESC);
            return $ranks;

        }
    }
}
