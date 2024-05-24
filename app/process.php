<?php
require_once '../handlers/postValuesToDatabase.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{
    //Handle saving image, profileUser needs to be altered to be dynamic.. but currently not in possesion of a log-in system.
    $profileImage = null;
    $profileUser = 1;
    if (isset($_FILES['profileImage']) && $_FILES['profileImage']['error'] === UPLOAD_ERR_OK) {
        $imageTmpPath = $_FILES['profileImage']['tmp_name'];
        $imageType= $_FILES['profileImage']['type'];
        $imageName = basename($_FILES['profileImage']['name']);
        $uploadDir = '../images/profiles/';
        $profileImage = $uploadDir . $imageName;
        move_uploaded_file($imageTmpPath, $profileImage);
    }

    $data = [
        'Profile_User_idUser' => $profileUser,
        'image_file_path' => $uploadDir,
        'image_file_name' => $imageName,
        'image_file_type' => $imageType
    ];

    $userValues = new postUserValues();

    if ($userValues->saveImage($data)) {
        echo "Gegevens succesvol opgeslagen.";
    } else {
        echo "Er is een fout opgetreden bij het opslaan van de gegevens.";
    }

    //Handle saving profile values
    $description = $_POST['aboutMe'];
    $fun_fact = $_POST['hobbies'];
    $province = $_POST['whereFrom'];
    $fav_color = $_POST['favColour'];
    $fav_animal = $_POST['favAnimal'];
    $fav_season = $_POST['favSeason'];
    $starsign = $_POST['starsign'];
    $hobby_description = $_POST['hobbies'];
    $occupation = $_POST['occupation'];
    $green_flag = $_POST['greenFlag'];
    $red_flag = $_POST['redFlag'];
    $user_id = 1;

    $data = [
        'user_id' => $user_id,
        'description' => $description,
        'fun_fact' => $fun_fact,
        'province' => $province,
        'fav_color' => $fav_color,
        'fav_animal' => $fav_animal,
        'fav_season' => $fav_season,
        'starsign' => $starsign,
        'hobby_description' => $hobby_description,
        'occupation' => $occupation,
        'green_flag' => $green_flag,
        'red_flag' => $red_flag
    ];

    if ($userValues->saveHobbies($data)) {
        echo "Gegevens succesvol opgeslagen.";
    } else {
        echo "Er is een fout opgetreden bij het opslaan van de gegevens.";
    }

    //Handle saving special field values
    $user_id = 1;
    $extraFields = isset($_POST['extraFields']) ? $_POST['extraFields'] : null;

    if (is_array($extraFields)) {

        foreach ($extraFields as $index => $fieldData) {
            $title = isset($fieldData['title']) ? $fieldData['title'] : '';
            $content = isset($fieldData['content']) ? $fieldData['content'] : '';

            $data = [
                'Profile_User_idUser' => $user_id,
                'field_title' => $title,
                'field_content' => $content
            ];

            if ($userValues->saveSpecialField($data)) {
                echo "Gegevens succesvol opgeslagen.<br>";
            } else {
                echo "Er is een fout opgetreden bij het opslaan van de gegevens.<br>";
            }
        }
    } else {
        echo "Dynamic fields data is not set or is not an array.<br>";
    }
}   
?>