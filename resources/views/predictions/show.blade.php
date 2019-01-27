@extends('layouts.app')
@section('title', 'Predictions')
@section('content')
	<div class="container">
    <h2 class="text-center">The calculated value from the regression algorithm is:</h2>
      @foreach($output as $out)
  			@if($out > '50' && $out < '100')
          <h1 class="display-4 text-center alert alert-warning text-dark"><strong>
            @php
              $out = floatval($out);
              $out = round($out, 2);
              echo $out . " µg/m3";
            @endphp
          </strong></h1>
        @elseif($out > '100')
          <h1 class="display-4 text-center alert alert-danger text-dark"><strong>
            @php
              $out = floatval($out);
              $out = round($out, 2);
              echo $out . " µg/m3";
            @endphp
          </strong></h1>
        @else
          <h1 class="display-4 text-center alert alert-success text-dark"><strong>
            @php
              $out = floatval($out);
              $out = round($out, 2);
              echo $out . " µg/m3";
            @endphp
          </strong></h1>
        @endif
  		@endforeach
    <br><br>
    <h4 class="text-center">Make another prediction now</h4>
    <br>
	</div>

  <div class="container">
    <nav class="nav nav-pills nav-justified">
        <a data-toggle="pill" class="nav-item nav-link active" href="#home"><strong>SkopjePulse Data</strong></a>
        <a class="nav-item nav-link" data-toggle="pill" href="#menu1"><strong>SkopjePulse + Weather</strong></a>
        <a class="nav-item nav-link" data-toggle="pill" href="#menu2"><strong>Classification</strong></a>
    </nav>

    <div class="tab-content">
        <div id="home" class="tab-pane fade in show active"><hr>
          @include('layouts.skopjepulse_prediction_partial')
        </div>
        <div id="menu1" class="tab-pane fade"><hr>
          @include('layouts.partial_withweather')
        </div>
        <div id="menu2" class="tab-pane fade"><hr>
          @include('layouts.classification_partial')
        </div>
    </div>
    <hr><br><br>
  </div>

  <div class="d-flex align-items-center" id="jumbo1">
    <div class="row">
      <div class="col-md-12">
        <h1 class="text-center text-dark">Regression with SkopjePulse data</h1>
        <img id="pic1" src="{{ URL::to('images/pred1.jpg') }}" class="img-responsive">
        <div class="carousel-caption">
          <p class="h4 text-dark"><strong><em>What is a regression?</em></strong></p>
          <blockquote class="blockquote text-justify text-dark"><strong>
            &emsp;In machine learning, the term regression is used to define the algorithm which function is predicting a continuous-valued attribute associated with an object. In this project the type of regression used is <em>multiple linear regression</em>. This form of algorithm belongs to <em>supervised learning</em> types of algorithm. Supervised learning is the machine learning task of learning a function that maps an input to an output based on example input-output pairs.
          </strong></blockquote>
          <p class="h4 text-dark"><strong><em>How it works?</em><strong></p>
          <blockquote class="blockquote text-dark text-justify"><strong>
            &emsp;First of all, the user has to provide the form shown above with some inputs. For example, you can enter values like Noise: 90 (dB), Temperature: 3 (&#8451;), and Humidity: 85 (%). Clicking on submit calls a function in controller which takes the entered data, and using Symphony's <em>Process</em> class creates a new process, which calls the corresponding Python script and sends the inputs as arguments. The Python script is building an appropriate regression model, where the final regressor is fitted with the data from SkopjePulse dataset, and finally we pass the entered inputs as arguments to the regressor's <em>predict</em> function, which predicts the concentration of the PM particles, and we return that value back to the Laravel's <em>Predictions</em> controller, and show that value to the screen.
          </strong></blockquote>
        </div>
      </div>
    </div>
  </div>

  <br><br><br>

  <div class="d-flex align-items-center" id="jumbo2">
    <div class="row">
      <div class="col-md-12">
        <h1 class="text-center text-dark">Regression with SkopjePulse & OpenWeatherMap data</h1>
        <img id="pic2" src="{{ URL::to('images/pred1.jpg') }}" class="img-responsive">
        <div class="carousel-caption">
          <p class="h4 text-dark"><strong><em>What is a regression?</em></strong></p>
          <blockquote class="blockquote text-justify text-dark"><strong>
            &emsp;In machine learning, the term regression is used to define the algorithm which function is predicting a continuous-valued attribute associated with an object. In this project the type of regression used is <em>multiple linear regression</em>. This form of algorithm belongs to <em>supervised learning</em> types of algorithm. Supervised learning is the machine learning task of learning a function that maps an input to an output based on example input-output pairs.
          </strong></blockquote>
          <p class="h4 text-dark"><strong><em>How it works?</em><strong></p>
          <blockquote class="blockquote text-dark text-justify"><strong>
            &emsp;First of all, the user has to provide the form shown above with some inputs. For example, you can enter values like Temperature: -2 (&#8451;), Humidity: 90 (%), Wind Speed: 1 (m/s), Wind Direction: 270 (&deg; - degrees), Clouds: 78 (%). Clicking on submit calls a function in controller which takes the entered data, and using Symphony's <em>Process</em> class creates a new process, which calls the corresponding Python script and sends the inputs as arguments. The Python script is building an appropriate regression model, where the final regressor is fitted with the data from SkopjePulse dataset, and finally we pass the entered inputs as arguments to the regressor's <em>predict</em> function, which predicts the concentration of the PM particles, and we return that value back to the Laravel's <em>Predictions</em> controller, and show that value to the screen.
          </strong></blockquote>
        </div>
      </div>
    </div>
  </div>

  <script>
    var w = window.innerWidth;
    var h = window.innerHeight;
    document.getElementById("jumbo1").style.width = w + "px";
    document.getElementById("jumbo1").style.height = h + "px";
    document.getElementById("pic1").style.width = w + "px";
    document.getElementById("pic1").style.height = h - 60 + "px";
    document.getElementById("pic1").style.filter = "blur(1px)";
    document.getElementById("pic1").style.opacity = 0.5;
    document.getElementById("jumbo2").style.width = w + "px";
    document.getElementById("jumbo2").style.height = h + "px";
    document.getElementById("pic2").style.width = w + "px";
    document.getElementById("pic2").style.height = h - 60 + "px";
    document.getElementById("pic2").style.filter = "blur(1px)";
    document.getElementById("pic2").style.opacity = 0.5;
  </script>

  <script>
    var w = window.innerWidth;
    var h = window.innerHeight;
    document.getElementById("jumbo1").style.width = w + "px";
    document.getElementById("jumbo1").style.height = h + "px";
    document.getElementById("pic1").style.width = w + "px";
    document.getElementById("pic1").style.height = h - 60 + "px";
    document.getElementById("pic1").style.filter = "blur(1px)";
    document.getElementById("pic1").style.opacity = 0.5;
    document.getElementById("jumbo2").style.width = w + "px";
    document.getElementById("jumbo2").style.height = h + "px";
    document.getElementById("pic2").style.width = w + "px";
    document.getElementById("pic2").style.height = h - 60 + "px";
    document.getElementById("pic2").style.filter = "blur(1px)";
    document.getElementById("pic2").style.opacity = 0.5;
  </script>
@endsection