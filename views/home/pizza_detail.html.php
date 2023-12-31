<h1 class="title title-detail"><?= $pizza->name ?></h1>
<div class="container-pizza-detail">
    <div class="box-image-detail">
        <img class="image-detail" src="/assets/images/pizza/<?= $pizza->image_path ?>" alt="image de<?= $pizza->name ?>">
        <div class="allergene">
            <!-- gerer affichage allergene s'il y en a-->
            <?php if (in_array(true, array_column($pizza->ingredients, 'is_allergic'))) : ?>
                <h3 class="sub-title-allergene">Allergènes :</h3>
                <!-- j'affiche le label des l'ingredient allergene-->
                <?php foreach ($pizza->ingredients as $ingredient) : ?>
                    <?php if ($ingredient->is_allergic) : ?>
                        <div>
                            <span class="badge text-bg-danger"><?= $ingredient->label ?></span>
                        </div>
                    <?php endif ?>
                <?php endforeach ?>
            <?php endif; ?>
        </div>
    </div>
    <div class="info-pizza-detail">
        <form action="/user-order" method="post" id="order-form">
            <input type="hidden" name="pizzaId" value="<?= $pizza->id ?>">
            <input type="hidden" name="pizzaName" value="<?= $pizza->name ?>">
          
        </form>

        <h3 class="sub-title sub-title-detail">Ingrédients :</h3>
        <div class="box-ingredient-detail">
            <?php foreach ($pizza->ingredients as $ingredient) : ?>
                <p class="detail-description">| <?= $ingredient->label ?> |</p>
            <?php endforeach ?>
        </div>
        <h3 class="sub-title sub-title-detail">Prix :</h3>
        <table>
            <thead>
                <tr>
                    <th class="footer-description">Taille</th>
                    <th class="footer-description">Prix</th>
                    <th class="footer-description"><i class="bi bi-bag"></i></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pizza->prices as $price) : ?>
                    <tr>
                        <td class="footer-description"><?= $price->size->label ?></td>
                        <td class="footer-description"><?= number_format($price->price, 2, ',', ' ') ?> €</td>
                        <td class="footer-description">
                            <a href="/user-order" class="call-action">
                                <i class="bi bi-plus-circle"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>