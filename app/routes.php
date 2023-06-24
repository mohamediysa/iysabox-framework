<?php
errors(); //set to false to disable errors

######################################################
//                                                  //
//     Thanks for giving the framework a try.       //
//           Give us a start on github:             //
// https://github.com/mohamediysa/iysabox-framework //
//                                                  //
######################################################


route("/", function () {
    json([
        "message" => "Welcome to  iysabox"
    ]);
});

route("page", function () {
    $data = [
        "email" => "example@gmail.com",
        "age" => 23
    ];
    view("page", $data); // html/page.php
});
