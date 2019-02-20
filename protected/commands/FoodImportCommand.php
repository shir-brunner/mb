<?php

/**
 * Created by PhpStorm.
 * User: awinterstein
 * Date: 12/01/2016
 * Time: 19:54
 */
class FoodImportCommand extends CConsoleCommand
{
    private $_curl;
    private $_curlError;

    public function actionImport()
    {
        $this->initCurl();

        $sql = 'select * from import.food_ls_name order by id';

        $foodNames = Yii::app()->db->createCommand($sql)->query();

        foreach ($foodNames as $foodName)
        {
            echo $foodName['id'] . PHP_EOL;
            $query = urlencode($foodName['name']);

            $url = "http://www.livestrong.com/service/food/elastic/foods/?query=$query&limit=1&offset=0&source%5Busda%5D=usda&source%5Bnutritionix%5D=nutritionix&source%5Bfactual%5D=factual&source%5Brestaurant%5D=restaurant&popularity_boost_factor=0";
            $this->curlSetUrl($url);
            $response = $this->executeCurl();

            if(!$response)
            {
                echo 'Bad response, searched for: ' . $foodName['name'] . ',ID: ' .$foodName['id'] . PHP_EOL;
                echo 'error: ' . $this->getCurlError() . PHP_EOL;

                $badFood = new TBadFood();
                $badFood->id = $foodName['id'];
                $badFood->save();
                continue;
            }


            if(empty($response->data))
            {
                continue;
            }

            $total  = $response->total;

            //run new request with limit
            $url = "http://www.livestrong.com/service/food/elastic/foods/?query=$query&limit=$total&offset=0&source%5Busda%5D=usda&source%5Bnutritionix%5D=nutritionix&source%5Bfactual%5D=factual&source%5Brestaurant%5D=restaurant&popularity_boost_factor=0";

            $this->curlSetUrl($url);
            $response = $this->executeCurl();

            if(!$response)
            {
                echo 'Bad response, searched for: ' . $foodName['name'] . ',ID: ' .$foodName['id'] . PHP_EOL;
                echo 'error: ' . $this->getCurlError() . PHP_EOL;

                $badFood = new TBadFood();
                $badFood->id = $foodName['id'];
                $badFood->save();
                continue;
            }

            $insert = array();
            foreach ($response->data as $food)
            {
                $insert[] = array(
                    'id' => $food->id,
                    'key' => $food->key,
                    'name' => $food->name,
                    'brand' => $food->brand,
                    'username' => $food->username,
                    'source' => $food->source,
                    'serving_size' => $food->serving_size,
                    'cals' => $food->cals,
                    'fat' => $food->fat,
                    'carbs' => $food->carbs,
                    'protein' => $food->protein,
                    'sat_fat' => $food->sat_fat,
                    'dietary_fiber' => $food->dietary_fiber,
                    'sugars' => $food->sugars,
                    'sodium' => $food->sodium,
                    'cholesterol' => $food->cholesterol,
                    'cals_from_fat' => $food->cals_from_fat,
                    'cals_perc_fat' => $food->cals_perc_fat,
                    'cals_perc_carbs' => $food->cals_perc_carbs,
                    'cals_perc_protein' => $food->cals_perc_protein,
                );
            }

            $builder = Yii::app()->db->schema->commandBuilder;
            $command= $builder->createMultipleInsertCommand('t_food', $insert);
            $command->execute();
        }

        $this->closeCurl();
    }

