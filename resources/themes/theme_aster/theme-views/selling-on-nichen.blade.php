@extends('theme-views.layouts.app')

@section('title', $web_config['name']->value.' '.translate('Selling_on_nichen').' | '.$web_config['name']->value.' '.translate(' Ecommerce'))
@push('css_or_js')
    <meta property="og:image" content="{{asset('storage/app/public/company')}}/{{$web_config['web_logo']->value}}"/>
    <meta property="og:title" content="Welcome To {{$web_config['name']->value}} Home"/>
    <meta property="og:url" content="{{env('APP_URL')}}">
    <meta property="og:description" content="{!! substr($web_config['about']->value,0,100) !!}">

    <meta property="twitter:card" content="{{asset('storage/app/public/company')}}/{{$web_config['web_logo']->value}}"/>
    <meta property="twitter:title" content="Welcome To {{$web_config['name']->value}} Home"/>
    <meta property="twitter:url" content="{{env('APP_URL')}}">
    <meta property="twitter:description" content="{!! substr($web_config['about']->value,0,100) !!}">
   
    <style>
        .full-width {
            width: 100%;
            height: 500px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            -webkit-background-size: cover;
            -moz-background-size: cover;
            -o-background-size: cover;
            background-size: cover;
            align-items: flex-start; /* align items to the left */
            padding: 20px; /* add some padding */
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: flex-start; /* align items to the left */
            text-align: left; /* align text to the left */
            background: url('{{ theme_asset('assets/img/selling/selling-banner2.jpg') }}') no-repeat center center fixed;
        }

        .full-width h1 {
            margin-bottom: 10px; /* add some space below the title */
        }

        .full-width button {
            background-color: orange; /* make the button orange */
            border: none; /* remove the border */
            color: white; /* make the text white */
            padding: 10px 20px; /* add some padding */
            text-align: center; /* center the text */
            text-decoration: none; /* remove the underline */
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
        }
       .content-container {
            padding: 20px; /* add some padding */
            margin: 0 0 0 300px; /* top right bottom left */
            max-width: 500px; /* limit the width */
        }
        .content-container h1 {
            font-size: 40px; /* increase the font size */
            margin-bottom: 10px; /* add some space below the title */
            color : white;
        }
        
        .content-container p {
            font-size: 18px; 
            color : white;
        }

        .content-container a {
            background-color: #FF4D00; /* change the button color */
            border: none; /* remove the border */
            border-radius: 12px; /* make the borders curvy */
            color: white; /* make the text white */
            padding: 10px 20px; /* add some padding */
            text-align: center; /* center the text */
            text-decoration: none; /* remove the underline */
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
        }
        .orange-section {
            background-color: #FF4D00;
            padding: 20px;
            text-align: center;
        }
    
        .orange-section h2,
        .orange-section h3,
        .orange-section p {
            color: white;
        }
    
        .orange-section img {
            width: 50px;
            height: 50px;
        }
    
        .orange-section .content {
            display: flex;
            justify-content: center;
            gap: 20px;
        }
        .orange-section h2 {
            font-size: 62px; /* make the title bigger */
            margin: 30px 0; /* add space above and below the title */
            }

       
        .info-div {
            margin: 0 200px; /* add space on the left and right of each div */
            }
            
        .info-div img {
            margin-bottom: 25px; /* add space below the image */
        }
        .info-div h3 {
            margin-bottom: 25px; /* add space below the title */
        }

        .info-div p {
            width: 300px; /* set the width of the paragraph */
            margin: 0 auto; /* center the paragraph */
        }
        
        .video-section {
            margin-top: 50px; /* add space above the video section */
        }
    
        .video-section h2 {
            font-size: 3em;
            margin-top: 70px;
        }
        .video-section p {
            font-size: 1.2em;
            margin-top: 50px;
        }
        
        .card {
            border: 1px solid #787878; /* Make borders more black */
        }
        
        .brake-space { 
            margin-bottom: 20px; 
            
        }
        .hover-bg-black:hover {
            background-color:#ffffff;
            color: black; /* Change the text color to white so it's visible on the black background */
        }

        @media (max-width: 576px) {
            /* Adjust the height for screens smaller than 576px (phones) */
            #bannerImage {
                height: 120px;
            }
        }

         @media (max-width: 768px) {
        .full-width {
            height: auto;
            padding: 20px;
        }

        .content-container {
            margin: 0;
            max-width: 100%;
        }

        .orange-section .content {
            flex-direction: column;
        }

        .info-div {
            margin: 20px 0;
        }

        .info-div p {
            width: 100%;
        }
    }
    
        
    </style>
@endpush

