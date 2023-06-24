<?php

######################################################
//                                                  //
//     Thanks for giving the framework a try.       //
//           Give us a start on github:             //
// https://github.com/mohamediysa/iysabox-framework //
//                                                  //
######################################################

route("/", function () {
    json([
        "message" => "Welcome"
    ]);
});

route("id1/{/^[a-zA-Z0-9-_.]+$/}/id22/{:num}", function ($id1, $id2) {
    json([
        "id1" => $id1,
        "id2" => $id2
    ]);
});


route("page_num/{:alpha_num_hyphen_dash}", function ($id) {
    json([
        "id" => $id
    ]);
});

route("page", function () {
    $data = [
        "email" => "example@gmail.com",
        "age" => 23
    ];
    view("page", $data);
});


route("user_name/{:alpha}/user_id/{:num}", function ($name, $id) {
    json([
        "user name" => $name,
        "user id" => $id
    ]);
});

// {:num} ONLY NUMS
// {:alpha} ONLY ALPHABETS
// {:alpha_num} NUMS AND ALPHABETS
// {:num_hyphen} NUM WITH -
// {:alpha_hyphen} ALPHA WITH -
// {:alpha_num_hyphen} ALPHA & NUM WITH -
// {:num_dash} NUM WITH _
// {:alpha_dash} ALPHA WITH _
// {:alpha_num_dash} ALPHA NUM WITH _
// {:num_hyphen_dash} NUM WITH - AND _
// {:alpha_hyphen_dash} ALPHA WITH - AND _
// {:alpha_num_hyphen_dash} ALPHA NUM WITH - AND _