    public function actionImportRaw()
    {
        $this->initCurl();

        $sql = 'select * from import.search where id > 35920 order by id';

        $foodNames = Yii::app()->db->createCommand($sql)->query();
        $search = '';
        $i = 1;
        foreach ($foodNames as $foodName)
        {
            if($i++ % 10 !== 0)
            {
                $search .= $foodName['search'] . ' ';
                continue;
            }

            $search .= $foodName['search'] . ' ';

            echo $foodName['id'] . PHP_EOL;

            $query = urlencode($search);

            $url = "http://www.livestrong.com/service/food/elastic/foods/?query=$query&limit=10000&offset=0&source%5Busda%5D=usda&source%5Bnutritionix%5D=nutritionix&popularity_boost_factor=0";
            $this->curlSetUrl($url);
            $response = $this->executeCurl(false);

            if(!$response)
            {
                echo 'Bad response, searched for: ' . $search . ',ID: ' . $foodName['id'] . PHP_EOL;
                echo 'error: ' . $this->getCurlError() . PHP_EOL;
                continue;
            }

            //echo $query . PHP_EOL;

            Yii::app()->db->createCommand()->insert('import.data', array('data'=> $response, 'keyword' => $foodName['id']));
            $search = '';
        }

        $this->closeCurl();
    }

    public function actionUniqueNames()
    {
        $handle = fopen("foodNames.csv", "r");
        $i = 0;
        while (($data = fgetcsv($handle)) !== FALSE)
        {
            if($i++ < 200700){
                continue;
            }
            try{
                $str = trim($data[0]);
                $str = trim($str, '(');
                $str = trim($str, ')');
                $str = trim($str, "'");
                $str = trim($str, '"');

                Yii::app()->db->createCommand()->insert('import.search', array('search'=> $str));
            }
            catch(CDbException $e){
                if(!$e->getCode() == 23000) //duplicate pk
                {
                    echo $data[0] . PHP_EOL;
                    print_r($e);
                    die;
                }
            }
            echo $i . PHP_EOL;
        }
        echo 'done';
    }

    public function actionImportMeasures()
    {
        $this->initCurl();


        //$sql = 'select distinct * from leaf.t_food order by id';
        $sql = 'select * from leaf.food where food_id > 114792 order by food_id'; //27360 remaining

        $foods = Yii::app()->db->createCommand($sql)->query();

        $count = count($foods);
        foreach ($foods as $food)
        {
            echo $count-- . '(food ID: ' . $food['food_id'] . ')' . PHP_EOL;

            $originFoodId = $food['food_origin_id'];
            $url = "http://www.livestrong.com/service/food/elastic/unit/?id=$originFoodId";

            $this->curlSetUrl($url);
            $measure = $this->executeCurl(false);

            if(!$measure)
            {
                echo 'Couldn\'t get measures for ID: ' . $food['food_id'] . PHP_EOL;
                echo 'error: ' . $this->getCurlError();

                continue;
            }

            if(empty($measure))
            {
                echo 'measures empty for ID: ' . $food['food_id'] . PHP_EOL;
                echo 'error: ' . $this->getCurlError();

                continue;
            }

            Yii::app()->db->createCommand()->insert('import.measures', array('data'=> $measure, 'origin_id' => $originFoodId));
        }

        $this->closeCurl();
    }

    public function actionDiff()
    {
        $originFoods = Food::model()->resetScope()->findAll();
        foreach ($originFoods as $food)
        {
            $foodName = addslashes($food->food_name);
            $sql = "SELECT name, 
	                    MATCH(name) AGAINST('$foodName' IN NATURAL LANGUAGE MODE) as score
                        FROM leaf.t_food
                        WHERE 
                            MATCH(name) AGAINST('$foodName' IN NATURAL LANGUAGE MODE) 
                            and 
                            MATCH(name) AGAINST('$foodName' IN NATURAL LANGUAGE MODE) > 5";

            $result = Yii::app()->db->createCommand($sql)->queryAll(array('limit' => 10));
            if(empty($result))
            {
                $foodDiff = new FoodDiff();
                $foodDiff->food_id = $food->food_id;
                $foodDiff->food_name = $food->food_name;
                $foodDiff->save();
            }
        }
    }

    function getRandomUserAgent()
    {
        $userAgents=array(
            "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-GB; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6",
            "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1)",
            "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; .NET CLR 1.1.4322; .NET CLR 2.0.50727; .NET CLR 3.0.04506.30)",
            "Opera/9.20 (Windows NT 6.0; U; en)",
            "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; en) Opera 8.50",
            "Mozilla/4.0 (compatible; MSIE 6.0; MSIE 5.5; Windows NT 5.1) Opera 7.02 [en]",
            "Mozilla/5.0 (Macintosh; U; PPC Mac OS X Mach-O; fr; rv:1.7) Gecko/20040624 Firefox/0.9",
            "Mozilla/5.0 (Macintosh; U; PPC Mac OS X; en) AppleWebKit/48 (like Gecko) Safari/48"
        );
        $random = rand(0,count($userAgents)-1);

        return $userAgents[$random];
    }

