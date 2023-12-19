<?php

use App\AppRepoManager;
use Core\Session\Session; ?>
<div class="admin-container">
    <h1 class="title">Mes Commandes</h1>
  
    <table class="table-user">
        <thead>
            <tr>
                <th class="footer-description">Pizza</th>
                <th class="footer-description">Ingrédients</th>
                <th class="footer-description">Prix</th>

            </tr>
        </thead>

        <tbody>
            <!-- on affiche les pizzas choisis par l'user -->
            
            <?php $user = AppRepoManager::getRm()->getUserRepository()->findUserById(Session::get(Session::USER)->id); ?>
            <?php if ($user) :  ?>
                <tr>
                    <td class="footer-description"><?= $user->lastname ?></td>
                    <td class="footer-description"><?= $user->firstname ?></td>
       
                    <td class="footer-description">
                        <a onclick="return confirm('Voulez-vous supprimer cette commande?')" class="button-delete" href="">
                            <i class="bi bi-trash"></i></a>
                    </td>
                </tr>
            <?php endif ?>
        </tbody>
    </table>
 
</div>

