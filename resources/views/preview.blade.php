<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Preview</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

    <!-- Scripts -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script type='text/javascript'>
        function delete_article(id){
            $.ajax({
                type: "DELETE",
                url: "{{ $unique_id }}/"+id,
                data:{
                    "_token": "{{ csrf_token() }}"
                },
                success: function(msg){
                    document.getElementById( 'article-'+(id)).remove();
                    console.log(msg);
                }
            });
        }
        function save_article(id){

            var article = {};
            article.id = id;
            article._token = "{{ csrf_token() }}";
            article.date = document.getElementsByName('article['+id+'][date]')[0].value;
            article.from = document.getElementsByName('article['+id+'][from]')[0].value;
            article.to = document.getElementsByName('article['+id+'][to]')[0].value;

            $.ajax({
                type: "PATCH",
                url: "{{ $unique_id }}/"+id,
                dataType: 'json',
                data: article,
                success: function(msg){
                    document.getElementById('article['+id+'][day]').innerHTML=msg.day;
                    save_button = document.getElementById("save");

                    if(Number(save_button.value) > 1 ){
                        number =(Number(save_button.value)- 1);
                        save_button.value = number.toString();
                    }else{
                        save_button.value = "0";
                        if (save_button.classList.contains("disabled-button"))
                            save_button.classList.remove("disabled-button");
                        if(save_button.disabled===true)
                            save_button.disabled = false;
                    }
                    save_article_button = document.getElementById("save-"+id);
                    save_article_button.classList.add("disabled-button");

                    console.log(msg);
                }
            });
        }
        function changed_article(article_id){
            save_article_button = document.getElementById("save-"+article_id);
            if( save_article_button.classList.contains("disabled-button")){

                save_article_button.classList.remove("disabled-button");
                save_button = document.getElementById("save");
                save_button.classList.add("disabled-button");
                save_button.disabled = true;
                number =(Number(save_button.value)+ 1);
                save_button.value = number.toString();

            }
        }
        function add_article(){
            var article = {};
            article._token = "{{ csrf_token() }}";
            article.date = document.getElementsByName('new[date]')[0].value;
            article.from = document.getElementsByName('new[from]')[0].value;
            article.to = document.getElementsByName('new[to]')[0].value;
            $.ajax({
                type: "PUT",
                url: "{{ $unique_id }}/new",
                dataType: 'json',
                data: article,
                success: function(msg){
                    // Container <div> where dynamic content will be placed
                    var container = document.getElementById("mainForm");

                    var divContainer = document.createElement('div');
                    divContainer.classList.add("article");
                    divContainer.classList.add("input-inline");
                    divContainer.classList.add("widthBlock");
                    divContainer.classList.add("addedArticle");
                    divContainer.id=  'article-'+msg.article_id;
                    container.appendChild(divContainer);

                    var input = document.createElement('input');
                    input.setAttribute('type', 'time');
                    input.setAttribute('onchange', 'changed_article('+msg.article_id+')');
                    input.setAttribute('name','article['+msg.article_id+'][from]');
                    input.value = article.from;
                    divContainer.appendChild(input);

                    var input2 = document.createElement('input');
                    input2.setAttribute('type', 'time');
                    input2.setAttribute('onchange', 'changed_article('+msg.article_id+')');
                    input2.setAttribute('name','article['+msg.article_id+'][to]');
                    input2.value = article.to;
                    divContainer.appendChild(input2);

                    var input3 = document.createElement('input');
                    input3.setAttribute('type', 'date');
                    input3.setAttribute('onchange', 'changed_article('+msg.article_id+')');
                    input3.setAttribute('name','article['+msg.article_id+'][date]');
                    input3.value = article.date;
                    divContainer.appendChild(input3);

                    var span = document.createElement('span');
                    span.setAttribute('onchange', 'changed_article('+msg.article_id+')');
                    span.id='article['+msg.article_id+'][day]';
                    span.classList.add("input2");
                    span.classList.add("input-inline");
                    span.innerHTML=msg.day;
                    divContainer.appendChild(span);

                    var a = document.createElement('a');
                    a.classList.add("button");
                    a.setAttribute('onclick', 'save_article('+msg.article_id+')');
                    a.innerHTML='Save';
                    divContainer.appendChild(a);

                    var a2 = document.createElement('a');
                    a2.classList.add("button");
                    a2.setAttribute('onclick', 'delete_article('+msg.article_id+')');
                    a2.innerHTML='Delete';
                    divContainer.appendChild(a2);

                    var new_from = document.getElementsByName("new[from]")[0];
                    var new_to = document.getElementsByName("new[to]")[0];
                    var new_date = document.getElementsByName("new[date]")[0];
                    new_from.value='';
                    new_to.value='';
                    new_date.value='';

                    console.log(msg);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR.responseJSON);
                    msg=jqXHR.responseJSON;

                    var br = document.createElement('br');
                    var span = document.createElement('span');
                    span.classList.add("red");
                    span.innerHTML=msg.message;
                    var container = document.getElementById("new");
                    container.appendChild(br);
                    container.appendChild(br);
                    container.appendChild(span);
                }
            });
        }
    </script>

    <!-- Styles -->
    <style>
        html, body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Nunito', sans-serif;
            font-weight: 200;
            height: 100vh;
            margin: 0;
        }

        .links > a {
            color: #636b6f;
            padding: 0 25px;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }
        body{
            font-family: "Roboto", sans-serif;
        }
        .form {
            position: relative;
            z-index: 1;
            background: #FFFFFF;
            max-width: 800px;
            margin: auto;
            padding: 45px;
            text-align: center;
            box-shadow: 0 0 20px 0 rgba(0, 0, 0, 0.2), 0 5px 5px 0 rgba(0, 0, 0, 0.24);
        }
        .form input {
            font-family: "Roboto", sans-serif;
            outline: 0;
            background: #f2f2f2;
            width: 100%;
            border: 0;
            margin: 0 0 15px;
            padding: 15px;
            box-sizing: border-box;
            font-size: 14px;
        }
        .form button, .form .button{
            font-family: "Roboto", sans-serif;
            text-transform: uppercase;
            outline: 0;
            background-color: #151515;
            width: 100%;
            border: 0;
            padding: 15px;
            color: #FFFFFF;
            font-size: 14px;
            transition: all;
            cursor: pointer;
        }
        .form button:hover,.form button:active,.form button:focus,
        .form .button:hover,.form .button:active,.form .button:focus{
            background: #434343;
        }
        .form .disabled-button{
            cursor: default;
            background: #434343;
        }
        .form .message {
            margin: 15px 0 0;
            color: #b3b3b3;
            font-size: 12px;
        }
        .form .message a {
            color: #151515;
            text-decoration: none;
            font-weight: bolder;
        }
        .form .form-title{
            color: black;
            margin-bottom: 10px;
            display: inline-block;
        }
        .form .cln {
            float: left;
            margin: 0 1.5%;
            width: 30%;
        }
        #checkDays input{
            width: 10%;
        }
        #checkDays label{
            display: inline-block;
            width:30%;
        }
        #checkDays .form-title{
            width:100%;
        }
        .form .input-inline{
            width: 100%;
            display: inline-block;
        }
        .form .input-inline input, .form .input-inline select, .form .widthBlock .input{
            width: 20%;
            margin: 10px;
            display: inline-block;
        }
        .form .widthBlock{
            text-align: left;
        }
        .form .article{
            display:block;
            width:100%;
        }
        .form .article input, .form .article .input{
            width:20%;
            display:inline-block;
        }
        .form .article .input2{
            width:12%;
            display:inline-block;
        }
        .form .addedArticle input, .form .addedArticle span{
            margin-left: 11px;
        }
        .form .addedArticle a{
            margin-left: 3px;
        }
        .form .spacer{
            padding: 5px;
            display: inline-block;
            font-size: larger;
        }
        .red{
            color:red;
            font-weight: bolder;
            text-align: center;
        }
        .green{
            color:green;
            font-weight: bolder;
            text-align: center;
        }
        .orange{
            color: rgb(249, 190, 18);
        }
        .grey{
            color: #646460;
            cursor: auto;
        }
        .bold{
            font-weight: bolder;
        }
    </style>
