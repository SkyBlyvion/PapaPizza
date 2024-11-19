<?php

use App\AppRepoManager;
use Core\Session\Session; ?>
<div class="admin-container">
    <h1 class="title">Mon Compte</h1>
    <!-- bouton pour modifier le compte -->
    <div class="admin-box-add">
        <a class="call-action" href="/user/update/user/<?php echo Session::get(Session::USER)->id ?>" class="btn btn-primary">Modifier mon compte</a>
    </div>




    <table class="table-user">
        <thead>
            <tr>
                <th class="footer-description">Nom</th>
                <th class="footer-description">Prénom</th>
                <th class="footer-description">Email</th>
                <th class="footer-description">Téléphone</th>
                <th class="footer-description">Action</th>
            </tr>
        </thead>

        <tbody>
            <!-- on affiche les informations de l'utilisateur en session -->
            <?php $user = AppRepoManager::getRm()->getUserRepository()->findUserById(Session::get(Session::USER)->id); ?>
            <?php if ($user) :  ?>
                <tr>
                    <td class="footer-description"><?= $user->lastname ?></td>
                    <td class="footer-description"><?= $user->firstname ?></td>
                    <td class="footer-description"><?= $user->email ?></td>
                    <td class="footer-description"><?= $user->phone ?></td>
                    <td class="footer-description">
                        <a onclick="return confirm('Voulez-vous supprimer votre compte ?')" class="button-delete" href="/user/user/delete/<?= $user->id ?>">
                            <i class="bi bi-trash"></i></a>
                    </td>
                </tr>
            <?php endif ?>
        </tbody>
    </table>
    <h2 class="title">Vos Créations</h2>
    <div class="admin-box-add">
        <a class="call-action" href="/user/create-pizza/<?php echo Session::get(Session::USER)->id ?>" class="btn btn-primary">Créer votre pizza</a>
    </div>
    <div class="d-flex justify-content-center">
        <div class="d-flex flex-row flex-wrap my-3 justify-content-center col-lg-10">
            <?php $pizzas = AppRepoManager::getRm()->getPizzaRepository()->getAllPizzasById(); ?>
            <?php foreach ($pizzas as $pizza) : ?>
                <div class="card m-2" style="width: 18rem;">
                    <a href="/pizza/<?= $pizza->id ?>">
                        <img src="/assets/images/pizza/<?= $pizza->image_path ?>" alt="<?= $pizza->name ?>" class="card-img-top img-fluid img-pizza">
                    </a>
                    <div class="card-body">
                        <h3 class="card-title sub-title"><?= $pizza->name ?></h3>
                    </div>

                </div>

            <?php endforeach ?>
        </div>
    </div>
</div>

