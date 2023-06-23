# iysabox-framework
a simple and lightweight php router/framework for building apis and small webpages<br>
<pre>1. clone the files into your dir
    git clone https://github.com/mohamediysa/iysabox-framework.git</pre>
<br>
<pre>2. localhost/your-dir-name</pre>
<br>
<pre>
3. open routes.php and add your routes <br>
route("path", callback)
</pre>
<br>
examples :
<pre>
route("page", function () {
    $data = [
        "email" => "example@gmail.com",
        "age" => 25
    ];
    view("page", $data); // html/page.php | pass data to a view file
});
</pre>


<pre>
route("page/user", function () {
    view("user"); // html/user.php
});
</pre>

<pre>
route("page/{id}", function ($id) {
    $data = [
        "parametre" => $id,
        "all_requests" => all_request(false),
        "get" => get("q")
    ];
    json($data, 200); //print data as a json with a status code
});

<a>localhost/iysabox/page/1?q=query&name=iysa</a>
<b>output:</b> 
{
    "parametre" : "1",
    "all" : [
        q : "query"
         name : "iysa"
     ],
      "get" : {
          "q":"query"
       }
}
</pre>
<pre>
<b>only nums</b> 
route("users/{:num}", function ($id) {
    $data = [
        "parametre 1" => $id
    ];
    json($data);
});
</pre>
<pre>
<b>only alpha</b>
route("users/{:alpha}", function ($id) {
    $data = [
        "parametre 1" => $id
    ];
    json($data);
});
</pre><pre>
<b>alpha and nums</b>
route("users/{:alpha_num}", function ($id) {
    $data = [
        "parametre 1" => $id
    ];
    json($data);
});
</pre>

<pre>
<b>using regex, one param</b>
route("page/{/^[0-9]+$/}", function ($id) {
    $data = [
        "id" => $id,
    ];
    json($data);
});
</pre>

<pre>
<b>using regex, multi params</b>
route("page/{/^[a-zA-Z]+$/}/user/{/^[0-9]+$/}", function ($p1, $p2) {
    $data = [
        "parametre 1" => $p1,
        "parametre 2" => $p2
    ];
    json($data);
});
</pre>
