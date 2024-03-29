<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

<body>
    <div class="sidebar">
        <div class="logo">
            <ul class="menu">
                <li class="active">
                    <a href="">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('dashUtilisateur') }}">
                        <i class="fas fa-users "></i>
                        <span>utilisateurs</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('dashboard') }}">
                        <i class="fas fa-car"></i>
                        <span>vehicules</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('dashChauffeur') }}">
                        <i class="fas fa-user-tie"></i>
                        <span>chauffeurs</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('dashCommande') }}">
                        <i class="fas fa-clipboard-list"></i>
                        <span>Commandes</span>
                    </a>
                </li>
                <li>
                    <a href="{{route('dashCommentaire')}}">
                        <i class="fas fa-star"></i>
                        <span>Testamonials</span>
                    </a>
                </li>
                <li>
                    <a href="">
                        <i class="fas fa-cog"></i>
                        <span>Settings</span>
                    </a>
                </li>
                <li class="logout">
                    <a href="">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <div class="main--content">
        <div class="header--wrapper">
            <div class="header--title">
                <span>Yobalema</span>
                <h2>Dashboard</h2>
            </div>
            <div class="user--info">
                <div class="search--box">
                    <i class="fa-solid fa-search"></i>
                    <input type="text" placeholder="search">
                </div>
                <img src="" alt="">
            </div>
        </div>

        <div class="card--container">
            <h3 class="main--title">Données en live</h3>
            <div class="card--wrapper">
                <div class="payment--card light-red">
                    <div class="card--header">
                        <div class="amount">
                            <span class="title">
                                Nombre total de véhicules
                            </span>
                            <span class="amount-value">
                                {{ $totalVehicules }}
                            </span>
                        </div>
                        <i class="fas fa-car icon"></i>
                    </div>
                    <span class="card--detail">
                        **** **** **** 3544
                    </span>
                </div>
                <div class="payment--card light-purple">
                    <div class="card--header">
                        <div class="amount">
                            <span class="title">
                                Nombre total de chauffeurs
                            </span>
                            <span class="amount-value">
                            {{ $totalchauffeurs }}
                            </span>
                        </div>
                        <i class="fas fa-user-tie icon dark-purple"></i>
                    </div>
                    <span class="card--detail">
                        **** **** **** 5542
                    </span>
                </div>
                <div class="payment--card light-green">
                    <div class="card--header">
                        <div class="amount">
                            <span class="title">
                                Nombre total d'utilisateurs
                            </span>
                            <span class="amount-value">
                            {{ $totalusers }}
                            </span>
                        </div>
                        <i class="fas fa-users icon dark-green"></i>
                    </div>
                    <span class="card--detail">
                        **** **** **** 7624
                    </span>
                </div>
                <div class="payment--card light-blue">
                    <div class="card--header">
                        <div class="amount">
                            <span class="title">
                                Nombre de commandes
                            </span>
                            <span class="amount-value">
                            {{ $totalreservations }}
                            </span>
                        </div>
                        <i class="fas fa-clipboard-list icon dark-blue"></i>
                    </div>
                    <span class="card--detail">
                        **** **** **** 7734
                    </span>
                </div>
            </div>
        </div>
        <div class="tabular--wrapper">
            <h3 class="main--title">
                Liste des commandes
            </h3>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>USER ID</th>
                            <th>VEHICULE ID</th>
                            <th>LIEU DEPART</th>
                            <th>LIEU ARRIVE</th>
                            <th>HEURE DEBUT</th>
                            <th>HEURE FIN</th>
                            <th>PRIX TOTAL</th>
                            <!-- <th>Action</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reservations as $reservation)
                        <tr>
                            <td>{{ $reservation->id}}</td>
                            <td>{{ $reservation->user_id }}</td>
                            <td>{{ $reservation->vehicule_id }}</td>
                            <td>{{ $reservation->lieu_depart }}</td>
                            <td>{{ $reservation->lieu_arrivee }}</td>
                            <td>{{ $reservation->heure_debut }}</td>
                            <td>{{ $reservation->heure_fin }}</td>
                            <td>{{ $reservation->prix }}</td>
                           
                            <!-- <td class="btn-boutton">
                                <a href="{{ route('vehicule.edit', $reservation->id) }}" class="btnEditer">
                                    <i class="fas fa-pencil-alt"></i> Editer</a>
                                <form action="{{ route('vehicule.destroy', $reservation->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btnSuprimer"><i class="fas fa-trash-alt"></i> Supprimer</button>
                                </form>
                            </td> -->
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>