@section('content')
    <main dir="LTR">
        <div class="full-width">
            <div class="content-container">
                @if(Session::get('direction') === "ltr" && app()->getLocale() === "en")
                <h1>Start selling <br>on Nichen</h1>
                <p>Niche was created to help businesses, no matter their size - grow. Being from the region, noon is especially passionate about helping local businesses thrive, we look forward to helping you take your venture to the next level.</p>
                @elseif(Session::get('direction') === "ltr" && app()->getLocale() === "fr")
                <h1>Commencez à vendre <br>sur Niche</h1>
                <p>Niche a été créée pour aider les entreprises, quelle que soit leur taille, à se développer. Étant de la région, noon est particulièrement passionné par l'aide aux entreprises locales à prospérer. Nous sommes impatients de vous aider à faire évoluer votre projet vers le niveau suivant.</p>

                @elseif(Session::get('direction') === "rtl")
                <h1>ابدا البيع على موقع </br>  نيشان</h1>
                <p>تم إنشاء نيش لمساعدة الشركات، بغض النظر عن حجمها، على النمو. كونها من المنطقة، نون ملتزمة بشكل خاص بمساعدة الشركات المحلية على الازدهار، نتطلع إلى مساعدتك في رفع مشروعك إلى المستوى التالي.</p>
                @endif
                <a href="{{route('shop.apply')}}" class="hover-bg-black">{{ translate('Sign_up_now')}}</a>
                
            </div>
        </div>
        
    <div class="orange-section">
        <h2>{{ translate('Why_nichen')}}</h2>

        <div class="content">
           <div class="info-div">
                <img src="{{ theme_asset('assets/img/icons/selling-icone.png') }}" alt="Icon 1">
                <h3>grow your business</h3>
                <p>Use nichens's custom-built tools and seller support team to expand your business online, safeguarding for future success.</p>
            </div>
            
            <div class="info-div">
                <img src="{{ theme_asset('assets/img/icons/selling-icone.png') }}" alt="Icon 2">
                <h3>reach millions of customers</h3>
                <p>Access nichens's hyper-engaged database of millions of customers.</p>
            </div>
        </div>
    </div>
    
       <div>
            <div class="rounded position-relative">
                @if(Session::get('direction') === "rtl")
                    <a href="#">
                        <img id="bannerImage"
                             src="{{ theme_asset('assets/img/second-banner-AR.png')}}"
                             onerror="this.src='{{ theme_asset('assets/img/banner-placeholder.png')}}'"
                             alt="Arabic Banner" class="img-fluid w-100"
                             style="float: right;" loading="lazy">
                    </a>
                @else
                    <a href="#">
                        <img id="bannerImage"
                             src="{{ theme_asset('assets/img/second-banner-EN.png') }}"
                             onerror="this.src='{{ theme_asset('assets/img/banner-placeholder.png') }}'"
                             alt="English Banner" class="img-fluid w-100"
                             style="float: left;" loading="lazy">
                    </a>
                @endif
            </div>
        </div>
    
    
  <div class="bg-white text-center py-5 video-section" >
    <div class="container">
        <div class="row justify-content-center" style="padding-top: 100px;">
            <div class="col-lg-8"> <!-- Increase column size -->
                 <div style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden;">
                    <iframe style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;" src="https://www.youtube.com/embed/TBhlufCJyf8?si=zuvJXxHpUIoU1HDv" allowfullscreen></iframe>
                </div>
                <h2 class="display-4 mb-3">The Title Here</h2> <!-- Increase title size -->
               @if(Session::get('direction') === "ltr" && app()->getLocale() === "en")
                <p>Niche was created to help businesses, no matter their size - grow. Being from the region, noon is especially passionate about helping local businesses thrive, we look forward to helping you take your venture to the next level.</p>
                @elseif(Session::get('direction') === "ltr" && app()->getLocale() === "fr")
                <p>Niche a été créée pour aider les entreprises, quelle que soit leur taille, à se développer. Étant de la région, noon est particulièrement passionné par l'aide aux entreprises locales à prospérer. Nous sommes impatients de vous aider à faire évoluer votre projet vers le niveau suivant.</p>
                @elseif(Session::get('direction') === "rtl")
                <p>تم إنشاء نيش لمساعدة الشركات، بغض النظر عن حجمها، على النمو. كونها من المنطقة، نون ملتزمة بشكل خاص بمساعدة الشركات المحلية على الازدهار، نتطلع إلى مساعدتك في رفع مشروعك إلى المستوى التالي.</p>
                @endif
            </div>
        </div>
        <div class="row" style="padding-top: 50px;">
                <!-- Rectangle 1 -->
                <div class="col-lg-4 mb-4">
                    <div class="card  shadow-sm rounded">
                       <div class="d-flex justify-content-center">
                            <img src="{{ theme_asset('assets/img/icons/selling-icone.png') }}" class="mt-3" style="width: 24px; height: 24px;" alt="Icon 1">
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Title 1</h5>
                            <p class="card-text mt-2">Some quick example text </p>
                           <a href="#" class="btn btn-primary">Learn more</a>
                        </div>
                    </div>
                </div>
                
                <!-- Rectangle 2 -->
                <div class="col-lg-4 mb-4">
                    <div class="card  shadow-sm rounded">
                        <div class="d-flex justify-content-center">
                            <img src="{{ theme_asset('assets/img/icons/selling-icone.png') }}" class="mt-3" style="width: 24px; height: 24px;" alt="Icon 2">
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Title 2</h5>
                            <p class="card-text mt-2">Some quick example text.</p>
                           <a href="#" class="btn btn-primary">Learn more</a>
                        </div>
                    </div>
                </div>
               
                <!-- Rectangle 3 -->
                <div class="col-lg-4 mb-4">
                    <div class="card  shadow-sm rounded">
                        <div class="d-flex justify-content-center">
                            <img src="{{ theme_asset('assets/img/icons/selling-icone.png') }}" class="mt-3" style="width: 24px; height: 24px;" alt="Icon 3">
                        </div>  
                        <div class="card-body">
                            <h5 class="card-title ">Title 3</h5>
                            <p class="card-text mt-2">Some quick example text</p>
                            <a href="#" class="btn btn-primary">Learn more</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div style=" background-image: url('{{ theme_asset('assets/img/selling/selling-img-2.jpg') }}'); background-size: cover; padding: 50px 0;">
        <div class="container">
            <div class="row justify-content-center text-center">
                <div class="col-lg-8">
                    <h2 class="mb-4" style="color: black;">The Title Here</h2>
                    <p class="mb-4" style="color: black;">The text here</p>
                         <div style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden;">
                            <iframe style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;" src="https://www.youtube.com/embed/TBhlufCJyf8?si=zuvJXxHpUIoU1HDv" allowfullscreen></iframe>
                        </div>
                </div>
            </div>
        </div>
    </div>
    
    <section style="background-color: #E8E8E8; padding: 50px 0;">
        <div class="container">
            <div class="row justify-content-center text-center">
                <div class="col-lg-8">
                    <h2 class="mb-4" style="color: black;">The Title Here</h2>
                    <p class="mb-4" style="color: black;">The text here</p>
                </div>
            </div>
            <div class="row">
                <!-- Card 1 -->
                <div class="col-lg-4 mb-4">
                    <div class="card" style="background-color: white;">
                        <img src="{{ theme_asset('assets/img/selling/logo_foreground.png') }}" class="card-img-top" alt="Image 1">
                        <div class="card-body">
                            <h5 class="card-title font-weight-bold" style="color: black;">Card Title 1</h5>
                            <p class="card-text" style="color: black;">Some quick example text</p>
                        </div>
                    </div>
                </div>
                <!-- Card 2 -->
                <div class="col-lg-4 mb-4">
                    <div class="card" style="background-color: white;">
                        <img src="{{ theme_asset('assets/img/selling/logo_foreground.png') }}" class="card-img-top" alt="Image 2">
                        <div class="card-body">
                            <h5 class="card-title font-weight-bold" style="color: black;">Card Title 2</h5>
                            <p class="card-text" style="color: black;">Some quick example text</p>
                        </div>
                    </div>
                </div>
                <!-- Card 3 -->
                <div class="col-lg-4 mb-4">
                    <div class="card" style="background-color: white;">
                        <img src="{{ theme_asset('assets/img/selling/logo_foreground.png') }}" class="card-img-top" alt="Image 3">
                        <div class="card-body">
                            <h5 class="card-title font-weight-bold" style="color: black;">Card Title 3</h5>
                            <p class="card-text" style="color: black;">Some quick example text</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
