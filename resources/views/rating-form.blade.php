<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/utilisateur.css') }}">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Noter chauffeur</title>

    <style>
        body {
            background-image: url('images/mercedes.jpeg');
            background-size: cover;
            background-position: center;
            height: 100vh; /* Hauteur de l'écran */
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .login_form {
            background-color: rgba(255, 255, 255, 0.8); /* Ajoute un fond blanc transparent */
            padding: 20px;
            border-radius: 10px;
        }
    </style>
    
</head>

<body style="background-image: url('images/mercedes.jpeg');">
    <section class="home">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Noter chauffeur</h5>
                            <form method="POST" action="{{ route('chauffeur.rate', $chauffeur->id) }}">
                                @csrf
                                <div class="form-group">
                                    <label for="note">Note:</label>
                                    <input type="number" class="form-control" name="rating" id="rating" min="1" max="5" placeholder="Note" required>
                                </div>
                                <div class="form-group">
                                    <label for="comment">Commentaire:</label>
                                    <textarea class="form-control" name="comment" id="comment" rows="4"></textarea>
                                </div>
                                <div class="form-group">
                                    <a href="{{route('user-accueil')}}" class="btn btn-secondary">Accueil</a>
                                    <button type="submit" class="btn btn-primary">Soumettre</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


<!-- <style>
    .home{
        background-image: url('images/mercedes.jpeg');
        background-size: cover;
        background-position: center;
    }
</style> -->

<!-- <body style="background-image: url('images/mercedes.jpeg');">
    <section class="home">
        <div class="form_container ">
            <div class="form login_form">

                <form method="POST" action="{{ route('chauffeur.rate', $chauffeur->id) }}">
                    @csrf

                    <div class="erreur">{{ session('error') }}</div>
                    <div class="erreur"><p style="color: green;">{{ session('success') }}</p></div>
                    <div class="input_box">
                        <label for="note">Note:</label> -->
                        <!-- <input type="number" name="note" id="note" min="1" max="5" placeholder="Note" required>
                    </div>

                    <div class="input_box"> -->
                        <!-- <label for="comment">Commentaire:</label> -->
                        <!-- <textarea name="comment" id="comment" rows="4"></textarea>
                    </div>
                    <br>
                    <br>
                    <br>
                    <br>
                    <a href="{{route('user-accueil')}}">Accueil</a>
                    <button type="submit" >Soumettre</button>
                </form>
            </div>
        </div>
    </section> -->
 

</body>

</html>