<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Action</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

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
            max-width: 1000px;
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
            width:30%;
            margin: 10px;
        }
        .form .widthBlock{
            text-align: left;
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
    </style>
<!--
    <script type='text/javascript'>
        function removeFields(id){
            console.log('test'+ id);
            document.getElementById( 'field'+(id)).remove();
        }
        function addFields(){
            // Number of inputs
            var number = document.getElementById("remove_count_field").value;

            // Container <div> where dynamic content will be placed
            var container = document.getElementById("remove_dynamic_fields");

            var divContainer = document.createElement('div');
            divContainer.classList.add("input-inline");
            divContainer.id=  'field'+( Number(number) +1);

            container.appendChild(divContainer);

            var input = document.createElement('input');
            input.setAttribute('type', 'time');
            input.setAttribute('name','remove['+( Number(number) +1)+'][from]');
            divContainer.appendChild(input);

            var input2 = document.createElement('input');
            input2.setAttribute('type', 'time');
            input2.setAttribute('name','remove['+( Number(number) +1)+'][to]');
            divContainer.appendChild(input2);

            var select = document.createElement('select');
            select.setAttribute('name','remove['+( Number(number) +1)+'][day]');
            divContainer.appendChild(select);

            var option = document.createElement("option");
            option.text = "Monday";
            option.value = "Monday";
            select.appendChild(option);

            var option2 = document.createElement("option");
            option2.text = "Tuesday";
            option2.value = "Tuesday";
            select.appendChild(option2);

            var option3 = document.createElement("option");
            option3.text = "Wednesday";
            option3.value = "Wednesday";
            select.appendChild(option3);

            var option4 = document.createElement("option");
            option4.text = "Thursday";
            option4.value = "Thursday";
            select.appendChild(option4);

            var option5 = document.createElement("option");
            option5.text = "Friday";
            option5.value = "Friday";
            select.appendChild(option5);

            var remove = document.createElement('a');
            remove.text="X";
            remove.setAttribute('onclick', 'removeFields('+(Number(number)+1)+')');
            remove.classList.add("button");
            remove.id=  'removeAField';
            divContainer.appendChild(remove);

            document.getElementById("remove_count_field").value=(Number(number)+1);
        }
    </script>-->
</head>
<body>

<div class="action-page">
    <div class="form">
        <form class="action-form" method="POST" action="/preview">
            <div class="widthBlock">
                <div id="morningTime" class="cln">
                    <label class="form-title">Morning time</label>
                    <input type="time" name="morningBegin" required value="08:00"/>
                    <input type="time" name="morningEnd" required value="12:30"/>
                </div>
                <div id="afternoonTime" class="cln">
                    <label class="form-title">Afternoon time</label>
                    <input type="time" name="afternoonBegin" required value="13:30"/>
                    <input type="time" name="afternoonEnd" required value="18:00"/>
                </div>
                <div id="checkDays" class="cln">
                    <label class="form-title">Check day of week</label>
                    <br />

                    <label for="monday"> Monday</label>
                    <input type="checkbox" checked id="monday" name="days[Monday]" value="1">
                    <br />

                    <label for="tuesday"> Tuesday</label>
                    <input type="checkbox" checked id="tuesday" name="days[Tuesday]" value="1">
                    <br />

                    <label for="wednesday"> Wednesday</label>
                    <input type="checkbox" checked id="wednesday" name="days[Wednesday]" value="1">
                    <br />

                    <label for="thursday"> Thursday</label>
                    <input type="checkbox" checked id="thursday" name="days[Thursday]" value="1">
                    <br />

                    <label for="friday"> Friday</label>
                    <input type="checkbox" checked id="friday" name="days[Friday]" value="1">
                </div>
            </div><!--

-->

            <br /><br />
            <button>Preview</button>
            @csrf
        </form>
        @if ($errors->any())
            <div class="red alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div><br />
        @endif
        @if (session('error'))
            <br /> <br /><span class="red">{{ session('error') }}</div>
    @endif

</div>
</div>


</body>
</html>
