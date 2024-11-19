<?php

use App\AppRepoManager;
use Core\Session\Session; ?>
<div class="admin-container">
    <h1 class="title">Les Pizzas</h1>
    <!-- bouton pour ajouter un nouveau membre -->
    <div class="admin-box-add">
        <a class="call-action" href="/admin/pizza/add" class="btn btn-primary">Ajouter une pizza</a>
    </div>
    <!-- on va afficher les erreurs s'il y en a -->
    <?php if ($form_result && $form_result->hasErrors()) : ?>
        <div class="alert alert-danger" role="alert">
            <?php echo $form_result->getErrors()[0]->getMessage() ?>
        </div>
    <?php endif ?>

    <table class="table-user">
        <thead>
            <tr>
                <th class="footer-description">Nom</th>
                <th class="footer-description">Photo</th>
                <th class="footer-description">Prix</th>
                <th class="footer-description">Ingrédients</th>
                <th class="footer-description">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php $pizzas = AppRepoManager::getRm()->getPizzaRepository()->getAllPizzasById(); ?>
            <?php foreach ($pizzas as $pizza) : ?>
                <tr>
                    <td class="footer-description"><?= $pizza->name ?></td>
                    <td class="footer-description">
                        <img class="admin-img-pizza" src="/assets/images/pizza/<?= $pizza->image_path ?>" alt="<?= $pizza->name ?>">
                    </td>
                    <td class="footer-description">
                        <?php foreach ($pizza->prices as $price) : ?>
                            <p><?= $price->size->label ?> : <?= number_format($price->price, 2, ',', '') ?> €</p>
                        <?php endforeach ?>
                    </td>
                    <td class="footer-description">
                        <?php foreach ($pizza->ingredients as $ingredient) : ?>
                            <p><?= $ingredient->label ?></p>
                        <?php endforeach ?>
                    </td>
                    <td class="footer-description">
                        <a onclick="return confirm('Voulez-vous supprimer cette pizza ?')" class="button-delete" href="/admin/pizza/delete/<?= $pizza->id ?>">
                            <i class="bi bi-trash"></i>
                        </a>
                        <a class="button-delete bg-warning" href="/admin/pizza/update/<?= $pizza->id ?>">
                            <i class="bi bi-gear"></i>
                        </a>

                    </td>

                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>