</head>
<body>

<div class="action-page">
    <div class="form">
        <div>
            <span class="orange bold spacer" id="last_insert_date_from">Last inserted FROM event in KIMAI: <span class="grey">{{ session('KIMAI_LAST_INSERT_FROM_DATE') }} at {{ session('KIMAI_LAST_INSERT_FROM') }}</span></span><br />
            <span class="orange bold spacer" id="last_insert_date_to">Last inserted TO event in KIMAI: <span class="grey">{{ session('KIMAI_LAST_INSERT_END_DATE') }} at {{ session('KIMAI_LAST_INSERT_END') }}</span></span><br />
        </div><br />

        <div class="article input-inline widthBlock" id="new" >
            <input type="time" name="new[from]"  />
            <input type="time" name="new[to]"/>
            <input type="date" name="new[date]" value="" />
            <a class="button" onclick="add_article()">Add new element</a>
        </div>
        @if ($errors->any())
            <div class="red alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div><br />
        @endif
        <form class="action-form" method="POST" action="/preview/{{ $unique_id }}/submit">
            <div class="widthBlock input-inline form-title" id="header">
                <span class="input">From</span>
                <span class="input">To</span>
                <span class="input">Date</span>
                <span class="input">Day</span>
            </div>

        @foreach ($previews as $preview)

            <div class="article input-inline widthBlock" id="article-{{$preview->id}}">
                <input type="time" onchange="changed_article({{$preview->id}})" name="article[{{$preview->id}}][from]" value="{{ $preview->from }}" />
                <input type="time" onchange="changed_article({{$preview->id}})" name="article[{{$preview->id}}][to]" value="{{ $preview->to }}" />
                <input type="date" onchange="changed_article({{$preview->id}})" name="article[{{$preview->id}}][date]" value="{{ $preview->date }}" />
                <span class="input2 input-inline" id="article[{{$preview->id}}][day]">{{ $preview->day }}</span>

                <a class="button disabled-button" id="save-{{$preview->id}}" onclick="save_article({{$preview->id}})">Save</a>
                <a class="button" onclick="delete_article({{$preview->id}})">Delete</a>

            </div>
        @endforeach
            <div id="mainForm"></div>
            <br />
            <button id="save" value="0" class="button" >save them to kimai server</button>
            @csrf
        </form>



        @if (session('error'))
            <br /> <br /><span class="red">{{ session('error') }}</span></div>
    @endif

</div>
</div>


</body>
</html>
