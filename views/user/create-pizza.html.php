<?php

use App\AppRepoManager;
use Core\Session\Session; ?>

<main class="container-form">
    <h1 class="title">Nouvelle Pizza</h1>
    <!-- on va afficher les erreurs s'il y en a -->
    <?php if ($form_result && $form_result->hasErrors()) : ?>
        <div class="alert alert-danger" role="alert">
            <?php echo $form_result->getErrors()[0]->getMessage() ?>
        </div>
    <?php endif ?>


    <form class="auth-form" action="/user/create-pizza" method="POST" enctype="multipart/form-data">
        <!-- On ajoute un input de type hidden pour envoyer l'id de l'user en session-->
        <input type="hidden" name="user_id" value="<?= Session::get(Session::USER)->id ?>">
        <div class="box-auth-input">
            <label class="detail-description">Nom de la pizza</label>
            <input type="text" class="form-control" name="name">
        </div>
        <div class="box-auth-input">
            <label class="detail-description">Charger une image</label>
            <input type="file" class="form-control" name="image_path">
        </div>
        <div class="box-auth-input list-ingredient">
            <!-- on affiche la liste des ingrédients -->
            <?php foreach (AppRepoManager::getRm()->getIngredientRepository()->getIngredientActive() as $ingredient) : ?>
                <div class="form-check form-switch list-ingredient-input">
                    <input name="ingredients[]" value="<?= $ingredient->id ?>" class="form-check-input" type="checkbox" role="switch">
                    <label class="form-check-label footer-description m-1"><?= $ingredient->label ?></label>
                </div>
            <?php endforeach ?>
        </div>
        <div class="box-auth-input list-size">
            <label class="header-description">Prix par taille :</label>
            <?php foreach (AppRepoManager::getRm()->getSizeRepository()->getAllSize() as $size) : ?>
                <div class="list-size-input">
                    <input type="hidden" name="size_id[]" value="<?= $size->id ?>">
                    <label type="tex" class="footer-description"><?= $size->label ?></label>
                    <input type="text" step="0.01" class="form-control total-price-input" name="price[]" readonly>
                </div>
            <?php endforeach ?>
        </div>
        <button type="submit" class="call-action">Créer la pizza</button>
    </form>

</main>

<!-- script pour limiter les ingrédients a 8max -->
<script>
    // attendre que le DOM soit chargé
    document.addEventListener('DOMContentLoaded', function() {
        // selectionne tous les checkbox
        const checkboxes = document.querySelectorAll('input[name="ingredients[]"]');
        let selectedCount = 0;
        // ajout d'eventlistener
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                // si coché
                if (this.checked) {
                    // incrementer
                    selectedCount++;
                    // si superieur a 8
                    if (selectedCount >= 8) {
                        checkboxes.forEach(cb => {
                            // on desactive les cases restantes
                            if (!cb.checked) {
                                cb.disabled = true;
                            }
                        });
                    }
                } else {
                    // sinon decrementer
                    selectedCount--;
                    // reactiver cases
                    checkboxes.forEach(cb => {
                        cb.disabled = false;
                    });
                }
            });
        });
    });
</script>

<!-- script pour calculer le prix total-->
<script>
    // attendre que le DOM soit chargé
    document.addEventListener('DOMContentLoaded', function() {
        // selectionne tous les checkbox
        const checkboxes = document.querySelectorAll('input[name="ingredients[]"]');
        const sizeInputs = document.querySelectorAll('input[name="price[]"]');
        const totalPriceInputs = document.querySelectorAll('.total-price-input');

        // ajout d'eventlistener
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateTotalPrice);
        });

        sizeInputs.forEach(sizeInput => {
            sizeInput.addEventListener('input', updateTotalPrice);
        });

        // fonction qui met à jour le prix total
        function updateTotalPrice() {
            // prix initiaux
            const initialPrices = [5, 6, 7];
           
            initialPrices.forEach((initialPrice, index) => {
                let totalIngredients = 0;

                // compte le nombre d'ingrédients cocheés
                checkboxes.forEach(checkbox => {
                    if (checkbox.checked) {
                        totalIngredients++;
                    }
                });
                // calcul du prix
                const basePrice = parseFloat(sizeInputs[index].value || 0);
                // calcul en ajoutant les prix initiaux
                const totalPrice = initialPrice + totalIngredients;
                // mise a jour du prix
                totalPriceInputs[index].value = totalPrice.toFixed(2);
            });
        }
    });
</script>