<div class="container-fluid" style="padding: 50px 0;">
    <div class="row justify-content-center">
        <!-- Image -->
        <div class="col-sm-12 col-md-6">
            <img id="bannerImage" src="{{ theme_asset('assets/img/iphones.png') }}" alt="Image" class="img-fluid d-block mx-auto" style="max-width: 100%;">
        </div>
        <!-- Text and button -->
       <div class="col-sm-12 col-md-6 text-center text-md-start">
           
            @if(Session::get('direction') === "ltr" && app()->getLocale() === "en")
            <h2 class="text-md-left" style="font-size: 32px; margin-bottom: 20px;">Set Up Your Seller <br>Account Now</h2> 
            @elseif(Session::get('direction') === "ltr" && app()->getLocale() === "fr")
            <h2 class="text-md-left" style="font-size: 32px; margin-bottom: 20px;">Créez votre compte vendeur <br>dès maintenant</h2> 
            @elseif(Session::get('direction') === "rtl")
            <h2 class="text-md-left" style="font-size: 32px; margin-bottom: 20px;">أنشئ حساب البائع الخاص بك <br>الآن</h2> 
            @endif
            <div>
               @if(Session::get('direction') === "ltr" && app()->getLocale() === "en")
                    <p style="font-size: 18px;">In order to kickstart your journey with us, we require some essential information:</p>
                    <p style="font-size: 18px;">-Your name.</p>
                    <p style="font-size: 18px;">-Phone number.</p>
                    <p style="font-size: 18px;">-Store name.</p>
                    <p style="font-size: 18px;">-Logo and banner.</p>
                    <p style="font-size: 18px;">These pieces of information play a crucial role in establishing your identity on our platform.</p>
                    <p style="font-size: 18px;">Once you've provided these details, you'll have laid the groundwork to initiate your experience and engage with our platform's offerings, showcasing your products and services to your audience.</p>
                @elseif(Session::get('direction') === "ltr" && app()->getLocale() === "fr")
                    <p style="font-size: 18px;">Afin de démarrer votre parcours avec nous, nous avons besoin de quelques informations essentielles :</p>
                    <p style="font-size: 18px;">-Votre nom.</p>
                    <p style="font-size: 18px;">-Numéro de téléphone.</p>
                    <p style="font-size: 18px;">-Nom de la boutique.</p>
                    <p style="font-size: 18px;">-Logo et bannière.</p>
                    <p style="font-size: 18px;">Ces informations jouent un rôle crucial dans l'établissement de votre identité sur notre plateforme.</p>
                    <p style="font-size: 18px;">Une fois que vous aurez fourni ces détails, vous aurez posé les bases pour initier votre expérience et vous engager avec les offres de notre plateforme, présentant vos produits et services à votre public.</p>
                @elseif(Session::get('direction') === "rtl")
                    <p style="font-size: 18px;" >: من أجل إطلاق رحلتك معنا، نحتاج إلى بعض المعلومات الأساسية</p>
                    <p style="font-size: 18px;" dir="LTR">.اسمك-</p>
                    <p style="font-size: 18px;">.رقم الهاتف-</p>
                    <p style="font-size: 18px;">.اسم المتجر-</p>
                    <p style="font-size: 18px;">.الشعار والبانر-</p>
                    <p style="font-size: 18px;">.تلك القطع المعلوماتية تلعب دورًا حاسمًا في تأسيس هويتك على منصتنا</p>
                    
                    <p style="font-size: 18px;"> .بمجرد توفيرك لهذه التفاصيل، ستكون قد وضعت الأساس لبدء تجربتك والتفاعل مع عروض منصتنا، حيث ستقوم بعرض منتجاتك وخدماتك لجمهورك</p>
                @endif
                
                <div class="d-flex justify-content-center justify-content-md-start"> <!-- Changed here -->
                    <a href="{{route('shop.apply')}}" class="btn btn-primary" style="width: 200px;">{{ translate('Become a Seller Now!')}}</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="bg-white text-center py-5 video-section" >
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8"> <!-- Increase column size -->
                 <div style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden;">
                    <iframe style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;" src="https://www.youtube.com/embed/TBhlufCJyf8?si=zuvJXxHpUIoU1HDv" allowfullscreen></iframe>
                </div>
                <h2 class="display-4 mb-3">{{ translate('See what our cutomers say')}}</h2> <!-- Increase title size -->
            </div>
        </div>
       <div class="row justify-content-center" style="padding-top: 50px;">
                <!-- Rectangle 1 -->
                <div class="col-lg-4 mb-4">
                    <div class="card  shadow-sm rounded">
                       
                        <div class="card-body">
                            
                            <p class="card-text mt-2">Niche was created to help businesses, no matter their size - grow. Being from the region, noon is especially passionate about helping local businesses thrive, we look forward to helping you take your venture to the next level. </p>
                            <h5 class="card-title">Customer 1</h5>
                        </div>
                    </div>
                </div>
                
                <!-- Rectangle 2 -->
                <div class="col-lg-4 mb-4">
                    <div class="card  shadow-sm rounded">
                        
                        <div class="card-body">
                            
                            <p class="card-text mt-2">Niche was created to help businesses, no matter their size - grow. Being from the region, noon is especially passionate about helping local businesses thrive, we look forward to helping you take your venture to the next level.</p>
                           <h5 class="card-title">Customer 1</h5>
                        </div>
                    </div>
                </div>
               
               
            </div>
        </div>
    </div>
   
        
</main>
@endsection
    
