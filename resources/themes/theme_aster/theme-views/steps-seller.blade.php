@extends('theme-views.layouts.app')

@section('title',\App\CPU\translate('How to become a seller'))

@push('css_or_js')



    <meta property="og:image" content="{{asset('storage/app/public/company')}}/{{$web_config['web_logo']->value}}"/>
    <meta property="og:title" content="About {{$web_config['name']->value}} "/>
    <meta property="og:url" content="{{env('APP_URL')}}">
    <meta property="og:description" content="{!! substr($web_config['about']->value,0,100) !!}">

    <meta property="twitter:card" content="{{asset('storage/app/public/company')}}/{{$web_config['web_logo']->value}}"/>
    <meta property="twitter:title" content="about {{$web_config['name']->value}}"/>
    <meta property="twitter:url" content="{{env('APP_URL')}}">
    <meta property="twitter:description" content="{!! substr($web_config['about']->value,0,100) !!}">
    
    <style>
        
      
.main-timeline-2 {
  position: relative;
}


.main-timeline-2::after {
  content: "";
  position: absolute;
  width: 3px;
  background-color: #FF4D00;
  top: 0;
  bottom: 0;
  left: 50%;
  margin-left: -3px;
}

/* Container around content */
.timeline-2 {
  position: relative;
  background-color: inherit;
  width: 50%;
}

/* The circles on the timeline */
.timeline-2::after {
  content: "";
  position: absolute;
  width: 25px;
  height: 25px;
  right: -11px;
  background-color: #FF4D00;
  top: 15px;
  border-radius: 50%;
  z-index: 1;
}

/* Place the container to the left */
.left-2 {
  padding: 0px 40px 20px 0px;
  left: 0;
}

/* Place the container to the right */
.right-2 {
  padding: 0px 0px 20px 40px;
  left: 50%;
}

/* Add arrows to the left container (pointing right) */
.left-2::before {
  content: " ";
  position: absolute;
  top: 18px;
  z-index: 1;
  right: 30px;
  border: medium solid white;
  border-width: 10px 0 10px 10px;
  border-color: transparent transparent transparent grey;
}

/* Add arrows to the right container (pointing left) */
.right-2::before {
  content: " ";
  position: absolute;
  top: 18px;
  z-index: 1;
  left: 30px;
  border: medium solid white;
  border-width: 10px 10px 10px 0;
  border-color: transparent grey transparent transparent;
}

/* Fix the circle for containers on the right side */
.right-2::after {
  left: -14px;
}

/* Media queries - Responsive timeline on screens less than 600px wide */
@media screen and (max-width: 600px) {
  /* Place the timelime to the left */
  .main-timeline-2::after {
    left: 31px;
  }

  /* Full-width containers */
  .timeline-2 {
    width: 100%;
    padding-left: 70px;
    padding-right: 25px;
  }

  /* Make sure that all arrows are pointing leftwards */
  .timeline-2::before {
    left: 60px;
    border: medium solid white;
    border-width: 10px 10px 10px 0;
    border-color: transparent white transparent transparent;
  }

  /* Make sure all circles are at the same spot */
  .left-2::after,
  .right-2::after {
    left: 18px;
  }

  .left-2::before {
    right: auto;
    border-color: transparent transparent transparent #your_desired_color;
  }

  /* Make all right containers behave like the left ones */
  .right-2 {
    left: 0%;
  }
}
        
    </style>
    
    
    
    
@endpush

