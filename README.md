API:

api method: POST

In any api request you need a "query" variable that can be "file" or "export"
if "query" variable equal to "file" you gonna send a "file" variable that gonna be your .csv file for executing

for example:
$post_data = [
    "query" => "file",
    "file" => yourFile.csv
]

or

$post_data = [
    "query" => "export"
]

(you can also send your requests using postman application)

when "query" variable equal to "file", there is not any important response from host or server and your csv file just gonna executing

when "query" variable equal to "export", server or host gonna redirect you to .csv file that gonna show you a backup from all of users datas

Important:

Your .csv file gonna be like this:
----------- file start -----------

user1_phoneNumber, user1_firstName, user1_lastName, user1_currency_count;
user2_phoneNumber, user2_firstName, user2_lastName, user2_currency_count;
user3_phoneNumber, user3_firstName, user3_lastName, user3_currency_count;
user4_phoneNumber, user4_firstName, user4_lastName, user4_currency_count;
user5_phoneNumber, user5_firstName, user5_lastName, user5_currency_count;
...

----------- file stop ------------