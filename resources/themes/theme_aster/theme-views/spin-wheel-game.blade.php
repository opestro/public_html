@extends('theme-views.layouts.app')

@section('title', \App\CPU\translate('spin_wheel_game'))

@push('css_or_js')


<meta name="csrf-token" content="{{ csrf_token() }}">

    <style>

.spin-section {
  font-family: Helvetica, sans-serif;
  display: flex;
  justify-content: center;
  flex-direction: column;
  align-items: center;
  height: 100vh;
  overflow: hidden;
  background-size: cover;
  box-sizing: border-box;
  padding: 0;
  margin: 0;
  outline: none;
}
.container-spin {
  position: relative;
  width: 500px;
  height: 500px;
}
.container-spin:after {
  position: absolute;
  content: '';
  width: 32px;
  height: 32px;
  background: url('https://upload.wikimedia.org/wikipedia/commons/thumb/0/0d/Red_Arrow_Left.svg/1200px-Red_Arrow_Left.svg.png') no-repeat;
  background-size: 32px;
  right: -30px;
  top: 50%;
  margin-bottom: 20px;
  transform: translateY(-50%);
}
.box {
  width: 100%;
  height: 100%;
  position: relative;
  font-weight: bold;
  border-radius: 50%;
  border: 10px solid black;
  overflow: hidden;
  transition: all ease 5s;
}

.span-1 {
  clip-path: polygon(0 92%, 100% 50%, 0 8%);
  background-color: #FFFB00;
  top: 120px;
  left: 0;
  width: 50%;
  height: 50%;
  display: inline-block;
  position: absolute;
}
.span-2 {
  clip-path: polygon(100% 92%, 0 50%, 100% 8%);
  background-color: #FF4FA1;
  top: 120px;
  right: 0;
  width: 50%;
  height: 50%;
  display: inline-block;
  position: absolute;
}
.span-3 {
  clip-path: polygon(50% 0%, 8% 100%, 92% 100%);
  background-color: #FFAA00;
  bottom: 0;
  left: 120px;
  width: 50%;
  height: 50%;
  display: inline-block;
  position: absolute;
}
.span-4 {
  clip-path: polygon(50% 100%, 92% 0, 8% 0);
  background-color: #22FF00;
  top: 0;
  left: 120px;
  width: 50%;
  height: 50%;
  display: inline-block;
  position: absolute;
}
.box-1 .span-3 .bold-num{
  transform: translate(-50%, -50%) rotate(-270deg);
}
.box-1 .span-1 .bold-num,
.box-2 .span-1 .bold-num{
  transform: translate(-50%, -50%) rotate(185deg);
}
.box-2 .span-3 .bold-num{
  transform: translate(-50%, -50%) rotate(90deg);
}
.box-1 .span-4 .bold-num,
.box-2 .span-4 .bold-num{
  transform: translate(-50%, -50%);
}
.bold-num{
  font-size: 24px;
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
}
.box-2{
  width: 100%;
  height: 100%;
  transform: rotate(-135deg);
}
.spin {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 75px;
  height: 75px;
  border-radius: 50%;
  border: 4px solid #FFF;
  background-color: #001AFF;
  color: #FFF;
  box-shadow: 0 5px 20px #000;
  font-weight: bold;
  font-size: 22px;
  cursor: pointer;
}
.spin:active {
  width: 70px;
  height: 70px;
  font-size: 20px;
}
.container-spin.animate:after{
  animation: animateArrow 0.7s ease infinite;
}

.result {
    font-size: 18px;
    text-align: center;
}
.spin-header {
    font-size: 28px;
    font-weight: bold;
    margin-bottom: 80px;
}   
@keyframes animateArrow {
  50% {
    right: -40px;
  }
}

@media screen and (max-width: 600px) {
    
.container-spin {
  position: relative;
  width: 250px;
  height: 250px;
}

.span-1{
    top: 58px;
}
.span-2{
    top: 58px;
}
.span-3{
    left: 58px;
    
}
.span-4{
    left: 58px;
}
 .spin {
  width: 60px;
  height: 60px;
  font-size: 15px;
}
}
    </style>
@endpush


@section('content')
 <section class="spin-section">
        <h1 class="spin-header">Spin to Win</h1>
    <div id="container-spin" class="container-spin">
        <div id="box" class="box">
            <div class="box-1">
                <span class="span-1"><b class="bold-num">5</b></span>
                <span class="span-2"><b class="bold-num">1</b></span>
                <span class="span-3"><b class="bold-num">3</b></span>
                <span class="span-4"><b class="bold-num">7</b></span>
            </div>
            <div class="box-2">
                <span class="span-1"><b class="bold-num">2</b></span>
                <span class="span-2"><b class="bold-num">6</b></span>
                <span class="span-3"><b class="bold-num">8</b></span>
                <span class="span-4"><b class="bold-num">4</b></span>
            </div>
        </div>
         @if(auth('customer')->check())
             @if($canSpin)
                <button class="spin" onclick="rotateFunc()" id="spin-btn">Spin</button>
            @else
                <button class="spin" onclick="canNotSpin()" id="spin-btn">Spin</button>
            @endif    
         @else
            <button class="spin" data-bs-toggle="modal" data-bs-target="#loginModal" id="spin-btn">Spin</button>
        @endif
    </div>
   
<div class="result" id="result"></div>


</section>
@endsection


<script>
let spun = false;


function rotateFunc() {

    if (spun) {
        canNotSpin();
        return;
    }

    let min = 1024;
    let max = 9999;
    let deg = Math.floor(Math.random() * (max - min)) + min;
    document.getElementById('box').style.transform = "rotate(" + deg + "deg)";
    var elem = document.getElementById('container-spin');
    elem.classList.remove('animate');
    
    // Disable further spins until the current spin finishes
    spun = true;
    setTimeout(function () {
        elem.classList.add('animate');
        calculateResult();
        updateLastSpinWin();
    }, 5000);
}


function calculateResult() {
    let deg = getRotationDegrees(document.getElementById('box'));

    // Calculate the sector based on the rotation angle
    let offsetDeg = (deg + 22.5) % 360;
    let sector = Math.floor(offsetDeg / 45) + 1;

    // Display the result in the 'result' div
    document.getElementById('result').innerHTML = "The wheel stopped at sector: " + sector;
}

// Function to get the rotation angle in degrees
function getRotationDegrees(element) {
    let style = window.getComputedStyle(element);
    let matrix = new WebKitCSSMatrix(style.transform);
    
    // Extract the rotation angle in degrees
    let angle = Math.atan2(matrix.m21, matrix.m22) * (180 / Math.PI);

    // Ensure the angle is positive
    angle = (angle + 360) % 360;

    return angle;
}
 function canNotSpin() {
        alert("You can only win once a month.");
        
    }

function updateLastSpinWin() {
        
        $.ajax({
            url: '{{ route('spin-game-win') }}',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                console.log(response);
            },
            error: function (error) {
                console.error(error);
            }
        });
    }
    
</script>

