<?php use Core\Session\Session; ?>
<h1 class="title title-page">Liste des pizzas</h1>
<div class="admin-container">
    <?php if (Session::get(Session::USER)) { ?>
        <a class="call-action" href="/account/<?php echo Session::get(Session::USER)->id ?>" class="btn btn-primary">Créer votre pizza</a>
    <?php } else { ?>
        <a class="call-action" href="/connexion" class="btn btn-primary">Créer votre pizza</a>
    <?php } ?>
</div>
<div class="d-flex justify-content-center">
    <div class="d-flex flex-row flex-wrap my-3 justify-content-center col-lg-10">
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