@extends('theme-views.layouts.app')

@section('title',\App\CPU\translate('Our Offers'))

@push('css_or_js')


    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Lato:300:400" rel="stylesheet">
    <link href="{{ asset('resources/themes/theme_aster/public/assets/css/our_offers_style.css') }}" rel="stylesheet">

    <meta property="og:image" content="{{asset('storage/app/public/company')}}/{{$web_config['web_logo']->value}}"/>
    <meta property="og:title" content="About {{$web_config['name']->value}} "/>
    <meta property="og:url" content="{{env('APP_URL')}}">
    <meta property="og:description" content="{!! substr($web_config['about']->value,0,100) !!}">

    <meta property="twitter:card" content="{{asset('storage/app/public/company')}}/{{$web_config['web_logo']->value}}"/>
    <meta property="twitter:title" content="about {{$web_config['name']->value}}"/>
    <meta property="twitter:url" content="{{env('APP_URL')}}">
    <meta property="twitter:description" content="{!! substr($web_config['about']->value,0,100) !!}">
    
    <style>
    
   body {
  -webkit-font-smoothing: antialiased;
  margin: 0;
}



.section1 {
  color: #7a90ff;
  padding: 2em 0;
  margin-top: -15px;
  position: relative;
  -webkit-font-smoothing: antialiased;
  z-index: 1;
  width: 100%; /* Ensures the section spans the full width */
  margin-left: auto; /* Centers the content */
  margin-right: auto; /* Centers the content */
  max-width: 100%; /* Ensures the content doesn't exceed the screen width */
}

.pricing {
  display: -webkit-flex;
  display: flex;
  -webkit-flex-wrap: wrap;
  flex-wrap: wrap;
  -webkit-justify-content: center;
  justify-content: center;
  width: 100%;
  margin: 0 auto;
}

.pricing-item {
  position: relative;
  display: -webkit-flex;
  display: flex;
  -webkit-flex-direction: column;
  flex-direction: column;
  -webkit-align-items: stretch;
  align-items: stretch;
  text-align: center;
  -webkit-flex: 0 1 330px;
  flex: 0 1 330px;
}



.pricing-feature-list {
  text-align: left;
 
}