@section('content')
<section >
     @if(Session::get('direction') === "ltr" && app()->getLocale() === "fr")
  <div class="container py-5">
    <div class="main-timeline-2">
      <div class="timeline-2 left-2">
        <div class="card">
          <div class="card-body p-4">
              
            <h4 class="fw-bold mb-4"> Etape 1: Formulaire d'application </h4>
            <p class="text-muted mb-4"><i class="far fa-clock" aria-hidden="true"></i> </p>
       <div style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden;">
                    <iframe style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;" src="https://www.youtube.com/embed/TBhlufCJyf8?si=zuvJXxHpUIoU1HDv" allowfullscreen></iframe>
                </div>
                <div style="max-width: 100%; margin: 0; padding-top: 20px;">
                <p >1. Visitez notre site Web dans votre navigateur. </p>
                <p >2. Cliquez sur la barre supérieure et accédez à la "Seller Zone" (Zone vendeur) </p>
                <p >3. Une fois dans la Seller Zone, sélectionnez "Devenir vendeur" (Become a Seller) </p>
                <p >4. Remplissez le formulaire de demande de boutique en fournissant vos informations personnelles </p>
                <p >5. Explorez et choisissez parmi les trois offres d'adhésion disponibles. </p>
                <p >6. Remplissez les détails de votre boutique</p>
                <p >7. Sélectionnez le plan d'adhésion qui vous convient.</p>
                <p >8. Soumettez votre demande. </p>
                </div>
            </div>
        </div>
      </div>
      <div class="timeline-2 right-2">
        <div class="card">
          <div class="card-body p-4">
            <h4 class="fw-bold mb-4"> Etape 2: Confirmation et  Paiement</h4>
            <p class="text-muted mb-4"><i class="far fa-clock" aria-hidden="true"></i> </p>
                <div class="embed-responsive embed-responsive-16by9" style="max-width: 100%; margin: 0 auto;">
                    <video class="embed-responsive-item d-block" controls style="max-width: 100%;">
                        <source src="{{ asset("public/assets/front-end/video/Step2.mp4") }}" type="video/mp4">
                    </video>
                </div>

                    <div style="max-width: 100%; margin: 0; padding-top: 20px;">
                    <p  >Soumettez votre demande pour ouvrir votre boutique. Nous approuvons manuellement chaque demande, mais pour accélérer, <a href="https://docs.google.com/forms/d/1VXc7nXW2v2knXBvmW5jvExJy8dlrAUtq05qiMgrDpks" target="_blank" style="color :green;">remplissez le formulaire de la deuxième étape</a> avec les infos et la preuve de paiement. Contactez le support si besoin. Dès réception et vérification du paiement, nous approuverons rapidement votre boutique pour que vous puissiez débuter votre aventure commerciale en ligne.</p>
                    </div>
            </div>
        </div>
      </div>
      <div class="timeline-2 left-2">
        <div class="card">
          <div class="card-body p-4">
            <h4 class="fw-bold mb-4">Etape 3 : Comment utiliser votre boutique </h4>
            <p class="text-muted mb-4"><i class="far fa-clock" aria-hidden="true"></i></p>
            <div style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden;">
                    <iframe style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;" src="https://www.youtube.com/embed/AzgICygRCuM?si=JQLfaHIrlJwY8Ls-" allowfullscreen></iframe>
                </div>
                <div style="max-width: 100%; margin: 0; padding-top: 20px; ">
        
                  <p>La vidéo démo vous guide pour maximiser votre expérience en tant que vendeur sur notre plateforme. Apprenez à gérer facilement vos produits, configurer les options de livraison et consulter les statistiques de ventes. Cette ressource complète vous aide à devenir un vendeur accompli, à mettre en avant votre entreprise et à développer votre présence en ligne.</p>
                </div>
            </div>

            </div>
      
      </div>
    </div>
  </div>
  @elseif(Session::get('direction') === "ltr" && app()->getLocale() === "en")
<div class="container py-5">
    <div class="main-timeline-2">
<div class="timeline-2 left-2">
    <div class="card">
        <div class="card-body p-4">
            <h4 class="fw-bold mb-4">Step 1: Application Form</h4>
            <p class="text-muted mb-4"><i class="far fa-clock" aria-hidden="true"></i></p>
            <div class="embed-responsive embed-responsive-16by9">
                <div style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden;">
                    <iframe style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;" src="https://www.youtube.com/embed/TBhlufCJyf8?si=zuvJXxHpUIoU1HDv" allowfullscreen></iframe>
                </div>
            </div>
            <div style="max-width: 100%; margin: 0; padding-top: 20px;">
                <p>1. Visit our website in your browser.</p>
                <p>2. Click on the top bar and access the "Seller Zone."</p>
                <p>3. Once in the Seller Zone, select "Become a Seller."</p>
                <p>4. Fill out the shop application form by providing your personal information.</p>
                <p>5. Explore and choose from the three available membership offers.</p>
                <p>6. Fill in your shop details.</p>
                <p>7. Select the membership plan that suits you.</p>
                <p>8. Submit your application.</p>
            </div>
        </div>
    </div>
</div>

        <div class="timeline-2 right-2">
            <div class="card">
                <div class="card-body p-4">
                    <h4 class="fw-bold mb-4">Step 2: Confirmation and Payment</h4>
                    <p class="text-muted mb-4"><i class="far fa-clock" aria-hidden="true"></i> </p>
                    <div class="embed-responsive embed-responsive-16by9" style="max-width: 100%; margin: 0 auto;">
                        <video class="embed-responsive-item d-block" controls style="max-width: 100%;">
                            <source src="{{ asset("public/assets/front-end/video/Step2.mp4") }}" type="video/mp4">
                        </video>
                    </div>
                    <div style="max-width: 100%; margin: 0; padding-top: 20px;">
                        <p>Submit your request to open your shop. We manually approve each request, but to expedite, <a href="https://docs.google.com/forms/d/1VXc7nXW2v2knXBvmW5jvExJy8dlrAUtq05qiMgrDpks" target="_blank" style="color: green;">fill out the second step form</a> with the information and payment proof. Contact support if needed. Upon receiving and verifying the payment, we will quickly approve your shop so you can start your online business venture.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="timeline-2 left-2">
    <div class="card">
        <div class="card-body p-4">
            <h4 class="fw-bold mb-4">Step 3: How to Use Your Shop</h4>
            <p class="text-muted mb-4"><i class="far fa-clock" aria-hidden="true"></i></p>
            <div class="embed-responsive embed-responsive-16by9">
                <div style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden;">
                    <iframe style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;" src="https://www.youtube.com/embed/AzgICygRCuM?si=JQLfaHIrlJwY8Ls-" allowfullscreen></iframe>
                </div>
            </div>
            <div style="max-width: 100%; margin: 0; padding-top: 20px;">
                <p>The demo video guides you to maximize your experience as a seller on our platform. Learn to easily manage your products, set up delivery options, and check sales statistics. This comprehensive resource helps you become a successful seller, showcase your business, and grow your online presence.</p>
            </div>
        </div>
    </div>
