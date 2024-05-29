@extends('theme-views.layouts.app')

@section('title', \App\CPU\translate('spin_wheel_game'))

@push('css_or_js')
    <style>

.spin-section {
  font-family: Helvetica, sans-serif;
  display: flex;
  justify-content: center;
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
.box-1 .span-3 b{
  transform: translate(-50%, 50%) rotate(-270deg);
}
.box-1 .span-1 b,
.box-2 .span-1 b{
  transform: translate(-50%, -50%) rotate(185deg);
}
.box-2 .span-3 b{
  transform: translate(-50%, -50%) rotate(90deg);
}
.box-1 .span-4 b,
.box-2 .span-4 b{
  transform: translate(-50%, -50%);
}
span b{
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

@keyframes animateArrow {
  50% {
    right: -40px;
  }
}
    </style>
@endpush


@section('content')
<section class="spin-section">
    
    <div id="container-spin" class="container-spin">
        <div id="box" class="box">
            <div class="box-1">
                <span class="span-1"><b>1</b></span>
                <span class="span-2"><b>2</b></span>
                <span class="span-3"><b>3</b></span>
                <span class="span-4"><b>4</b></span>
            </div>
            <div class="box-2">
                <span class="span-1"><b>5</b></span>
                <span class="span-2"><b>6</b></span>
                <span class="span-3"><b>7</b></span>
                <span class="span-4"><b>8</b></span>
            </div>
        </div>
        <button class="spin" onclick="rotateFunc()" id="spin-btn">Spin</button>
    </div>
</section>
@endsection


<script>
   function rotateFunc(){
        let min = 1024;
        let max = 9999;
        let deg = Math.floor(Math.random() * (max-min)) + min;
        document.getElementById('box').style.transform = "rotate("+deg+"deg)";
        var elem = document.getElementById('container-spin'); // Fix: Change 'container' to 'container-spin'
        elem.classList.remove('animate');
        setTimeout(function() {
            elem.classList.add('animate');
        }, 5000);
    }
</script>

