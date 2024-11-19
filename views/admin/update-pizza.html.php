<?php

use App\AppRepoManager;
use Core\Session\Session; ?>

<main class="container-form">
    <h1 class="title"><?php echo $pizza->name ?></h1>
    <!-- on va afficher les erreurs s'il y en a -->
    <?php if ($form_result && $form_result->hasErrors()) : ?>
        <div class="alert alert-danger" role="alert">
            <?php echo $form_result->getErrors()[0]->getMessage() ?>
        </div>
    <?php endif ?>


    <form action="/update-pizza/name" method="POST">
        <input type="hidden" name="pizza_id" value="<?= $pizza->id ?>">
        <div class="box-auth-input">
            <label class="detail-description">Nom de la pizza</label>
            <input type="text" class="form-control" name="name" value="<?= $pizza->name ?>">
        </div>
        <button type="submit" class="call-action">Modifier le nom</button>
    </form>

    <form action="/update-pizza/image" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="pizza_id" value="<?= $pizza->id ?>">
        <div class="box-auth-input">
            <label class="detail-description">Charger une image</label>
            <input type="file" class="form-control" name="image_path">
            <img src="/assets/images/pizza/<?= $pizza->image_path ?>" alt="image de la pizza" style="width: 200px; height: 200px; object-fit: contain">
        </div>
        <button type="submit" class="call-action">Modifier l'image</button>
    </form>

    <form class="auth-form" action="/update-pizza/ingredients" method="POST">
        <input type="hidden" name="pizza_id" value="<?= $pizza->id ?>">
        <div class="box-auth-input list-ingredient">
            <!-- on affiche la liste des ingrédients -->
            <?php foreach (AppRepoManager::getRm()->getIngredientRepository()->getIngredientActive() as $ingredient) :
                $checked = "";
                foreach ($pizza->ingredients as $pizza_ingredient) {
                    if ($pizza_ingredient->id == $ingredient->id) {
                    }
                }

            ?>
                <div class="form-check form-switch list-ingredient-input">
                    <input <?php echo $checked ?> name="ingredients[]" value="<?= $ingredient->id ?>" class="form-check-input" type="checkbox" role="switch">
                    <label class="form-check-label footer-description m-1"><?= $ingredient->label ?></label>
                </div>
            <?php endforeach ?>
        </div>
        <button type="submit" class="call-action">Modifier les ingrédients</button>
    </form>

    <form action="/update-pizza/price" method="POST">
        <input type="hidden" name="pizza_id" value="<?= $pizza->id ?>">
        <div class="box-auth-input list-size">
            <label class="header-description">Prix par taille :</label>
            <?php foreach ($pizza->prices as $size) : ?>
                <div class="list-size-input">
                    <input type="hidden" name="size_id[]" value="<?= $size->size_id ?>">
                    <label type="tex" class="footer-description"><?= $size->size->label ?></label>
                    <input type="text" step="0.01" class="form-control total-price-input" name="price[]" value="<?= $size->price ?>">
                </div>
            <?php endforeach ?>
        </div>

        <button type="submit" class="call-action">Modifier les prix</button>
    </form>

</main>