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
                <th class="footer-description">Supprimer</th>
            </tr>
        </thead>

        <tbody>
            <!-- on affiche les informations de l'utilisateur en session -->
            <?php $user = AppRepoManager::getRm()->getUserRepository()->findUserById(Session::get(Session::USER)->id); ?>
            <?php if ($user ) :  ?>
                <tr>
                    <td class="footer-description"><?= $user->lastname ?></td>
                    <td class="footer-description"><?= $user->firstname ?></td>
                    <td class="footer-description"><?= $user->email ?></td>
                    <td class="footer-description"><?= $user->phone ?></td>
                    <td class="footer-description">
                        <a onclick="return confirm('Voulez-vous supprimer votre compte ?')" class="button-delete" href="#">
                            <i class="bi bi-trash"></i></a>
                    </td>
                </tr>
            <?php endif ?>
        </tbody>
    </table>
</div>