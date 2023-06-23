<?php
display_errors(false);



//////////////////////////////
//                          //
// route ("path", callback);//
//                          //
//////////////////////////////



route("/", function () {
    json([
        "message" => "Welcome to the box"
    ]);
});



route("page", function () {
    $data = [
        "email" => "example@gmail.com",
        "age" => 25
    ];
    view("page", $data); // html/page.php | pass data to a view file
});



route("page/user", function () {
    view("user"); // html/user.php
});


// http://localhost/iysabox/page/1?q=query&name=iysa
// output : 
// {
//     "parametre" : "1",
//     "all" : [
//         q : "query"
//         name : "iysa"
//     ],
//      "get" : {
//          "q":"query"
//       }
// }
route("page/{id}", function ($id) {
    $data = [
        "parametre" => $id,
        "all_requests" => all_request(false),
        "get" => get("q")
    ];
    json($data, 200); //print data as a json with a status code
});



//only nums 
route("users/{:num}", function ($id) {
    $data = [
        "parametre 1" => $id
    ];
    json($data);
});

//only alpha
route("users/{:alpha}", function ($id) {
    $data = [
        "parametre 1" => $id
    ];
    json($data);
});

//alpha and nums
route("users/{:alpha_num}", function ($id) {
    $data = [
        "parametre 1" => $id
    ];
    json($data);
});

route("users/{:alpha_num}", function ($id) {
    $data = [
        "parametre 1" => $id
    ];
    json($data);
});




//custom regex
route("page/{/^[0-9]+$/}", function ($id) {
    $data = [
        "id" => $id,
    ];
    json($data);
});


route("page/{/^[a-zA-Z]+$/}/user/{/^[0-9]+$/}", function ($id, $id2) {
    $data = [
        "parametre 1" => $id,
        "parametre 2" => $id2
    ];
    json($data);
});