.button2 {
    display: inline-block;
    padding: 10px 20px;
    font-size: 16px;
    text-align: center;
    margin-top:10px;
    text-decoration: none;
    border-radius: 30px; /* Adjust the radius as needed */
    border: 2px solid transparent; /* Change the border color */
    color: #ffffff;
    background: linear-gradient(135deg, #999999, #666666);

    cursor: pointer;
    transition: background-color 0.3s, color 0.3s, border-color 0.3s;
}



.button1 {
    display: inline-block;
    padding: 10px 20px;
    font-size: 16px;
    text-align: center;
    margin-top:10px;
    text-decoration: none;
    border-radius: 30px; /* Adjust the radius as needed */
    border: 2px solid transparent; /* Change the border color */
    color: #ffffff;
    background: linear-gradient(135deg, #ff6666, #ff3333);
    cursor: pointer;
    transition: background-color 0.3s, color 0.3s, border-color 0.3s;
}



.button3 {
    display: inline-block;
    padding: 10px 20px;
    font-size: 16px;
    text-align: center;
    margin-top:10px;
    text-decoration: none;
    border-radius: 30px; /* Adjust the radius as needed */
     border: 2px solid transparent; /* Change the border color */
    color: #ffffff;
    background: linear-gradient(135deg, #ffae42, #ff8243);
    cursor: pointer;
    transition: background-color 0.3s, color 0.3s, border-color 0.3s;
}




.pricing-palden .pricing-item {
  font-family: "Open Sans", sans-serif;
  cursor: default;
  color: #84697c;
  background: #fff;
  box-shadow: 0 0 10px rgba(46, 59, 125, 0.23);
  border-radius: 20px 20px 10px 10px;
  margin: 1em;
}

@media screen and (min-width: 66.25em) {
  .pricing-palden .pricing-item {
    margin: 1em -0.5em;
  }
  .pricing-palden .pricing__item--featured {
    margin: 0;
    z-index: 10;
    box-shadow: 0 0 20px rgba(46, 59, 125, 0.23);
  }
}

.pricing-palden .pricing-deco {
  border-radius: 10px 10px 0 0;
  background: linear-gradient(135deg, #4097f9, #0af0c7);
  padding: 4em 0 9em;
  position: relative;
}

.pricing-deco {
  /* Existing styles for pricing-deco */

  /* Add the following styles for the image */
  position: relative;
}


.pricing-palden .pricing-deco-img {
  position: absolute;
  bottom: 0;
  left: 0;
  width: 100%;
  height: 160px;
}

.pricing-palden .pricing-title {
  font-size: 0.75em;
  margin: 0;
  text-transform: uppercase;
  letter-spacing: 5px;
  color: #fff;
}

.pricing-palden .deco-layer {
  -webkit-transition: -webkit-transform 0.5s;
  transition: transform 0.5s;
}


.pricing-palden .pricing-item:hover .deco-layer--1 {
  -webkit-transform: translate3d(15px, 0, 0);
  transform: translate3d(15px, 0, 0);
}

.pricing-palden .pricing-item:hover .deco-layer--2 {
  -webkit-transform: translate3d(-15px, 0, 0);
  transform: translate3d(-15px, 0, 0);
}

.pricing-palden .icon {
  font-size: 2.5em;
}

  .offer-descriptions {
    background-color: white;
    display: flex;
    margin-top: -30px;
    flex-wrap: wrap; /* Allow containers to wrap to the next line on smaller screens */
    justify-content: space-between;
    font-family: 'Poppins', sans-serif;
    padding: 20px; /* Adjust padding as needed */
  }

  .description-container {
    width: calc(30% - 20px); /* Adjust the width and consider padding */
    margin-bottom: 50px; /* Add margin between containers */
    margin-top: 50px; /* Add margin between containers */
    padding: 20px;
   
    border-radius: 10px; /* Add border-radius for rounded corners */
box-shadow: rgba(0, 0, 0, 0.3) 0px 19px 38px, rgba(0, 0, 0, 0.22) 0px 15px 12px;
  }

  /* Add specific styles for each offer */
 .silver {
  background: linear-gradient(135deg, rgba(153, 153, 153, 0.1), rgba(102, 102, 102, 0.4));
}

.supreme {
  background: linear-gradient(135deg, rgba(255, 174, 66, 0.1), rgba(255, 130, 67, 0.4));
}

.legendary {
  background: linear-gradient(135deg, rgba(255, 102, 102, 0.1), rgba(255, 51, 51, 0.4));
}

  /* Change text color and make only the heading bold */
  .description-container h2 {
    color: black;
    font-weight: bold;
    font-family: 'Poppins', sans-serif; /* Specify the font for the heading */
    margin-bottom:15px;
  }

  .description-container p {
    color: black;
    font-weight: 500; /* Set the font weight to normal for paragraphs */
    font-family: 'Poppins', sans-serif; /* Specify the font for paragraphs */
  }

  

  @media (max-width: 768px) {
    .description-container {
      width: calc(100% - 20px); /* Full width on smaller screens */
      
      
      
    }
    
@media (max-width: 768px) {
  .pricing-item:nth-child(2) .pricing-deco::before,
  .pricing-item:nth-child(3) .pricing-deco::before {
    width: 120px; /* Adjust the width for smaller screens */
    height: 120px; /* Adjust the height for smaller screens */
    top: -30px; /* Adjust the top position for smaller screens if needed */
    right: -60px; /* Adjust the right position for smaller screens if needed */
  }
}

/* For smaller screens */
@media (max-width: 480px) {
  .pricing-item:nth-child(2) .pricing-deco::before,
  .pricing-item:nth-child(3) .pricing-deco::before {
    width: 100px; /* Adjust the width for even smaller screens */
    height: 100px; /* Adjust the height for even smaller screens */
    top: -30px; /* Adjust the top position for even smaller screens if needed */
    right: -20px; /* Adjust the right position for even smaller screens if needed */
  }
}    
    
.section2 {
    padding: 2em 0;
    margin-top: -15px;
    position: relative;
    -webkit-font-smoothing: antialiased;
    z-index: 10;
    width: 100%; /* Ensure the section spans the full width */
    margin-left: auto; /* Center the content */
    margin-right: auto; /* Center the content */
    max-width: 100%; /* Ensure the content doesn't exceed the screen width */
  }
  }

.pricing-palden .pricing-price {
  font-size: 5em;
  font-weight: bold;
  padding: 0;
  color: #fff;
  margin: 0 0 0.25em 0;
  line-height: 0.75;
}

.pricing-palden .pricing-currency {
  font-size: 0.15em;
  vertical-align: top;
}

.pricing-palden .pricing-period {
  font-size: 0.15em;
  padding: 0 0 0 0.5em;
  font-style: italic;
}

.pricing-palden .pricing__sentence {
  font-weight: bold;
  margin: 0 0 1em 0;
  padding: 0 0 0.5em;
}




.pricing-palden .pricing-feature-list {
   margin-top: -80px;
   margin-bottom: -30px;
   padding: 0.25em 2em 2.5em 1em;
   padding-left: 20px;
   list-style: none;
   text-align: left;
   position: relative;
   z-index: 1;
}


.pricing-palden .pricing-feature {
  padding: 0.3em 0;
}

.pricing-feature-list .pricing-feature {
  color: #000;
  font-weight: 500;
  display: flex;
  align-items: center;
}

.pricing-palden .pricing-action {
  font-weight: bold;
  margin: auto 3em 2em 3em;
  padding: 1em 2em;
  color: #fff;
  border-radius: 30px;
background: linear-gradient(135deg, rgba(255, 178, 102, 0.9), rgba(255, 127, 51, 1));
  -webkit-transition: background-color 0.3s;
  transition: background-color 0.3s;
}


.pricing-palden .pricing-item--featured .pricing-deco {
  padding: 5em 0 8.885em 0;
}



/* Background Color Transition */
.header1 {
  position: relative;
  text-align: center;
  background-image: radial-gradient(
    circle at 20% 50%,
    rgba(255, 77, 0, 0.3) 0%,
    rgba(255, 77, 0, 0.1) 70%
  ),
  radial-gradient(
    circle at 80% 50%,
    rgba(255, 77, 0, 0.3) 0%,
    rgba(255, 77, 0, 0.1) 70%
  ),
  radial-gradient(
    circle at 39% 47%,
    rgba(255, 77, 0, 0.08) 0%,
    rgba(255, 77, 0, 0.08) 33.333%,
    rgba(72, 72, 72, 0.08) 33.333%,
    rgba(72, 72, 72, 0.08) 66.666%,
    rgba(36, 36, 36, 0.08) 66.666%,
    rgba(36, 36, 36, 0.08) 99.999%
  ),
  radial-gradient(
    circle at 53% 74%,
    rgba(255, 77, 0, 0.08) 0%,
    rgba(255, 77, 0, 0.08) 33.333%,
    rgba(202, 202, 202, 0.08) 33.333%,
    rgba(202, 202, 202, 0.08) 66.666%,
    rgba(221, 221, 221, 0.08) 66.666%,
    rgba(221, 221, 221, 0.08) 99.999%
  ),
  radial-gradient(
    circle at 14% 98%,
    rgba(255, 77, 0, 0.08) 0%,
    rgba(255, 77, 0, 0.08) 33.333%,
    rgba(96, 96, 96, 0.08) 33.333%,
    rgba(96, 96, 96, 0.08) 66.666%,
    rgba(7, 7, 7, 0.08) 66.666%,
    rgba(7, 7, 7, 0.08) 99.999%
  ),
  linear-gradient(45deg, rgb(255, 77, 0), rgb(20, 32, 127));
  color: white;
}


.inner-header {
  height: 100%;
  width: 100%;
  margin: 0;
  padding: 0;
}

.flex {
  /*Flexbox for containers*/
  justify-content: center;
  align-items: center;
  text-align: center;
}

.waves {
  position: relative;
  width: 100%;
  height: 150vh;
  margin-bottom: -7px; /*Fix for safari gap*/
  min-height: 100px;
  max-height: 250px;
}

/* Animation */

.parallax > use {
  animation: move-forever 25s cubic-bezier(0.55, 0.5, 0.45, 0.5) infinite;
}
.parallax > use:nth-child(1) {
  animation-delay: -2s;
  animation-duration: 7s;
}
.parallax > use:nth-child(2) {
  animation-delay: -3s;
  animation-duration: 10s;
}
.parallax > use:nth-child(3) {
  animation-delay: -4s;
  animation-duration: 13s;
}


.parallax > use:nth-child(4) {
  animation-delay: -5s;
  animation-duration: 20s;
}
@keyframes move-forever {
  0% {
    transform: translate3d(-90px, 0, 0);
  }
  100% {
    transform: translate3d(85px, 0, 0);
  }
}




    </style>
    
    
    
    
@endpush

@section('content')

<section class="section1">
  <div class="header1">
        <div class="inner-header flex">
            <section class="section1" style="direction: ltr;">
                  <center><h1 style="font-size: 2.5em;font-family: 'Open Sans', sans-serif; color: white; margin-bottom: 50px;margin-top: 20px;">{{ translate('Monthly_Offers_NicheN+')}}</h1></center>
                    <div class="pricing pricing-palden">
                        <div class="pricing-item features-item ja-animate" data-animation="move-from-bottom" data-delay="item-0" style="min-height: 570px;">
                            <div class="pricing-deco" style="background: linear-gradient(135deg, #999999, #666666);">
                                <svg class="pricing-deco-img" enable-background="new 0 0 300 100" height="100px" id="Layer_1" preserveAspectRatio="none" version="1.1" viewBox="0 0 300 100" width="300px" x="0px" xml:space="preserve" y="0px">
                                    <path class="deco-layer deco-layer--1" d="M30.913,43.944c0,0,42.911-34.464,87.51-14.191c77.31,35.14,113.304-1.952,146.638-4.729c48.654-4.056,69.94,16.218,69.94,16.218v54.396H30.913V43.944z" fill="#FFFFFF" opacity="0.6"></path>
                                    <path class="deco-layer deco-layer--2" d="M-35.667,44.628c0,0,42.91-34.463,87.51-14.191c77.31,35.141,113.304-1.952,146.639-4.729c48.653-4.055,69.939,16.218,69.939,16.218v54.396H-35.667V44.628z" fill="#FFFFFF" opacity="0.6"></path>
                                    <path class="deco-layer deco-layer--3" d="M43.415,98.342c0,0,48.283-68.927,109.133-68.927c65.886,0,97.983,67.914,97.983,67.914v3.716H42.401L43.415,98.342z" fill="#FFFFFF" opacity="0.7"></path>
                                    <path class="deco-layer deco-layer--4" d="M-34.667,62.998c0,0,56-45.667,120.316-27.839C167.484,57.842,197,41.332,232.286,30.428c53.07-16.399,104.047,36.903,104.047,36.903l1.333,36.667l-372-2.954L-34.667,62.998z" fill="#FFFFFF"></path>
                                </svg>
                                <div class="pricing-price"><span class="pricing-currency" style="direction: ltf;">{{ translate('Dinar')}}</span>1200
                                    <span class="pricing-period">{{ translate('/_month')}}</span>
                                </div>
                                <h3 class="pricing-title">Basic</h3>
                            </div>
                           <ul class="pricing-feature-list">
                               
                                @if(Session::get('direction') === "ltr" && app()->getLocale() === "en")
                                    <li class="pricing-feature">✅&nbsp;Store + Seller Panel</li>
                                    <li class="pricing-feature">✅&nbsp;Product listing limit: 50</li>
                                    <li class="pricing-feature">✅&nbsp;Add your logo and banner</li>
                                    <li class="pricing-feature">❌&nbsp;Products boost</li>
                                    <li class="pricing-feature">❌&nbsp;Featured on Homepage</li>
                                    
                                    <li class="pricing-feature">❌&nbsp;Recommended shop</li>
                                    <li class="pricing-feature">❌&nbsp;Order Confirmation Call</li>
                                    <li class="pricing-feature">❌&nbsp;Warehousing</li>
                                @elseif(Session::get('direction') === "ltr" && app()->getLocale() === "fr")
                                    <li class="pricing-feature">✅&nbsp;Boutique + Panel Vendeur</li>
                                    <li class="pricing-feature">✅&nbsp;Limite de produits : 50</li>
                                    <li class="pricing-feature">✅&nbsp;Ajoutez votre logo et bannière</li>
                                    <li class="pricing-feature">❌&nbsp;Mise en avant des produits</li>
                                    <li class="pricing-feature">❌&nbsp;Mis en avant sur la page d'accueil</li>
                                    <li class="pricing-feature">❌&nbsp;Boutique recommandée</li>
                                    <li class="pricing-feature">❌&nbsp;Appel de confirmation de commande</li>
                                    <li class="pricing-feature">❌&nbsp;Entreposage</li>
                                @elseif(Session::get('direction') === "rtl")
                                    <li class="pricing-feature" style="direction: rtl;">✅&nbsp;متجر + لوحة البائع</li>
                                    <li class="pricing-feature" style="direction: rtl;">✅&nbsp;حد القائمة المنتجات: 50</li>
                                    <li class="pricing-feature" style="direction: rtl;">✅&nbsp;إضافة شعارك وشارتك</li>
                                    <li class="pricing-feature" style="direction: rtl;">❌&nbsp;تعزيز المنتجات</li>
                                    <li class="pricing-feature" style="direction: rtl;">❌&nbsp;مميزة على الصفحة الرئيسية</li>
                                    <li class="pricing-feature" style="direction: rtl;">❌&nbsp;متجر مُوصى به</li>
                                    <li class="pricing-feature" style="direction: rtl;">❌&nbsp;مكالمة تأكيد الطلب</li>
                                    <li class="pricing-feature" style="direction: rtl;">❌&nbsp;التخزين</li>
                                @endif
                            </ul>

                          
                <form action="{{ route('shop.apply') }}" method="get" target="">
                  
                <button type="submit" class="button2">{{ translate('Select_Plan')}}</button>
              </form>
                          
                        </div>
                        <div class="pricing-item features-item ja-animate pricing__item--featured" data-animation="move-from-bottom" data-delay="item-1" style="min-height: 570px;">
                            <div class="pricing-deco" style="background: linear-gradient(135deg, #ffae42, #ff8243);
            
            ">
                                <svg class="pricing-deco-img" enable-background="new 0 0 300 100" height="100px" id="Layer_1" preserveAspectRatio="none" version="1.1" viewBox="0 0 300 100" width="300px" x="0px" xml:space="preserve" y="0px">
                                    <path class="deco-layer deco-layer--1" d="M30.913,43.944c0,0,42.911-34.464,87.51-14.191c77.31,35.14,113.304-1.952,146.638-4.729c48.654-4.056,69.94,16.218,69.94,16.218v54.396H30.913V43.944z" fill="#FFFFFF" opacity="0.6"></path>
                                    <path class="deco-layer deco-layer--2" d="M-35.667,44.628c0,0,42.91-34.463,87.51-14.191c77.31,35.141,113.304-1.952,146.639-4.729c48.653-4.055,69.939,16.218,69.939,16.218v54.396H-35.667V44.628z" fill="#FFFFFF" opacity="0.6"></path>
                                    <path class="deco-layer deco-layer--3" d="M43.415,98.342c0,0,48.283-68.927,109.133-68.927c65.886,0,97.983,67.914,97.983,67.914v3.716H42.401L43.415,98.342z" fill="#FFFFFF" opacity="0.7"></path>
                                    <path class="deco-layer deco-layer--4" d="M-34.667,62.998c0,0,56-45.667,120.316-27.839C167.484,57.842,197,41.332,232.286,30.428c53.07-16.399,104.047,36.903,104.047,36.903l1.333,36.667l-372-2.954L-34.667,62.998z" fill="#FFFFFF"></path>
                                </svg>
                                <div class="pricing-price" style="direction: ltr;"><span class="pricing-currency">{{ translate('Dinar')}}</span>2200
                                    <span class="pricing-period">{{ translate('/_month')}}</span>
                                </div>
                                <h3 class="pricing-title">Prime</h3>
                            </div>
                          
                            <ul class="pricing-feature-list">
                                @if(Session::get('direction') === "ltr" && app()->getLocale() === "en")
                                    <li class="pricing-feature">✅&nbsp;Store + Seller Panel</li>
                                    <li class="pricing-feature">✅&nbsp;Product listing limit: 150</li>
                                    <li class="pricing-feature">✅&nbsp;Add your logo and banner</li>
                                    <li class="pricing-feature">❌&nbsp;Products boost</li>  
                                    <li class="pricing-feature">✅&nbsp;Delivery</li>
                                    <li class="pricing-feature">✅&nbsp;Featured on Homepage</li>
                                    <li class="pricing-feature">✅&nbsp;Recommended shop</li>
                                    <li class="pricing-feature">❌&nbsp;Order Confirmation Call</li>
                                    <li class="pricing-feature">❌&nbsp;Warehousing</li>
                                @elseif(Session::get('direction') === "ltr" && app()->getLocale() === "fr")
                                    <li class="pricing-feature">✅&nbsp;Boutique + Panel Vendeur</li>
                                    <li class="pricing-feature">✅&nbsp;Limite de produits : 150</li>
                                    <li class="pricing-feature">✅&nbsp;Ajoutez votre logo et bannière</li>
                                    <li class="pricing-feature">❌&nbsp;Mise en avant des produits</li>  
                                    <li class="pricing-feature">✅&nbsp;Livraison</li>
                                    <li class="pricing-feature">✅&nbsp;Mis en avant sur la page d'accueil</li>
                                    <li class="pricing-feature">✅&nbsp;Butique recommandée</li>
                                    <li class="pricing-feature">❌&nbsp;Appel de confirmation de commande</li>
                                    <li class="pricing-feature">❌&nbsp;Entreposage</li>
                                @elseif(Session::get('direction') === "rtl")
                                    <li class="pricing-feature" style="direction: rtl;">✅&nbsp;متجر + لوحة البائع</li>
                                    <li class="pricing-feature" style="direction: rtl;">✅&nbsp;حد القائمة المنتجات: 150</li>
                                    <li class="pricing-feature" style="direction: rtl;">✅&nbsp;إضافة شعارك وشارتك</li>
                                    <li class="pricing-feature" style="direction: rtl;">❌&nbsp;تعزيز المنتجات</li>
                                    <li class="pricing-feature" style="direction: rtl;">✅&nbsp;خدمة التوصيل</li>
                                    <li class="pricing-feature" style="direction: rtl;">✅&nbsp;مميزة على الصفحة الرئيسية</li>
                                    <li class="pricing-feature" style="direction: rtl;">✅&nbsp;متجر مُوصى به</li>
                                    <li class="pricing-feature" style="direction: rtl;">❌&nbsp;مكالمة تأكيد الطلب</li>
                                    <li class="pricing-feature" style="direction: rtl;">❌&nbsp;التخزين</li>
                                @endif
                            </ul>

                            <form action="{{ route('shop.apply') }}" method="get" target="">
                  
                                <button type="submit" class="button3">{{ translate('Select_Plan')}}</button>
                            </form>
                          
                        </div>
                        <div class="pricing-item features-item ja-animate" data-animation="move-from-bottom" data-delay="item-2" style="min-height: 570px;">
                            <div class="pricing-deco" style="background: linear-gradient(135deg, #ff6666, #ff3333);">
                                <svg class="pricing-deco-img" enable-background="new 0 0 300 100" height="100px" id="Layer_1" preserveAspectRatio="none" version="1.1" viewBox="0 0 300 100" width="300px" x="0px" xml:space="preserve" y="0px">
                                    <path class="deco-layer deco-layer--1" d="M30.913,43.944c0,0,42.911-34.464,87.51-14.191c77.31,35.14,113.304-1.952,146.638-4.729c48.654-4.056,69.94,16.218,69.94,16.218v54.396H30.913V43.944z" fill="#FFFFFF" opacity="0.6"></path>
                                    <path class="deco-layer deco-layer--2" d="M-35.667,44.628c0,0,42.91-34.463,87.51-14.191c77.31,35.141,113.304-1.952,146.639-4.729c48.653-4.055,69.939,16.218,69.939,16.218v54.396H-35.667V44.628z" fill="#FFFFFF" opacity="0.6"></path>
                                    <path class="deco-layer deco-layer--3" d="M43.415,98.342c0,0,48.283-68.927,109.133-68.927c65.886,0,97.983,67.914,97.983,67.914v3.716H42.401L43.415,98.342z" fill="#FFFFFF" opacity="0.7"></path>
                                    <path class="deco-layer deco-layer--4" d="M-34.667,62.998c0,0,56-45.667,120.316-27.839C167.484,57.842,197,41.332,232.286,30.428c53.07-16.399,104.047,36.903,104.047,36.903l1.333,36.667l-372-2.954L-34.667,62.998z" fill="#FFFFFF"></path>
                                </svg>
                                        <div class="pricing-price" style="direction: ltr;"><span class="pricing-currency">{{ translate('Dinar')}}</span>3300
                                            <span class="pricing-period">{{ translate('/_month')}}</span>
                                        </div>
                                        <h3 class="pricing-title">Pro</h3>
                            </div>
                                    <ul class="pricing-feature-list">
                                        @if(Session::get('direction') === "ltr" && app()->getLocale() === "en")
                                            <li class="pricing-feature">✅&nbsp;Store + Seller Panel</li>
                                            <li class="pricing-feature">✅&nbsp;Product listing limit: 300</li>
                                            <li class="pricing-feature">✅&nbsp;Add your logo and banner</li>
                                            <li class="pricing-feature">✅&nbsp;Products boost</li>
                                            <li class="pricing-feature">✅&nbsp;Delivery with special prices</li>
                                            <li class="pricing-feature">✅&nbsp;Featured on Homepage</li>
                                            <li class="pricing-feature">✅&nbsp;Recommended shop</li>
                                            <li class="pricing-feature">✅&nbsp;Order Confirmation Call</li>
                                            <li class="pricing-feature">✅&nbsp;Warehousing</li>
                                        @elseif(Session::get('direction') === "ltr" && app()->getLocale() === "fr")
                                            <li class="pricing-feature">✅&nbsp;Boutique + Panel Vendeur</li>
                                            <li class="pricing-feature">✅&nbsp;Limite de produits : 300</li>
                                            <li class="pricing-feature">✅&nbsp;Ajoutez votre logo et bannière</li>
                                            <li class="pricing-feature">✅&nbsp;Mise en avant des produits</li>
                                            <li class="pricing-feature">✅&nbsp;Livraison à prix spéciaux</li>
                                            <li class="pricing-feature">✅&nbsp;Mis en avant sur la page d'accueil</li>
                                            <li class="pricing-feature">✅&nbsp;Boutique recommandée</li>
                                            <li class="pricing-feature">✅&nbsp;Appel de confirmation de commande</li>
                                            <li class="pricing-feature">✅&nbsp;Entreposage</li>
                                        @elseif(Session::get('direction') === "rtl")
                                            <li class="pricing-feature" style="direction: rtl;">✅&nbsp;متجر + لوحة البائع</li>
                                            <li class="pricing-feature" style="direction: rtl;">✅&nbsp;حد القائمة المنتجات: 300</li>
                                            <li class="pricing-feature" style="direction: rtl;">✅&nbsp;إضافة شعارك وشارتك</li>
                                            <li class="pricing-feature" style="direction: rtl;">✅&nbsp;تعزيز المنتجات</li>
                                            <li class="pricing-feature" style="direction: rtl;">✅&nbsp;التوصيل بأسعار خاصة</li>
                                            <li class="pricing-feature" style="direction: rtl;">✅&nbsp;مميزة على الصفحة الرئيسية</li>
                                            <li class="pricing-feature" style="direction: rtl;">✅&nbsp;متجر مُوصى به</li>
                                            <li class="pricing-feature" style="direction: rtl;">✅&nbsp;مكالمة تأكيد الطلب</li>
                                            <li class="pricing-feature" style="direction: rtl;">✅&nbsp;التخزين</li>
                                        @endif
                                    </ul>

                          <form action="{{ route('shop.apply') }}" method="get" target="">
                            <button type="submit" class="button1">{{ translate('Select_Plan')}}</button>
                          </form>
                          
                        </div>
                    </div>
            </section>
        </div>
        <div>
        <svg class="waves" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
        viewBox="0 24 150 28" preserveAspectRatio="none" shape-rendering="auto">
        <defs>
        <path id="gentle-wave" d="M-160 44c30 0 58-18 88-18s 58 18 88 18 58-18 88-18 58 18 88 18 v44h-352z" />
        </defs>
        <g class="parallax">
        <use xlink:href="#gentle-wave" x="48" y="0" fill="rgba(255,255,255,0.7" />
        <use xlink:href="#gentle-wave" x="48" y="3" fill="rgba(255,255,255,0.5)" />
        <use xlink:href="#gentle-wave" x="48" y="5" fill="rgba(255,255,255,0.3)" />
        <use xlink:href="#gentle-wave" x="48" y="7" fill="#fff" />
        </g>
        </svg>
        </div>
        </div>
        </section>

            <section class="offer-descriptions">
                 @if(Session::get('direction') === "ltr" && app()->getLocale() === "en")
                  <div class="description-container silver">
                    <h2>{{ translate('Basic') }}</h2>
                    <p>Basic is an excellent choice for those starting their online store journey. With a monthly subscription of DA 1200, you get access to a Seller Panel and a Store. You can list up to 50 products and customize your storefront with your logo and banner. While product boosting and some additional features are not included.</p>
                  </div>
                
                  <div class="description-container supreme">
                    <h2>{{ translate('Prime') }}</h2>
                    <p>For a more advanced experience, Prime at DA 2200 per month offers an expanded set of features. Alongside the Store and Seller Panel, you can list up to 150 products and enhance your store with a logo and banner. Enjoy our delivery services and the privilege of being featured on the homepage. Order Confirmation Calls are included, but some features like product boosting and warehousing are not part of this package.</p>
                  </div>
                
                 <div class="description-container legendary">
                    <h2>{{ translate('Pro') }}</h2>
                    <p>The Pro package at DA 3300 per month is designed for those seeking a comprehensive and feature-rich plan. With Store and Seller Panel access, you can list up to 300 products, add your logo and banner, and enjoy our reliable delivery services with special prices. Your store will be featured on the homepage, and you\'ll receive the benefits of being a recommended shop. Order Confirmation Calls and warehousing are included, making it an all-inclusive package for your Pro venture.</p>
                 </div>
                @elseif(Session::get('direction') === "ltr" && app()->getLocale() === "fr")
                <div class="description-container silver">
                    <h2>{{ translate('Basic') }}</h2>
                    <p>L'offre Basic est un excellent choix pour ceux qui commencent leur parcours de boutique en ligne. Avec un abonnement mensuel de 1200 DA, vous avez accès à un panneau de vendeur et à une boutique. Vous pouvez répertorier jusqu'à 50 produits et personnaliser votre vitrine avec votre logo et votre bannière. Bien que la mise en avant des produits et certaines fonctionnalités supplémentaires ne soient pas incluses.</p>
                  </div>
                
                  <div class="description-container supreme">
                    <h2>{{ translate('Prime') }}</h2>
                    <p>Pour une expérience plus avancée, l'offre Prime à 2200 DA par mois offre un ensemble étendu de fonctionnalités. En plus de la boutique et du panneau de vendeur, vous pouvez répertorier jusqu'à 150 produits et améliorer votre boutique avec un logo et une bannière. Profitez de nos services de livraison et du privilège d'être présenté sur la page d'accueil. Les appels de confirmation de commande sont inclus, mais certaines fonctionnalités telles que la mise en avant des produits et l'entreposage ne font pas partie de cette offre.</p>
                  </div>
                
                 <div class="description-container legendary">
                    <h2>{{ translate('Pro') }}</h2>
                    <p>Le forfait Pro à 3300 DA par mois est conçu pour ceux qui recherchent un plan complet et riche en fonctionnalités. Avec un accès à la boutique et au panneau de vendeur, vous pouvez répertorier jusqu'à 300 produits, ajouter votre logo et votre bannière et profiter de nos services de livraison fiables à des prix spéciaux. Votre boutique sera mise en avant sur la page d'accueil, et vous bénéficierez des avantages d'être une boutique recommandée. Les appels de confirmation de commande et l'entreposage sont inclus, ce qui en fait un forfait tout compris pour votre aventure Pro.</p>
                 </div>
                @elseif(Session::get('direction') === "rtl" && app()->getLocale() === "dz")
                                       <div class="description-container silver">
                                    <h2>{{ translate('Basic') }}</h2>
                                    <p>العرض الأساسي هو خيار ممتازا لأولئك الذين يبدأون رحلة متجرهم عبر الإنترنت. مع اشتراك شهري قدره  1200  دينار  جزائري، يمكنك الوصول إلى لوحة البائع والمتجر. يمكنك إدراج ما يصل إلى 50 منتجًا وتخصيص واجهة متجرك بشعارك وشعارك. بينما لا يتم تضمين تعزيز المنتج وبعض الميزات الإضافية.</p>
                                </div>
                                
                                <div class="description-container supreme">
                                    <h2>{{ translate('Prime') }}</h2>
                                    <p>لتجربة متقدمة أكثر، يقدم العرض الرئيسي بقيمة 2200 دينار جزائري شهريًا مجموعة واسعة من الميزات. بالإضافة إلى المتجر ولوحة البائع، يمكنك إدراج ما يصل إلى 150 منتجًا وتحسين متجرك بشعار وشعار رئيسي. استفد من خدمات التوصيل وامتياز الظهور على الصفحة الرئيسية. تشمل المكالمات لتأكيد الطلبات، ولكن بعض الميزات مثل تسليط الضوء على المنتجات والتخزين لا تأتي ضمن هذا العرض.</p>
                                </div>
                                
                                <div class="description-container legendary">
                                    <h2>{{ translate('Pro') }}</h2>
                                     <p>تم تصميم باقة Pro بسعر 3300 دينار جزائري شهريًا لأولئك الذين يبحثون عن خطة شاملة وغنية بالميزات. من خلال الوصول إلى لوحة المتجر والبائع، يمكنك إدراج ما يصل إلى 300 منتج وإضافة شعارك وشعارك والاستمتاع بخدمات التوصيل الموثوقة بأسعار خاصة. سيتم عرض متجرك على الصفحة الرئيسية، وستستفيد من مزايا كونك متجرًا موصى به. يتم تضمين مكالمات تأكيد الطلب والتخزين، مما يجعل هذه الحزمة شاملة لمغامرة المحترفين الخاصة بك .</p>
                                </div>

                @endif
            
            
            </section>
            
       
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>   
   @if(Session::get('direction') === "ltr" && app()->getLocale() === "en")
  <section class="c-section">
 
    <h2 class="c-section__title" style="font-size: 48px" dir="ltr"><span>Why NicheN+ ?</span></h2>

    
 
    <ul class="c-services" dir="ltr">
        <li class="c-services__item">
        <h3>Store + Seller Panel</h3>
        <p>Manage your store effortlessly with our intuitive Seller Panel - a comprehensive platform designed for seamless navigation and efficient operations.</p>
        </li>
        <li class="c-services__item">
        <h3>Product listing</h3>
        <p>Showcase up to 300 products, expanding your reach and enabling a diverse display to captivate a broader audience.</p>
        </li>
        <li class="c-services__item">
        <h3>Add your logo and banner</h3>
        <p>Personalize your storefront with your logo and a captivating banner to create a distinctive and memorable brand presence.</p>
        </li>
        <li class="c-services__item">
        <h3>Warehousing Support</h3>
        <p>Streamline inventory management with our warehousing assistance, ensuring efficient order fulfillment and timely deliveries.</p>
        </li>
        <li class="c-services__item">
        <h3>Products boost</h3>
        <p>Amplify product visibility and attract more customers by strategically boosting your offerings for increased exposure.</p>
        </li>
        <li class="c-services__item">
        <h3>Order Confirmation Call</h3>
        <p>Provide personalized support with an Order Confirmation Call, ensuring order accuracy and customer satisfaction.</p>
        </li>
        
        <li class="c-services__item">
        <h3>Delivery</h3>
        <p>Gain prominence on our Homepage, enhancing visibility and increasing the chances of attracting potential buyers.
</p>
        </li>
        <li class="c-services__item">
        <h3>Recommended shop</h3>
        <p>Stand out as a trusted and recommended shop, building credibility and encouraging customer exploration. </p>
        </li>
    </ul>
    </section>


<div class="c-section new-section">
    <div class="c-section__content">
        <div class="c-section__image">
           <img id="bannerImage"
            src="{{ theme_asset('assets/img/iphones.png') }}" alt="" style="Max-inline-size: 380%;">

        </div>
        <div class="c-section__text">
            <h2 style="color:white; font-size:32px;">Set Up Your Seller Account Now</h2>
            <div class="white-container">
                <p class="text-content" style="font-size:18px;">In order to kickstart your journey with us, we require some essential information. Your name, phone number, store name, logo, and banner are all vital components to set up your presence. These pieces of information play a crucial role in establishing your identity on our platform. Once you've provided these details, you'll have laid the groundwork to initiate your experience and engage with our platform's offerings, showcasing your products and services to your audience.</p>
            </div>
            <a href="https://nichen.net/shop/apply" class="button" style="font-size:16px;">Become a Seller Now!</a>
        </div>
    </div>
</div>


@elseif(Session::get('direction') === "ltr" && app()->getLocale() === "fr")
<section class="c-section">
    <h2 class="c-section__title" style="font-size: 36px;"><span>Pourquoi NicheN+</span></h2>
 
    <ul class="c-services">
     <li class="c-services__item">
    <h3>Tableau de bord Magasin + Vendeur</h3>
    <p>Gérez votre magasin facilement avec notre Tableau de bord Vendeur intuitif - une plateforme complète conçue pour une navigation fluide et des opérations efficaces.</p>
</li>

<li class="c-services__item">
    <h3>Liste de produits</h3>
    <p>Présentez jusqu'à 300 produits, étendant votre portée et permettant une présentation diversifiée pour captiver un public plus large.</p>
</li>

<li class="c-services__item">
    <h3>Ajoutez votre logo et votre bannière</h3>
    <p>Personnalisez votre vitrine avec votre logo et une bannière captivante pour créer une présence de marque distinctive et mémorable.</p>
</li>

<li class="c-services__item">
    <h3>Support d'entreposage</h3>
    <p>Optimisez la gestion des stocks avec notre assistance en entreposage, garantissant un traitement efficace des commandes et des livraisons rapides.</p>
</li>

<li class="c-services__item">
    <h3>Mise en avant de produits</h3>
    <p>Amplifiez la visibilité des produits et attirez plus de clients en mettant stratégiquement en avant vos offres pour une exposition accrue.</p>
</li>

<li class="c-services__item">
    <h3>Appel de confirmation de commande</h3>
    <p>Fournissez un support personnalisé avec un appel de confirmation de commande, garantissant l'exactitude de la commande et la satisfaction du client.</p>
</li>

<li class="c-services__item">
    <h3>Livraison</h3>
    <p>Prenez de l'importance sur notre page d'accueil, améliorant la visibilité et augmentant les chances d'attirer des acheteurs potentiels.</p>
</li>

<li class="c-services__item">
    <h3>Boutique recommandée</h3>
    <p>Se démarquez en tant que boutique recommandée et digne de confiance, renforçant la crédibilité et encourageant l'exploration par les clients.</p>
</li>

    </ul>
</section>

<div class="c-section new-section">
    <div class="c-section__content">
        <div class="c-section__image">
            <img src="https://i.ibb.co/9pP4mK4/i-Phone-15-Pro.png" style="Max-inline-size: 380%;">
        </div>
        <div class="c-section__text">
            <h2 style="color:white;">Configurez Votre Compte Vendeur Maintenant</h2>
            <div class="white-container">
                <p class="text-content">Pour démarrer votre parcours avec nous, nous avons besoin de quelques informations essentielles. Votre nom, numéro de téléphone, nom de boutique, logo et bannière sont tous des composants vitaux pour établir votre présence. Ces informations jouent un rôle crucial dans l'établissement de votre identité sur notre plateforme. Une fois que vous aurez fourni ces détails, vous aurez jeté les bases pour initier votre expérience et interagir avec les offres de notre plateforme, présentant ainsi vos produits et services à votre audience.</p>
            </div>
            <a href="https://nichen.net/shop/apply" class="button" style="font-size:16px;">Devenir Vendeur Maintenant !</a>
        </div>
    </div>
</div>


@elseif(Session::get('direction') === "rtl")

<section class="c-section" style="direction: ltr;">
    <h2 class="c-section__title" style="font-size: 36px;" dir="ltr"><span>لماذا نيشان بلس؟</span></h2>
 
    <ul class="c-services">
       <li class="c-services__item">
    <h3 dir="rtl">لوحة تحكم المتجر + البائع</h3>
    <p dir="rtl">إدارة متجرك بسهولة مع لوحة تحكم البائع البديهية لدينا - منصة شاملة مصممة لتسهيل التنقل والعمليات الفعّالة.</p>
</li>

<li class="c-services__item">
    <h3 dir="rtl">قائمة المنتجات</h3>
    <p dir="rtl">عرض ما يصل إلى 300 منتج، مما يوسع نطاقك ويمكّن عرضًا متنوعًا لجذب جمهور أوسع.</p>
</li>

<li class="c-services__item">
    <h3 dir="rtl">إضافة شعارك وشعارك الشخصي</h3>
    <p dir="rtl">تخصيص واجهة متجرك بشعارك وشعار جذاب لإنشاء وجود علامة تجارية مميزة وذات طابع يُذكر.</p>
</li>

<li class="c-services__item">
    <h3 dir="rtl">دعم التخزين</h3>
    <p dir="rtl">تيسير إدارة المخزون مع مساعدتنا في التخزين، مما يضمن تنفيذ الطلبات بفعالية وتسليمها في الوقت المناسب.</p>
</li>

<li class="c-services__item">
    <h3 dir="rtl">تعزيز المنتجات</h3>
    <p dir="rtl">زيادة رؤية المنتجات وجذب المزيد من العملاء من خلال تعزيز عروضك بشكل استراتيجي لزيادة التعرض.</p>
</li>

<li class="c-services__item">
    <h3 dir="rtl">مكالمة تأكيد الطلب</h3>
    <p dir="rtl">تقديم دعم شخصي مع مكالمة تأكيد الطلب، مما يضمن دقة الطلب ورضا العملاء.</p>
</li>

<li class="c-services__item">
    <h3 dir="rtl">خدمة التوصيل</h3>
    <p dir="rtl">التميز على صفحتنا الرئيسية، مما يعزز الرؤية ويزيد من فرص جذب المشترين المحتملين.</p>
</li>

<li class="c-services__item">
    <h3 dir="rtl">المتجر الموصى به</h3>
    <p dir="rtl">التميز كمتجر موصى به وموثوق به، مما يبني المصداقية ويشجع استكشاف العملاء.</p>
</li>

    </ul>
</section>

<div class="c-section new-section" style="direction: ltr;">
    <div class="c-section__content">
        <div class="c-section__image">
            <img src="https://i.ibb.co/9pP4mK4/i-Phone-15-Pro.png" alt="نص بديل لصورتك" style="Max-inline-size: 380%;">
        </div>
        <div class="c-section__text">
            <h2 style="color:white;"dir="rtl">قم بإعداد حسابك كبائع الآن</h2>
            <div class="white-container">
                <p class="text-content" dir="rtl">لبدء رحلتك معنا، نحتاج إلى بعض المعلومات الأساسية. اسمك، رقم هاتفك، اسم المتجر، الشعار، والبانر هي جميعها مكونات حيوية لإنشاء وجودك. تلعب هذه المعلومات دورًا حاسمًا في تأسيس هويتك على منصتنا. بمجرد توفيرك لهذه التفاصيل، ستكون قد وضعت الأساس لبدء تجربتك والتفاعل مع عروض منتجاتك وخدماتك لجمهورك.</p>
            </div>
            <a href="https://nichen.net/shop/apply" class="button" dir="rtl" style="font-size:16px;">كن بائعًا الآن!</a>
        </div>
    </div>
</div>
@endif



@endsection

@push('scripts')
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

@endpush