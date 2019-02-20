<?php
class UserImportCommand extends CConsoleCommand
{
    public function actionSearch()
    {
        $nutritionists = Nutritionist::model()->with('user')->findAll();
        $i = 0;
        foreach ($nutritionists as $nutritionist)
        {
            if($nutritionist->user->user_deleted)
            {
                continue;
            }
            echo $i++;
            $nutritionist->setAttribute('nutritionist_popularity', rand(80,98));
            $nutritionist->setSearchData();
            $nutritionist->save();
        }
    }

    public function actionEr()
    {
        include_once (Yii::app()->basePath . '/components/simple_html_dom.php');
        set_time_limit(0);

        $ti = 0;
        $ids = array_map('str_getcsv', file('eatRight.csv'));
        foreach ($ids as $id)
        {
            $ti++;
            /*if($ti++ > 1)
            {
                echo 'done 100';
                exit;
            }*/

            if($ti < 3003)
            {
                continue;
            }

            $id = reset($id);
            file_put_contents('eatRightImported.csv', $id . ',' . PHP_EOL, FILE_APPEND);
            echo $ti . PHP_EOL;

            if(empty($id))
            {
                continue;
            }

            $url = 'http://findanrd.eatright.org/listing/details/' . $id;
            $html = file_get_html($url);

            $user = array();

            if(empty($html))
            {
                continue;
            }

            $user['image'] = 'http://findanrd.eatright.org' . $html->find('#profileImage')[0]->src;

            if(strpos($user['image'], 'no_avatar') !== false)
            {
                $user['image'] = '';
            }

            $tmpTitle = trim($html->find('.active-name')[0]->text());
            $user['title'] = str_replace("'", '', $tmpTitle);
            $user['title'] = str_replace("`", '', $tmpTitle);
            $user['title'] = str_replace("&#39;", '', $tmpTitle);

            $names = explode(' ', $user['title']);

            $user['first_name'] = trim($names[0]);
            $user['last_name'] = trim($names[1],',');

            $userInfo = $html->find('.user-info')[0];

            $detailsContainer = $userInfo->find('.user-info-box')[0];
            $userDetails = $detailsContainer->find('.details-right')[0];

            $in = '';
            foreach(array_reverse($userDetails->children) as $userDetail)
            {
                if(strpos($userDetail->plaintext, 'Website') !== false)
                {
                    $in = 'website';
                    $user['website'] = $userDetail->find('a')[0]->href;
                }
                elseif(strpos($userDetail->plaintext, 'Email') !== false)
                {
                    $in = 'email';
                    $user['email'] = ltrim($userDetail->plaintext, 'Email:');
                }
                elseif(strpos($userDetail->plaintext, 'Phone') !== false)
                {
                    $in = 'phone';
                    $user['phone'] = ltrim($userDetail->plaintext, 'Phone:');
                }
                else
                {
                    if($in == 'website' || $in == 'email' || $in == 'phone')
                    {
                        $in = 'state';

                        $address = explode(',', trim($userDetail->plaintext));
                        $stateZip = null;

                        if(count($address) == 2)
                        {
                            $stateZip = explode(' ', trim($address[1]));
                            if(count($stateZip) != 2)
                            {
                                //no address
                                $in = 'end';
                                $user['company_name'] = trim($userDetail->plaintext);
                            }
                            else
                            {
                                $user['city'] = trim($address[0]);
                                $user['state'] = $stateZip[0];
                                $user['zip'] = $stateZip[1];
                            }
                        }
                    }
                    elseif($in == 'state')
                    {
                        $in = 'street';
                        $user['street'] = trim($userDetail->plaintext);
                    }
                    elseif($in == 'street')
                    {
                        $in = 'end';
                        $user['company_name'] = trim($userDetail->plaintext);
                    }
                }
            }

            /////////////////////////////////////////////////////////////////////////////
            /////////////////////////////////////////////////////////////////////////////

            foreach ($userInfo->find('h4') as $category)
            {
                if(strpos($category->text(), 'Business') !== false)
                {
                    $businessEnvironmentContainer = $category->parent();
                    foreach ($businessEnvironmentContainer->find('li') as $businessEnvi)
                    {
                        $user['business'][] = trim(substr($businessEnvi->plaintext, 0, strpos($businessEnvi->plaintext, '   ')));
                    }
                }
                elseif (strpos($category->text(), 'Language') !== false)
                {
                    $languageContainer = $category->parent();
                    foreach ($languageContainer->find('li') as $language)
                    {

                        $user['language'][] = trim(substr($language->plaintext, 0, strpos($language->plaintext, '   ')));
                    }
                }
                elseif (strpos($category->text(), 'Expertise') !== false)
                {
                    $expertiseContainer = $category->parent();
                    foreach ($expertiseContainer->find('li') as $expertise)
                    {


                        $user['expertise'][] = trim(substr($expertise->plaintext, 0, strpos($expertise->plaintext, '   ')));
                    }
                }
                elseif (strpos($category->text(), 'Service') !== false)
                {
                    $serviceContainer = $category->parent();
                    foreach ($serviceContainer->find('li') as $service)
                    {


                        $user['service'][] = trim(substr($service->plaintext, 0, strpos($service->plaintext, '   ')));
                    }
                }
                elseif (strpos($category->text(), 'Medicare') !== false)
                {
                    $medicareContainer = $category->parent();
                    foreach ($medicareContainer->find('li') as $medicare)
                    {

                        $user['medicare'][] = trim(substr($medicare->plaintext, 0, strpos($medicare->plaintext, '   ')));
                    }
                }
            }

            foreach ($userInfo->find('.user-info-box') as $infoBox)
            {
                if($infoBox->find('h4') != null || $infoBox->find('.details-right') != null)
                {
                    continue;
                }

                $user['about'] = trim($infoBox->text());
            }


            ////////////////////////////////////////////////////////////
            // let's create the user
            ///////////////////////////////////////////////////////////

            $userModel = new User();

            if(!empty($user['image']))
            {
                $img = file_get_contents($user['image']);
                $tmpfname = tempnam("/tmp", "UL_IMAGE");
                file_put_contents($tmpfname, $img);

                $cdnKey = Yii::app()->cdn->uploadImage($tmpfname, 'erim.jpg', false);
                unlink($tmpfname);

                $userModel->user_profile_picture_cdn_key = $cdnKey;
            }

            $existingUser = User::model()->findByAttributes(array('user_first_name' => $user['first_name'], 'user_last_name' => $user['last_name']));
            $existingNutritionistMetaData = null;
            if(isset($user['email']))
            {
                $existingNutritionistMetaData = NutritionistImportMetadata::model()->findByAttributes(array('original_email' => $user['email']));
            }


            //avoid duplicates based on name or email
            if($existingUser || $existingNutritionistMetaData)
            {
                echo 'found duplicate' . PHP_EOL;
                continue;
            }

            $userModel->setAttributes(array(
                'user_first_name' => $user['first_name'],
                'user_last_name' => $user['last_name'],
                'user_email' => uniqid('eatright') . '@leaf.healthcare',
                'user_mobile' => isset($user['phone']) ? trim($user['phone']) : null,
                'password' => 'a43sc3fdfAdD235',
                'user_locked' => 1
            ));


            if(!$userModel->save())
            {
                echo '<pre>';
                print_r($userModel->getErrors());
                continue;
            }

            $company = new Company();
            $company->setAttributes(array('company_name' => isset($user['company_name']) ? $user['company_name'] : $userModel->getFullName()));
            $company->save();

            $nutritionist = new Nutritionist();
            $nutritionist->setAttributes(array(
                'user_id' => $userModel->user_id,
                'company_id' => $company->company_id,
                'nutritionist_title' => isset($user['title']) ? $user['title'] : null,
                'nutritionist_street' => isset($user['street']) ? $user['street'] : null,
                'nutritionist_city' => isset($user['city']) ? $user['city'] : null,
                'nutritionist_state' => isset($user['state']) ? $user['state'] : null,
                'nutritionist_zip_code' => isset($user['zip']) ? $user['zip'] : null,
                'country_id' => 230,
                'nutritionist_about' => isset($user['about']) ? $user['about'] : null,
                'nutritionist_calendar_public' => 0,
                'nutritionist_source' => 2,
                'nutritionist_published' => 1,
            ));

            if(!$nutritionist->save())
            {
                echo '<pre>';
                print_r($nutritionist->getErrors());
                continue;
            }

            $userModel->nutritionist_id = $nutritionist->getPrimaryKey();
            $userModel->save();

            ////////////////////////
            //save m2m's
            //////////////////////
            if(isset($user['expertise']))
            {
                foreach ($user['expertise'] as $userExpertise)
                {
                    $specialtyId = null;
                    switch ($userExpertise)
                    {
                        case 'Behavioral Health':
                            $specialtyId = 7;
                            break;
                        case 'Cancer/Oncology Nutrition':
                            $specialtyId = 8;
                            break;
                        case 'Childhood Overweight/Obesity':
                            $specialtyId = 49;
                            break;
                        case 'Culinary Arts':
                            $specialtyId = 48;
                            break;
                        case 'Diabetes':
                            $specialtyId = 16;
                            break;
                        case 'Digestive Disorders':
                            $specialtyId = 18;
                            break;
                        case 'Eating Disorders':
                            $specialtyId = 19;
                            break;
                        case 'Food Allergies/Food Intolerance':
                            $specialtyId = 22;
                            break;
                        case 'General Nutrition Wellness/Healthy Eating':
                            $specialtyId = 48;
                            break;
                        case 'Gerontology Nutrition':
                            $specialtyId = 50;
                            break;
                        case 'Gluten Intolerance':
                            $specialtyId = 25;
                            break;
                        case 'Heart Health':
                            $specialtyId = 28;
                            break;
                        case 'Home Health Care':
                            $specialtyId = 48;
                            break;
                        case 'Integrative & Functional Nutrition':
                            $specialtyId = 51;
                            break;
                        case 'Kidney and Renal Diseases':
                            $specialtyId = 40;
                            break;
                        case 'Nutrition for Immune Disorders/HIV/AIDS':
                            $specialtyId = 30;
                            break;
                        case 'Pediatric Nutrition':
                            $specialtyId = 36;
                            break;
                        case 'Sports Nutrition':
                            $specialtyId = 43;
                            break;
                        case 'Vegetarian Nutrition':
                            $specialtyId = 46;
                            break;
                        case 'Weight Control':
                            $specialtyId = 47;
                            break;
                        case 'Lactation':
                            $specialtyId = 52;
                            break;
                        case 'Maternal Nutrition':
                            $specialtyId = 53;
                            break;
                        case 'Metabolic Measurements':
                            $specialtyId = 54;
                            break;
                    }

                    if($specialtyId)
                    {
                        $nutritionistSpecialty = new NutritionistM2mSpecialty();
                        $nutritionistSpecialty->nutritionist_id = $nutritionist->getPrimaryKey();
                        $nutritionistSpecialty->nutritionist_specialty_id = $specialtyId;

                        try{
                            $nutritionistSpecialty->save();
                        }
                        catch (Exception $e)
                        {
                            //
                        }

                    }
                }
            }

            $nutritionist->refresh();
            if(!empty($userModel->user_profile_picture_cdn_key))
            {
                $nutritionist->nutritionist_popularity = rand(65, 75);
            }
            else
            {
                $nutritionist->nutritionist_popularity = rand(25, 54);
            }

            $nutritionist->setSearchData();
            $nutritionist->save();

            //save metadata for future use
            $nutritionistImportMetaData = new NutritionistImportMetadata();
            $nutritionistImportMetaData->setAttributes(array(
                'source_id' => $id,
                'nutritionist_id' => $nutritionist->getPrimaryKey(),
                'business' => isset($user['business']) ? json_encode($user['business']) : null,
                'language' => isset($user['language']) ? json_encode($user['language']) : null,
                'medicare' => isset($user['medicare']) ? json_encode($user['medicare']) : null,
                'service' => isset($user['service']) ? json_encode($user['service']) : null,
                'website' => isset($user['website']) ? $user['website'] : null,
                'original_email' => isset($user['email']) ? trim($user['email']) : null,
            ));

            if(!$nutritionistImportMetaData->save())
            {
                print_r ($nutritionistImportMetaData->getErrors());
            }
        }

        echo 'done';
    }
}