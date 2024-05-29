<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouveau Message d'un Client</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 20px;
            padding: 20px;
            background-color: #f4f4f4;
        }

        h1 {
            color: #333;
        }

        p {
            color: #555;
            line-height: 1.6;
        }

        strong {
            color: #000;
        }
    </style>
</head>
<body>
    
    <?php 
    use App\Model\Chatting;
        $userID = $user->id;
        $sellerID = $seller->id;
    
        $lastMessage = \App\Model\Chatting::where(function ($query) use ($userID, $sellerID) {
            $query->where('user_id', $userID)
                  ->where('seller_id', $sellerID);
        })
        ->orWhere(function ($query) use ($userID, $sellerID) {
            $query->where('user_id', $sellerID)
                  ->where('seller_id', $userID);
        })
        ->latest('created_at')
        ->first();
    ?>
    
    <h1>Bonjour {{ $seller->f_name }} {{ $seller->l_name }},</h1>

    <p>Vous avez reçu un nouveau message de la part du client <strong>{{ $user->f_name }} {{ $user->l_name }}</strong> sur le site Nichen :</p>

    <p><strong>Message :</strong></p>
       <p>{{ $lastMessage->message }}</p>

    <p>N'oubliez pas de vérifier votre tableau de bord sur le site Nichen pour plus de détails.</p>

    <p>Merci !</p>
</body>
</html>