    public function actionTestCurl()
    {
        $this->initCurl();
        $this->curlSetUrl('http://www.whatsmyip.net/');
        echo $this->executeCurl();
        echo $this->getCurlError();
        $this->closeCurl();
    }

    private function initCurl()
    {
        $this->_curl = curl_init();
        curl_setopt($this->_curl,CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->_curl,CURLOPT_HEADER, false);
        curl_setopt($this->_curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($this->_curl, CURLOPT_CONNECTTIMEOUT ,30);
        curl_setopt($this->_curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($this->_curl,CURLOPT_USERAGENT, $this->getRandomUserAgent());
        curl_setopt($this->_curl, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
        curl_setopt($this->_curl, CURLOPT_PROXY, '127.0.0.1:9150');

        echo 'Connected curl via TOR socks5' . PHP_EOL;
        return true;
    }

    private function curlSetUrl($url)
    {
        curl_setopt($this->_curl, CURLOPT_URL, $url);
    }

    private function closeCurl()
    {
        curl_close($this->_curl);
    }

    private function executeCurl($parse = true)
    {
        $output = curl_exec($this->_curl);

        $response = $output;

        if($parse)
        {
            $response = json_decode($output);
        }

        if(!empty(curl_error($this->_curl)))
        {
            $this->setCurlError(curl_error($this->_curl));
            return false;
        }

        if(!$response)
        {
            $this->setCurlError('Empty response / cannot parse json');
            return false;
        }

        return $response;
    }

    private function setCurlError($error)
    {
        $this->_curlError = $error;
    }

    private function getCurlError()
    {
        return $this->_curlError;
    }

    public function actionPortLocalFood()
    {
        $foods = Food::model()->findAll(array('order' => 'food_id', 'condition' => 'food_id > 18486'));
        $count = count($foods);
        foreach ($foods as $food)
        {
            echo $count-- . ' - ' . $food->food_id . PHP_EOL;
            $nFood = new NFood();
            $nFood->food_name = trim($food->food_name);
            $nFood->food_brand = ''; //trim
            $nFood->food_origin = '1';
            $nFood->food_origin_id = $food->food_id;
            $nFood->food_verified = 1;
            if($nFood->save())
            {
                foreach ($food->measures as $foodMeasure)
                {
                    $foodId = $food->food_id;
                    $measureId = $foodMeasure->measure_id;

                    $grams = Yii::app()->db->createCommand("select food_measure_gram_weight from leaf.food_measure where food_id = $foodId and measure_id = $measureId")->queryScalar();

                    if(!$grams || $grams == '0')
                    {
                        echo 'Error saving measure for food id: ' . $food->food_id . PHP_EOL;
                        continue;
                    }

                    $servingSize = $foodMeasure->measure_description;

                    if($measureId == 30)
                    {
                        $servingSize = 'cup';
                        $grams = $grams * 4;
                    }
                    elseif($measureId == 31)
                    {
                        $servingSize = 'cup';
                        $grams = $grams * 2;
                    }
                    elseif($measureId == 32)
                    {
                        $servingSize = 'cup';
                        $grams = $grams * 3;
                    }
                    elseif($measureId == 33)
                    {
                        $servingSize = 'cup';
                        $grams = $grams * 4 / 3;
                    }
                    elseif($measureId == 34)
                    {
                        $servingSize = 'cup';
                        $grams = $grams * 3 / 2;
                    }

                    $nFoodMeasure = new NFoodMeasure();
                    $nFoodMeasure->food_id = $nFood->food_id;
                    $nFoodMeasure->food_measure_verified = 1;

                    $nFoodMeasure->food_measure_serving_size = $servingSize;
                    $nFoodMeasure->food_measure_calories = $food->food_calories / 100 * $grams;
                    $nFoodMeasure->food_measure_fat = $food->food_fat / 100 * $grams;
                    $nFoodMeasure->food_measure_carbs = $food->food_carbohydrates / 100 * $grams;
                    $nFoodMeasure->food_measure_protein = $food->food_protein / 100 * $grams;
                    $nFoodMeasure->food_measure_sat_fat = $food->food_saturated_fat / 100 * $grams;
                    $nFoodMeasure->food_measure_dietary_fiber = $food->food_fiber / 100 * $grams;
                    $nFoodMeasure->food_measure_sugars = $food->food_sugars / 100 * $grams;
                    $nFoodMeasure->food_measure_sodium = $food->food_sodium / 100 * $grams;
                    $nFoodMeasure->food_measure_cholesterol = $food->food_cholesterol / 100 * $grams;
                    if(!$nFoodMeasure->save())
                    {
                        echo 'Error saving measure for food id: ' . $food->food_id . PHP_EOL;
                    }
                }
            }
            else
            {
                echo 'Error (food id): ' . $food->food_id . PHP_EOL;
            }

        }

        echo PHP_EOL . 'Done' . PHP_EOL;

    }

    public function actionPortRemoteFood()
    {
        $sql = 'select distinct * from leaf.t_food where id > 2513453 order by id ';
        $foods = Yii::app()->db->createCommand($sql)->query();

        $count = count($foods);
        foreach ($foods as $food)
        {
            echo $count-- . ' - ' . $food['id'] . PHP_EOL;
            $nFood = new NFood();
            $nFood->food_name = trim($food['name']);
            $nFood->food_brand = trim($food['brand']);
            $nFood->food_origin = '2';
            $nFood->food_origin_id = $food['id'];
            $nFood->food_verified = 1;
            if($nFood->save())
            {
                //find the measure
                $tMeasure = TMeasure::model()->findByAttributes(array('id' => $food['id']));
                if(!$tMeasure)
                {
                    $tMeasures = TMeasure::model()->findAllByAttributes(array('food_id' => $food['id']));
                    if(empty($tMeasures))
                    {
                        continue;//we have no data for this food // rare exeption (22 entries found)
                    }

                    foreach ($tMeasures as $availableMeasure)
                    {
                        if(strtolower(trim($availableMeasure->serving_size)) == strtolower(trim($food['serving_size'])))
                        {
                            $tMeasure = $availableMeasure;
                            break;
                        }
                    }

                    if(!$tMeasure)
                    {
                        echo 'error: ' . $food['id'] . ' No default measure found' . PHP_EOL;
                        continue;
                    }
                }

                $defaultServingSize = $tMeasure->serving_size;
                $conversions = json_decode($tMeasure->conversions);
                if(empty($conversions))
                {
                    $units = json_decode($tMeasure->units);
                    $conversions[$units[0]->unit] = $units[0]->quantity;
                }

                foreach ($conversions as $servingSize => $multiplier)
                {
                    if($multiplier == 0)
                    {
                        echo 'measure not added (divide by zero): ' . $food['id'] . PHP_EOL;
                        continue;
                    }

                    $newServingSize = $servingSize;

                    if(strpos(strtolower($defaultServingSize),'0.5 ea') !== false && $servingSize == 'serving')
                    {
                        $newServingSize = 'each';/////////////
                        $multiplier = $multiplier * 2;
                    }
                    elseif(strpos(strtolower($defaultServingSize),' pc ') !== false && $servingSize == 'serving')
                    {
                        $newServingSize = 'pc';
                    }
                    elseif(strpos(strtolower($defaultServingSize),'tablespoon unpopped') !== false && $servingSize == 'tbsp')
                    {
                        $newServingSize = 'tbsp unpopped';
                    }
                    elseif(strpos(strtolower($defaultServingSize),'1 ea') !== false && $servingSize == 'serving')
                    {
                        $newServingSize = 'each';////////////////////////
                    }
                    elseif(strpos(strtolower($defaultServingSize),'0.1 ea') !== false && $servingSize == 'serving')
                    {
                        $newServingSize = 'each';////////////////////////
                        $multiplier = $multiplier * 10;
                    }
                    elseif(strpos(strtolower($defaultServingSize),'2 ea') !== false && $servingSize == 'serving')
                    {
                        $newServingSize = 'each';////////////////////////
                        $multiplier = $multiplier / 2;
                    }
                    elseif(strpos(strtolower($defaultServingSize),'3 ea') !== false && $servingSize == 'serving')
                    {
                        $newServingSize = 'each';////////////////////////
                        $multiplier = $multiplier / 3;
                    }
                    elseif(strpos(strtolower($defaultServingSize),'0.12 ea') !== false && $servingSize == 'serving')
                    {
                        $newServingSize = 'each';////////////////////////
                        $multiplier = $multiplier / 0.12;
                    }
                    elseif(strpos(strtolower($defaultServingSize),'0.33 ea') !== false && $servingSize == 'serving')
                    {
                        $newServingSize = 'each';////////////////////////
                        $multiplier = $multiplier / 0.33;
                    }
                    elseif(strpos(strtolower($defaultServingSize),'4 ea') !== false && $servingSize == 'serving')
                    {
                        $newServingSize = 'each';////////////////////////
                        $multiplier = $multiplier / 4;
                    }
                    elseif(strpos(strtolower($defaultServingSize),'5 ea') !== false && $servingSize == 'serving')
                    {
                        $newServingSize = 'each';////////////////////////
                        $multiplier = $multiplier / 5;
                    }
                    elseif(strpos(strtolower($defaultServingSize),'6 ea') !== false && $servingSize == 'serving')
                    {
                        $newServingSize = 'each';////////////////////////
                        $multiplier = $multiplier / 6;
                    }
                    elseif(strpos(strtolower($defaultServingSize),'15 ea') !== false && $servingSize == 'serving')
                    {
                        $newServingSize = 'each';////////////////////////
                        $multiplier = $multiplier / 15;
                    }
                    elseif(strpos(strtolower($defaultServingSize),'13 ea') !== false && $servingSize == 'serving')
                    {
                        $newServingSize = 'each';////////////////////////
                        $multiplier = $multiplier / 13;
                    }
                    elseif(strpos(strtolower($defaultServingSize),'18 ea') !== false && $servingSize == 'serving')
                    {
                        $newServingSize = 'each';////////////////////////
                        $multiplier = $multiplier / 18;
                    }
                    elseif(strpos(strtolower($defaultServingSize),'0.33 ea') !== false && $servingSize == 'serving')
                    {
                        $newServingSize = 'each';////////////////////////
                        $multiplier = $multiplier / 0.33;
                    }
                    elseif(strpos(strtolower($defaultServingSize),'0.5 cu') !== false && $servingSize == 'serving')
                    {
                        $newServingSize = 'cup';////////////////////////
                        $multiplier = $multiplier * 2;
                    }
                    elseif(strpos(strtolower($defaultServingSize),'0.25 cu') !== false && $servingSize == 'serving')
                    {
                        $newServingSize = 'cup';////////////////////////
                        $multiplier = $multiplier * 4;
                    }
                    elseif(strpos(strtolower($defaultServingSize),'0.333 cu') !== false && $servingSize == 'serving')
                    {
                        $newServingSize = 'cup';////////////////////////
                        $multiplier = $multiplier / 0.33;
                    }
                    elseif(strpos(strtolower($defaultServingSize),'8 fl') !== false && $servingSize == 'serving')
                    {
                        $newServingSize = 'fl oz';////////////////////////
                        $multiplier = $multiplier / 8;
                    }
                    elseif(strpos(strtolower($defaultServingSize),'5 gr') !== false && $servingSize == 'serving')
                    {
                        $newServingSize = 'gr';////////////////////////
                        $multiplier = $multiplier / 5;
                    }
                    elseif(strpos(strtolower($defaultServingSize),'3 pc') !== false && $servingSize == 'serving')
                    {
                        $newServingSize = 'pc';////////////////////////
                        $multiplier = $multiplier / 3;
                    }
                    elseif(strpos(strtolower($defaultServingSize),'2 pc') !== false && $servingSize == 'serving')
                    {
                        $newServingSize = 'pc';
                        $multiplier = $multiplier / 2;
                    }
                    elseif($servingSize == 'serving')
                    { //fallback in case we have serving as a serving size and a different unit in conversions
                        $newServingSize = $defaultServingSize;
                    }


                    $nFoodMeasure = new NFoodMeasure();
                    $nFoodMeasure->food_id = $nFood->food_id;
                    $nFoodMeasure->food_measure_verified = 1;

                    $nFoodMeasure->food_measure_serving_size = $newServingSize;
                    $nFoodMeasure->food_measure_calories = $food['cals'] / $multiplier;
                    $nFoodMeasure->food_measure_fat = $food['fat'] / $multiplier;
                    $nFoodMeasure->food_measure_carbs = $food['carbs'] / $multiplier;
                    $nFoodMeasure->food_measure_protein = $food['protein'] / $multiplier;
                    $nFoodMeasure->food_measure_sat_fat = $food['sat_fat'] / $multiplier;
                    $nFoodMeasure->food_measure_dietary_fiber = $food['dietary_fiber'] / $multiplier;
                    $nFoodMeasure->food_measure_sugars = $food['sugars'] / $multiplier;
                    $nFoodMeasure->food_measure_sodium = $food['sodium'] / $multiplier;
                    $nFoodMeasure->food_measure_cholesterol = $food['cholesterol'] / $multiplier;

                    if(!$nFoodMeasure->save())
                    {
                        echo 'Error saving measure for food id: ' . $food->food_id . PHP_EOL;
                    }
                }
            }
            else
            {
                echo 'Error (food id): ' . $food->food_id . PHP_EOL;
            }

        }

        echo PHP_EOL . 'Done' . PHP_EOL;

    }

    public function actionFoodJsonToTable()
    {
        $sql = 'select * from import.data order by id';

        $rows = Yii::app()->db->createCommand($sql)->query();
        $i = 81580;
        foreach ($rows as $row)
        {
            echo $row['id'] . PHP_EOL;
            $data = json_decode($row['data']);

            if($data->total > 9999)
            {
                echo $row['id'] . ' is bigger than 9999' . PHP_EOL;
            }

            foreach ($data->data as $food)
            {
                $id = $i++;
                try{
                    Yii::app()->db->createCommand()->insert('leaf.food', array(
                        'food_id' => $id,
                        'food_name'=> $food->name,
                        'food_brand'=> $food->brand,
                        'food_origin'=> $food->source == 'nutritionix' ? 1 : $food->source,
                        'food_origin_id'=> $food->id,
                        'food_verified'=> 1,
                    ));

                    Yii::app()->db->createCommand()->insert('leaf.food_measure', array(
                        'food_id'=> $id,
                        'food_measure_name'=> $food->serving_size,
                        'food_measure_calories'=> $food->cals,
                        'food_measure_fat'=> $food->fat,
                        'food_measure_carbs'=> $food->carbs,
                        'food_measure_protein'=> $food->protein,
                        'food_measure_sat_fat'=> $food->sat_fat,
                        'food_measure_dietary_fiber'=> $food->dietary_fiber,
                        'food_measure_sugars'=> $food->sugars,
                        'food_measure_sodium'=> $food->sodium,
                        'food_measure_cholesterol'=> $food->cholesterol,
                        'food_measure_verified'=> 1,
                        'food_measure_primary'=> 1,
                    ));
                }
                catch(CDbException $e){
                    if(!$e->getCode() == 23000) //duplicate pk
                    {
                        print_r($e);
                        die;
                    }
                }
            }
        }
    }

    public function actionMeasureJsonToTable()
    {
        $sql = 'select * from import.measures order by food_id';

        $rows = Yii::app()->db->createCommand($sql)->query();

        $i = 0;
        foreach ($rows as $row)
        {
            $data = json_decode($row['data']);
            $data = reset($data);

            $defaultMeasure = FoodMeasure::model()->findAllByAttributes(array('food_id' => $row['food_id']));
            if(empty($defaultMeasure) || count($defaultMeasure) > 1)
            {
                echo 'ERROR: No Measure or more than one measure for food ID: ' . $row['food_id'] . ' Skipping please check manually' . PHP_EOL;
                continue;
            }

            $defaultMeasure = reset($defaultMeasure);

            if(!isset($defaultMeasure->food_measure_name) || !isset($data->serving_size))
            {
                echo 'ERROR: No measure or default measzure for food ID: ' . $row['food_id'] . PHP_EOL;
                continue;
            }

            if(strlen($defaultMeasure->food_measure_name) > 5)
            {
                if(levenshtein(trim(strtolower($defaultMeasure->food_measure_name)), trim(strtolower($data->serving_size))) > 3)
                {
                    echo 'ERROR: Default measure for food ID: ' . $row['food_id'] . ' Doesnt match measure from json, Skipping please check manually' . PHP_EOL;
                    continue;
                }
            }
            elseif(strlen($defaultMeasure->food_measure_name) > 4)
            {
                if(levenshtein(trim(strtolower($defaultMeasure->food_measure_name)), trim(strtolower($data->serving_size))) > 2)
                {
                    echo 'ERROR: Default measure for food ID: ' . $row['food_id'] . ' Doesnt match measure from json, Skipping please check manually' . PHP_EOL;
                    continue;
                }
            }
            elseif(strlen($defaultMeasure->food_measure_name) > 3)
            {
                if(levenshtein(trim(strtolower($defaultMeasure->food_measure_name)), trim(strtolower($data->serving_size))) > 2)
                {
                    echo 'ERROR: Default measure for food ID: ' . $row['food_id'] . ' Doesnt match measure from json, Skipping please check manually' . PHP_EOL;
                    continue;
                }
            }
            else
            {
                if(trim(strtolower($defaultMeasure->food_measure_name)) != trim(strtolower($data->serving_size)))
                {
                    echo 'ERROR: Default measure for food ID: ' . $row['food_id'] . ' Doesnt match measure from json, Skipping please check manually' . PHP_EOL;
                    continue;
                }
            }


            if($defaultMeasure->food_measure_calories != $data->cals)
            {
                echo 'ERROR: Default measure values for food ID: ' . $row['food_id'] . ' Doesnt match measure from json, Skipping please check manually' . PHP_EOL;
                continue;
            }

            foreach ($data->conversions as $conversionName => $conversionDivider)
            {
                if($conversionDivider == 0)
                {
                    echo 'ERROR: found a conversion for food ID: ' . $row['food_id'] . ' which is 0' . PHP_EOL;
                    continue;
                }

                try{
                    Yii::app()->db->createCommand()->insert('leaf.food_measure', array(
                        'food_id'=> $row['food_id'],
                        'food_measure_name'=> $conversionName,
                        'food_measure_calories'=> $defaultMeasure->food_measure_calories / $conversionDivider,
                        'food_measure_fat'=> $defaultMeasure->food_measure_fat / $conversionDivider,
                        'food_measure_carbs'=> $defaultMeasure->food_measure_carbs / $conversionDivider,
                        'food_measure_protein'=> $defaultMeasure->food_measure_protein / $conversionDivider,
                        'food_measure_sat_fat'=> $defaultMeasure->food_measure_sat_fat / $conversionDivider,
                        'food_measure_dietary_fiber'=> $defaultMeasure->food_measure_dietary_fiber / $conversionDivider,
                        'food_measure_sugars'=> $defaultMeasure->food_measure_sugars / $conversionDivider,
                        'food_measure_sodium'=> $defaultMeasure->food_measure_sodium / $conversionDivider,
                        'food_measure_cholesterol'=> $defaultMeasure->food_measure_cholesterol / $conversionDivider,
                        'food_measure_verified'=> 1,
                        'food_measure_primary'=> 0,
                    ));
                }
                catch(CDbException $e){
                    print_r($row['food_id']);
                    print_r($e);
                    die;
                }
            }
        }
    }
}