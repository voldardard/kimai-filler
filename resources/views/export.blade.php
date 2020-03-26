<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Export</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

    <!-- Scripts -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script type='text/javascript'>

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
            width: 20%;
            display: inline-block;
            margin: 10px;
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

        @if ($errors->any())
            <div class="red alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div><br />
        @endif
            <div class="widthBlock input-inline form-title" id="header">
                <span class="input">From</span>
                <span class="input">To</span>
                <span class="input">Date</span>
                <span class="input">Day</span>
            </div>

            @foreach ($previews as $preview)

                <div class="article input-inline widthBlock" id="article-{{$preview->id}}">
                    <span class="input2 input-inline" id="article[{{$preview->id}}][from]" >{{ $preview->from }}</span>
                    <span class="input2 input-inline" id="article[{{$preview->to}}][to]" >{{ $preview->to }}</span>
                    <span class="input2 input-inline" id="article[{{$preview->date}}][date]" >{{ $preview->date }}</span>
                    <span class="input2 input-inline" id="article[{{$preview->id}}][day]">{{ $preview->day }}</span>

                </div>
            @endforeach




        @if (session('error'))
            <br /> <br /><span class="red">{{ session('error') }}</span></div>
    @endif

</div>
</div>


</body>
</html>
