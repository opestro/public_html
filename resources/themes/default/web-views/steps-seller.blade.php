@extends('layouts.front-end.app')

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
        
        /* The actual timeline (the vertical ruler) */
.main-timeline-2 {
  position: relative;
}

/* The actual timeline (the vertical ruler) */
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
  border-color: transparent transparent transparent white;
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
  border-color: transparent white transparent transparent;
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
  }

  /* Make all right containers behave like the left ones */
  .right-2 {
    left: 0%;
  }
}
        
    </style>
    
    
    
    
@endpush

@section('content')
    <section style="background-color: #F0F2F5;">
  <div class="container py-5">
    <div class="main-timeline-2">
      <div class="timeline-2 left-2">
        <div class="card">
  <div class="card-body p-4">
    <h4 class="fw-bold mb-4"> Etape 1: Formulaire d'application </h4>
    <p class="text-muted mb-4"><i class="far fa-clock" aria-hidden="true"></i> </p>
    <div class="embed-responsive embed-responsive-16by9">
      <!-- Replace 'your-hosted-video-url' with the URL of your hosted video -->
      <video class="embed-responsive-item" controls>
        <source src="{{asset("public/assets/front-end/video/Step1.mp4")}}" type="video/mp4">
        Your browser does not support the video tag.
      </video>
    </div>
    
    <div class="p-4">
    <p class="mb-0" >1. Visitez notre site Web dans votre navigateur. </p>
    <p class="mb-0">2. Cliquez sur la barre supérieure et accédez à la "Seller Zone" (Zone vendeur) </p>
    <p class="mb-0">3. Une fois dans la Seller Zone, sélectionnez "Devenir vendeur" (Become a Seller) </p>
    <p class="mb-0">4. Remplissez le formulaire de demande de boutique en fournissant vos informations personnelles </p>
    <p class="mb-0">5. Explorez et choisissez parmi les trois offres d'adhésion disponibles. </p>
    <p class="mb-0">6. Remplissez les détails de votre boutique</p>
    <p class="mb-0">7. Sélectionnez le plan d'adhésion qui vous convient.</p>
    <p class="mb-0">8. Soumettez votre demande. </p>
    </div>
    
  </div>
</div>


      </div>
      <div class="timeline-2 right-2">
        <div class="card">
  <div class="card-body p-4">
    <h4 class="fw-bold mb-4"> Etape 2: Confirmation et  Paiement</h4>
    <p class="text-muted mb-4"><i class="far fa-clock" aria-hidden="true"></i> </p>
    <div class="embed-responsive embed-responsive-16by9">
      <!-- Replace 'your-hosted-video-url' with the URL of your hosted video -->
      <video class="embed-responsive-item" controls>
        <source src="{{asset("public/assets/front-end/video/Step2.mp4")}}" type="video/mp4">
        Your browser does not support the video tag.
      </video>
    </div>
    <p class="mb-0 " style="padding: 20px;" >Soumettez votre demande pour ouvrir votre boutique. Nous approuvons manuellement chaque demande, mais pour accélérer, <a href="https://docs.google.com/forms/d/1VXc7nXW2v2knXBvmW5jvExJy8dlrAUtq05qiMgrDpks" target="_blank" style="color :green;">remplissez le formulaire de la deuxième étape</a> avec les infos et la preuve de paiement. Contactez le support si besoin. Dès réception et vérification du paiement, nous approuverons rapidement votre boutique pour que vous puissiez débuter votre aventure commerciale en ligne.</p>
  </div>
</div>
      </div>
      <div class="timeline-2 left-2">
        <div class="card">
  <div class="card-body p-4">
    <h4 class="fw-bold mb-4">Etape 3 : Comment utiliser votre boutique </h4>
    <p class="text-muted mb-4"><i class="far fa-clock" aria-hidden="true"></i></p>
    <div class="embed-responsive embed-responsive-16by9">
      <!-- Replace 'your-hosted-video-url' with the URL of your hosted video -->
      <video class="embed-responsive-item" controls>
        <source src="{{asset("public/assets/front-end/video/Step3.mp4")}}" type="video/mp4">
        Your browser does not support the video tag.
      </video>
    </div>
    <div class="p-4">
        
  <p>La vidéo démo vous guide pour maximiser votre expérience en tant que vendeur sur notre plateforme. Apprenez à gérer facilement vos produits, configurer les options de livraison et consulter les statistiques de ventes. Cette ressource complète vous aide à devenir un vendeur accompli, à mettre en avant votre entreprise et à développer votre présence en ligne.</p>


        
        </p>
  </div>
</div>
      </div>
      
      
    </div>
  </div>
</section>
@endsection