</div>

    </div>
</div>

@elseif(Session::get('direction') === "rtl" && app()->getLocale() === "dz")

<div class="container py-5" dir="ltr">
    <div class="main-timeline-2">
        <div class="timeline-2 left-2">
            <div class="card">
                <div class="card-body p-4" dir="rtl">
                    <h4 class="fw-bold mb-4">الخطوة 1: نموذج الطلب</h4>
                    <p class="text-muted mb-4"><i class="far fa-clock" aria-hidden="true"></i> </p>
                     <div style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden;">
                    <iframe style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;" src="https://www.youtube.com/embed/TBhlufCJyf8?si=zuvJXxHpUIoU1HDv" allowfullscreen></iframe>
                </div>
                    <div style="max-width: 100%; margin: 0; padding-top: 20px;">
                        <p>1. قم بزيارة موقعنا على الويب من متصفحك.</p>
                        <p>2. انقر فوق الشريط العلوي واستخدم "منطقة البائع".</p>
                        <p>3. بمجرد دخولك إلى منطقة البائع، حدد "كن بائعًا".</p>
                        <p>4. قم بملء نموذج طلب المتجر عن طريق تقديم معلوماتك الشخصية.</p>
                        <p>5. استكشف واختر من بين العروض الثلاثة المتاحة للعضوية.</p>
                        <p>6. قم بملء تفاصيل متجرك.</p>
                        <p>7. حدد خطة العضوية التي تناسبك.</p>
                        <p>8. قدم طلبك.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="timeline-2 right-2">
            <div class="card">
                <div class="card-body p-4" dir="rtl">
                    <h4 class="fw-bold mb-4">الخطوة 2: التأكيد والدفع</h4>
                    <p class="text-muted mb-4"><i class="far fa-clock" aria-hidden="true"></i> </p>
                    <div class="embed-responsive embed-responsive-16by9" style="max-width: 100%; margin: 0 auto;">
                        <video class="embed-responsive-item d-block" controls style="max-width: 100%;">
                            <source src="{{ asset("public/assets/front-end/video/Step2.mp4") }}" type="video/mp4">
                        </video>
                    </div>
                    <div style="max-width: 100%; margin: 0; padding-top: 20px;">
                        <p>قدّم طلبك لفتح متجرك. نقوم بموافقة كل طلب يدويًا، ولكن لتسريع الأمور، <a href="https://docs.google.com/forms/d/1VXc7nXW2v2knXBvmW5jvExJy8dlrAUtq05qiMgrDpks" target="_blank" style="color: green;">املأ نموذج الخطوة الثانية</a> بالمعلومات وإثبات الدفع. تواصل مع الدعم إذا لزم الأمر. عند استلام وتحقق الدفع، سنوافق على متجرك بسرعة لتبدأ مغامرتك التجارية عبر الإنترنت.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="timeline-2 left-2">
            <div class="card">
                <div class="card-body p-4" dir="rtl">
                    <h4 class="fw-bold mb-4">الخطوة 3: كيفية استخدام متجرك</h4>
                    <p class="text-muted mb-4"><i class="far fa-clock" aria-hidden="true"></i></p>
                   <div style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden;">
                    <iframe style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;" src="https://www.youtube.com/embed/AzgICygRCuM?si=JQLfaHIrlJwY8Ls-" allowfullscreen></iframe>
                </div>
                    <div style="max-width: 100%; margin: 0; padding-top: 20px; ">
                        <p>يقوم الفيديو التوضيحي بتوجيهك لتعظيم تجربتك كبائع على منصتنا. تعلم كيفية إدارة منتجاتك بسهولة، وتكوين خيارات التوصيل، وفحص إحصاءات المبيعات. يساعدك هذا المورد الشامل على أن تصبح بائعًا ناجحًا، وعرض عملك التجاري، وزيادة وجودك عبر الإنترنت.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endif
  
  
</section>
@endsection
