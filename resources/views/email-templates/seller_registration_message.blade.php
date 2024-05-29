<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>{{\App\CPU\translate('Email Verification')}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style type="text/css">
              body {
            font-family: 'Source Sans Pro', sans-serif;
            background-color: #e9ecef;
            width: 100% !important;
            height: 100% !important;
            padding: 0 !important;
            margin: 0 !important;
        }

        .card {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .headerTitle {
            color: #1a82e2;
        }

        .container {
            margin-top: 60px;
            margin-bottom: 60px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        p {
            font-size: 16px;
            color: #333;
            text-align: justify;
            line-height: 1.6;
        }

        a {
            color: #1a82e2;
        }
        @media screen {
            @font-face {
                font-family: 'Source Sans Pro';
                font-style: normal;
                font-weight: 400;
                src: local('Source Sans Pro Regular'), local('SourceSansPro-Regular'), url(https://fonts.gstatic.com/s/sourcesanspro/v10/ODelI1aHBYDBqgeIAH2zlBM0YzuT7MdOe03otPbuUS0.woff) format('woff');
            }

            @font-face {
                font-family: 'Source Sans Pro';
                font-style: normal;
                font-weight: 700;
                src: local('Source Sans Pro Bold'), local('SourceSansPro-Bold'), url(https://fonts.gstatic.com/s/sourcesanspro/v10/toadOcfmlt9b38dHJxOBGFkQc6VGVFSmCnC_l7QZG60.woff) format('woff');
            }
        }

        /**
         * Avoid browser level font resizing.
         * 1. Windows Mobile
         * 2. iOS / OSX
         */
        body,
        table,
        td,
        a {
            -ms-text-size-adjust: 100%; /* 1 */
            -webkit-text-size-adjust: 100%; /* 2 */
        }

        /**
         * Remove extra space added to tables and cells in Outlook.
         */
        table,
        td {
            mso-table-rspace: 0pt;
            mso-table-lspace: 0pt;
        }

        /**
         * Better fluid images in Internet Explorer.
         */
        img {
            -ms-interpolation-mode: bicubic;
        }

        /**
         * Remove blue links for iOS devices.
         */
        a[x-apple-data-detectors] {
            font-family: inherit !important;
            font-size: inherit !important;
            font-weight: inherit !important;
            line-height: inherit !important;
            color: inherit !important;
            text-decoration: none !important;
        }

        /**
         * Fix centering issues in Android 4.4.
         */
        div[style*="margin: 16px 0;"] {
            margin: 0 !important;
        }

        body {
            width: 100% !important;
            height: 100% !important;
            padding: 0 !important;
            margin: 0 !important;
        }

        /**
         * Collapse table borders to avoid space between cells.
         */
        table {
            border-collapse: collapse !important;
        }

        a {
            color: #1a82e2;
        }

        img {
            height: auto;
            line-height: 100%;
            text-decoration: none;
            border: 0;
            outline: none;
        }
    </style>
</head>
<body style="background-color: #e9ecef;">

    <div class="card" style="max-width: 600px; margin: 20px auto; background-color: #ffffff; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
        <h2 class="text-center mt-3 headerTitle" style="background-color: #FF4D00; color: #ffffff; padding: 20px; border-top-left-radius: 8px; border-top-right-radius: 8px;">Félicitations!</h2>

        <div class="container" style="margin-top: 20px; margin-bottom: 20px; padding: 20px;">
            <p>
            
        Nous vous remercions d'avoir soumis votre demande pour la création d'un magasin sur notre plateforme. Nous vous informons que nous avons bien reçu votre demande et sommes ravis de vous accompagner dans cette démarche.
        
        Afin de procéder à l'approbation de votre magasin et de vous permettre de démarrer votre parcours avec nous, veuillez suivre ces étapes :
        <br>
        Remplissez <a href="https://docs.google.com/forms/d/1VXc7nXW2v2knXBvmW5jvExJy8dlrAUtq05qiMgrDpks" target="_blank" style="color :green;">FORMULAIRE DE LA 2EME ETAPE</a>  en saisissant les informations requises et effectuez le paiement de l'adhésion pour finaliser le
        processus. Enfin, soumettez la preuve de paiement.
        
        Le paiement total est {{ $totalPrice }} DA.
        
        En cas de problème ou de question, notre équipe de support est disponible sur WhatsApp au 0773738310 pour vous assister.
        
        Nous vous remercions de votre confiance en notre plateforme et avons hâte de vous accompagner dans la création et la gestion de votre magasin en ligne.
         <br><br>
        Cordialement,
       </p>
     </div>
    </div>

</body>
</html>

