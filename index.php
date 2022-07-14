<?php

$userFileLimit = 10000;

if($_POST['query'] == 'file')
{
    $file = file_get_contents($_FILES['file']['tmp_name']);
    $fileData = [];
    $fileData = explode(';', $file);
    array_pop($fileData);
    for($i = 0;$i < sizeof($fileData);$i++)
    {
        $fileData[$i] = explode(',', $fileData[$i]);
    }

    $usersGroups = json_decode(file_get_contents('groups.json'), true);
    $newUsers = [];
    $editUsers = [];

    foreach($fileData as $user)
    {
        $phoneNumber = $user[0];
        if(isset($usersGroups[$phoneNumber]))
        {
            if(!isset($editUsers[$usersGroups[$phoneNumber]]))
            {
                $editUsers[$usersGroups[$phoneNumber]] = [];
            }
            array_push($editUsers[$usersGroups[$phoneNumber]], $user);
        }
        else
        {
            array_push($newUsers, $user);
        }
    }

    // edit users
    foreach(array_keys($editUsers) as $groupFileName)
    {
        $groupUsers = $editUsers[$groupFileName];
        $fileData = json_decode(file_get_contents($groupFileName), true);
        foreach($groupUsers as $user)
        {
            $phoneNumber = $user[0];
            $fileData[$phoneNumber] = [$user[1], $user[2], $user[3]];
        }
        file_put_contents($groupFileName, json_encode($fileData, \JSON_UNESCAPED_UNICODE));
    }

    // new users

    $test = [];
    for($i = 0;$i < sizeof($newUsers);$i++)
    {
        $test[$newUsers[$i][0]] = $newUsers[$i];
    }
    $newUsers = [];
    foreach($test as $user)
    {
        array_push($newUsers, $user);
    }
    echo sizeof($newUsers);

    $phoneNumberFileNames = json_decode(file_get_contents("groups.json"), true);
    $usersFiles = scandir('users');
    $newFileNumber = 0;
    if(sizeof($phoneNumberFileNames))
    {
        $newFileNumber = sizeof($usersFiles) - 2;
        $groupFileName = 'users/' . $usersFiles[sizeof($usersFiles) - 1];
        $usersData = json_decode(file_get_contents($groupFileName), true);
        while(sizeof($newUsers) && sizeof($usersData) != $userFileLimit)
        {
            $user = $newUsers[sizeof($newUsers) - 1];
            array_pop($newUsers);

            $phoneNumber = $user[0];
            $phoneNumberFileNames[$phoneNumber] = $groupFileName;
            $usersData[$phoneNumber] = [$user[1], $user[2], $user[3]];
        }
        file_put_contents($groupFileName, json_encode($usersData, \JSON_UNESCAPED_UNICODE));
    }
    while(sizeof($newUsers))
    {
        $fileData = [];
        $newFilePath = 'users/group_users_' . $newFileNumber . '.json';
        for($i = 0;$i < $userFileLimit && sizeof($newUsers);$i++)
        {
            $user = $newUsers[sizeof($newUsers) - 1];
            array_pop($newUsers);

            $phoneNumber = $user[0];

            $phoneNumberFileNames[$phoneNumber] = $newFilePath;
            $fileData[$phoneNumber] = [$user[1], $user[2], $user[3]];
        }
        file_put_contents($newFilePath, json_encode($fileData, \JSON_UNESCAPED_UNICODE));
        $newFileNumber++;
    }

    file_put_contents('groups.json', json_encode($phoneNumberFileNames, \JSON_UNESCAPED_UNICODE));
}
else if($_POST['query'] == 'export')
{
    $filesNames = scandir('users');
    $data = '';
    for($i = 2;$i < sizeof($filesNames);$i++)
    {
        if($filesNames[$i] == '.DS_Store')
        {
            continue;
        }
        $fileName = 'users/' . $filesNames[$i];
        $users = json_decode(file_get_contents($fileName), true);
        foreach(array_keys($users) as $phone_number)
        {
            $data .= $phone_number . ", " . $users[$phone_number][0] . ", " . $users[$phone_number][1] . ", " . $users[$phone_number][2] . ";\n";
        }
    }
    file_put_contents("export.csv", $data);
    header('Location: export.csv');
    exit(0);
}

?>

<style>

body {
    background: #000;
    color: white;
}

</style>